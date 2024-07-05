<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;

use App\Models\Contact;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function created(Contact $contact)
    {
        Log::info('Contact created: ', $contact->toArray());
    }

    public function creating(Contact $contact)
    {
        Log::info('Contact creating: ', $contact->toArray());
    }
    /**
     * Handle the Contact "updated" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function updated(Contact $contact)
    {
        //
    }

    public function saving(Contact $contact)
    {
        Log::info('Saving contact: ', $contact->toArray());
    }

    public function saved(Contact $contact)
    {
        Log::info('Saved contact: ', $contact->toArray());
    }
    /**
     * Handle the Contact "deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function deleted(Contact $contact)
    {
        Log::info('Deleted contact: ', $contact->toArray());
    }

    public function deleting(contact $contact)
    {
        Log::info('Deleting contact: ', $contact->toArray());
    }

    /**
     * Handle the Contact "restored" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function restored(Contact $contact)
    {
        //
    }

    /**
     * Handle the Contact "force deleted" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function forceDeleted(Contact $contact)
    {
        //
    }
}
