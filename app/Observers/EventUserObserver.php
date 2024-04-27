<?php

namespace App\Observers;

use App\Models\EventUser;
use App\Mail\Schedule\Notification;
use Illuminate\Support\Facades\Mail;

class EventUserObserver
{
    /**
     * Handle the EventUser "created" event.
     *
     * @param  \App\Models\EventUser  $eventUser
     * @return void
     */
    public function created(EventUser $eventUser)
    {
        $event = $eventUser->event;
        if($event->type == 'Audit Schedule'){
            if($eventUser->modelable_type == 'App\Models\User'){
                Mail::to($eventUser->modelable)->send(new Notification($eventUser, 'New'));
            }
        }
    }

    /**
     * Handle the EventUser "updated" event.
     *
     * @param  \App\Models\EventUser  $eventUser
     * @return void
     */
    public function updated(EventUser $eventUser)
    {
        $event = $eventUser->event;
        if($event->type == 'Audit Schedule'){
            if($eventUser->modelable_type == 'App\Models\User'){
                if ($eventUser->isDirty('start_date') || $eventUser->isDirty('end_date')) {
                    $oldValueStart = trim($eventUser->getOriginal('start_date'));
                    $newValueStart = trim($eventUser->start_date);

                    $oldValueEnd = trim($eventUser->getOriginal('end_date'));
                    $newValueEnd = trim($eventUser->end_date);
                    if(($oldValueStart != $newValueStart) || ($oldValueEnd != $newValueEnd)){
                        Mail::to($eventUser->modelable)->send(new Notification($eventUser, 'Modified'));
                    }
                }
            }
        }
    }

    /**
     * Handle the EventUser "deleted" event.
     *
     * @param  \App\Models\EventUser  $eventUser
     * @return void
     */
    public function deleted(EventUser $eventUser)
    {
        //
    }

    /**
     * Handle the EventUser "restored" event.
     *
     * @param  \App\Models\EventUser  $eventUser
     * @return void
     */
    public function restored(EventUser $eventUser)
    {
        //
    }

    /**
     * Handle the EventUser "force deleted" event.
     *
     * @param  \App\Models\EventUser  $eventUser
     * @return void
     */
    public function forceDeleted(EventUser $eventUser)
    {
        //
    }
}
