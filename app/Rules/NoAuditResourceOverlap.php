<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class NoAuditResourceOverlap implements Rule
{
    protected $message = [];

    protected $event;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($event = null)
    {
        $this->event = $event;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $passes = true;
        foreach($value as $resource){
            $user = User::find($resource['id']);
            if($user){
                $eventUser = $user->isAvailableOn($resource['start_end_date']);
                if($eventUser){
                    if($this->event){
                        if($eventUser->event_id == $this->event->id){
                            continue;
                        }
                    }
                    $passes = false;
                    $this->message[] = $user->full_name . ' already have schedule on this date entitled ' . $eventUser->event->TitleComputed . ' ('. $eventUser->start_date .' - '. $eventUser->end_date .')';
                }
            }
        }
        return $passes;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return implode('<br>', $this->message);
    }
}
