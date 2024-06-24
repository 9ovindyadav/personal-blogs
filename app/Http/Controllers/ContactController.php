<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\User;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.form',['formTitle' => 'Add New Contact']);
    }

    public function store()
    {
        $attributes = request()->validate([
            'phone' => ['min:10','max:10']
        ]);
        
        $user = auth()->user();

        $user->relation('contact','M:M')->create(['phone' => $attributes['phone']]);

        return redirect("/profile/{$user->username}")->with('status',"{$attributes['phone']} contact created successfully");
    }

    public function edit(Contact $contact)
    {
        return view('contact.form',
                    [
                        'formTitle' => 'Edit Contact', 
                        'contact' => $contact
                    ]
                );
    }

    public function update()
    {
        $attributes = request()->validate([
            'phone' => ['min:10','max:10'],
            'contact_id' => 'int'
        ]);

        $contact = Contact::find($attributes['contact_id']);

        $contact->update(['phone' => $attributes['phone']]);
        $user = auth()->user();
        return redirect("/profile/{$user->username}")->with('status',"{$attributes['phone']} contact updated successfully");
    }

    public function delete(Contact $contact)
    {
        $user = auth()->user();

        $user->relation('contact','M:M')->detach($contact);
        $contact->delete();

        return redirect("/profile/{$user->username}")->with('status',"{$contact->phone} contact deleted successfully");
    }
}
