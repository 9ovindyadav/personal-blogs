<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::info('Created user: ', $user->toArray());
    }

    public function creating(User $user)
    {
        Log::info('Creating user: ', $user->toArray());
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Log::info('Updated user: ', $user->getDirty());      
    }

    public function updating(User $user)
    {
        Log::info('Updating user: ', $user->getDirty());
        // dd($user->toArray());
    }

    public function saving(User $user)
    {
        Log::info('Saving user: ', $user->toArray());
    }

    public function saved(User $user)
    {
        // dd($user->getChanges());
        Log::info('Saved user: ', $user->toArray());
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
