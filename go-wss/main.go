package main

import (
	"encoding/json"
	"log"
	"net/http"
	"sync"

	"github.com/gorilla/websocket"
)

type Client struct {
	ID       string
	Conn     *websocket.Conn
	Channels map[string]bool
}

type Server struct {
	Clients    map[string]*Client
	Channels   map[string]map[*Client]bool
	Register   chan *Client
	Unregister chan *Client
	Broadcast  chan Message
	mu         sync.Mutex
}

type Message struct {
	ChannelID string `json:"channel_id"`
	Data      string `json:"data"`
}

type JoinRequest struct {
	Type     string   `json:"type"`
	Channels []string `json:"channels"`
}

func NewServer() *Server {
	return &Server{
		Clients:    make(map[string]*Client),
		Channels:   make(map[string]map[*Client]bool),
		Register:   make(chan *Client),
		Unregister: make(chan *Client),
		Broadcast:  make(chan Message),
	}
}

func (s *Server) Run() {
	for {
		select {
		case client := <-s.Register:
			s.mu.Lock()
			s.Clients[client.ID] = client
			s.mu.Unlock()
			log.Printf("Client registered: %s", client.ID)
		case client := <-s.Unregister:
			s.mu.Lock()
			if _, ok := s.Clients[client.ID]; ok {
				delete(s.Clients, client.ID)
				for channelID := range client.Channels {
					s.removeClientFromChannel(client, channelID)
				}
				client.Conn.Close()
				log.Printf("Client unregistered: %s", client.ID)
			}
			s.mu.Unlock()
		case message := <-s.Broadcast:
			s.mu.Lock()
			log.Printf("Broadcasting message: %v", message)
			if clients, ok := s.Channels[message.ChannelID]; ok {
				for client := range clients {
					err := client.Conn.WriteJSON(message)
					if err != nil {
						log.Printf("Error broadcasting message to client %s: %v", client.ID, err)
						client.Conn.Close()
						delete(s.Clients, client.ID)
					}
				}
			} else {
				log.Printf("No clients found for channel: %s", message.ChannelID)
			}
			s.mu.Unlock()
		}
	}
}

func (s *Server) addClientToChannel(client *Client, channelID string) {
	if _, ok := s.Channels[channelID]; !ok {
		s.Channels[channelID] = make(map[*Client]bool)
	}
	s.Channels[channelID][client] = true
	log.Printf("Client %s added to channel %s", client.ID, channelID)
}

func (s *Server) removeClientFromChannel(client *Client, channelID string) {
	if clients, ok := s.Channels[channelID]; ok {
		if _, ok := clients[client]; ok {
			delete(clients, client)
			if len(clients) == 0 {
				delete(s.Channels, channelID)
			}
			log.Printf("Client %s removed from channel %s", client.ID, channelID)
		}
	}
}

var upgrader = websocket.Upgrader{
	ReadBufferSize:  1024,
	WriteBufferSize: 1024,
	CheckOrigin: func(r *http.Request) bool {

		return true
	},
}

func (s *Server) handleWebSocket(w http.ResponseWriter, r *http.Request) {
	conn, err := upgrader.Upgrade(w, r, nil)
	if err != nil {
		log.Printf("Failed to upgrade connection: %v", err)
		return
	}

	clientID := r.URL.Query().Get("client_id")
	client := &Client{
		ID:       clientID,
		Conn:     conn,
		Channels: make(map[string]bool),
	}

	s.Register <- client

	go s.handleClientMessages(client)
}

func (s *Server) handleClientMessages(client *Client) {
	defer func() {
		s.Unregister <- client
		client.Conn.Close()
	}()

	for {
		var rawMessage map[string]interface{}
		err := client.Conn.ReadJSON(&rawMessage)
		if err != nil {
			log.Printf("Error reading message from client %s: %v", client.ID, err)
			break
		}

		log.Printf("Received raw message from client %s: %v", client.ID, rawMessage)

		if msgType, ok := rawMessage["type"].(string); ok && msgType == "join" {
			var joinRequest JoinRequest
			err := mapToStruct(rawMessage, &joinRequest)
			if err != nil {
				log.Printf("Error parsing join request from client %s: %v", client.ID, err)
				break
			}

			log.Printf("Received join request from client %s: %v", client.ID, joinRequest)

			for _, channelID := range joinRequest.Channels {
				client.Channels[channelID] = true
				s.addClientToChannel(client, channelID)
			}
		} else {
			var message Message
			err := mapToStruct(rawMessage, &message)
			if err != nil {
				log.Printf("Error parsing regular message from client %s: %v", client.ID, err)
				break
			}

			log.Printf("Received message from client %s: %v", client.ID, message)
			s.Broadcast <- message
		}
	}
}

func mapToStruct(input map[string]interface{}, output interface{}) error {

	bytes, err := json.Marshal(input)
	if err != nil {
		return err
	}
	return json.Unmarshal(bytes, output)
}

func main() {
	server := NewServer()
	go server.Run()

	http.HandleFunc("/ws", server.handleWebSocket)

	log.Println("Server started on :8080")
	err := http.ListenAndServe(":8080", nil)
	if err != nil {
		log.Fatalf("ListenAndServe: %v", err)
	}
}
