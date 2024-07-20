FROM golang:1.22.5-bookworm

WORKDIR /go/src/app

COPY ../go-wss .

RUN go get -u

CMD ["go", "run", "main.go"]