<?php

namespace App\Mail\Schedule;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EventUser;
use Spatie\CalendarLinks\Link;
use Carbon\Carbon;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as SpatieEvent;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventUser $eventUser, $type = 'New')
    {
        $this->eventUser = $eventUser;
        $this->user = $eventUser->modelable;
        $this->event = $eventUser->event;
        $this->schedule = $this->event->schedule;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'New Schedule';
        if($this->type == 'Modified'){
            $subject = 'Modified Schedule';
        }

        // Create a new Calendar
        $calendar = Calendar::create()
            ->name($subject . ' - ' . $this->event->titleComputed)
            ->description('Audit Schedule - ' . $this->event->titleComputed)
            ->event(SpatieEvent::create()
                ->name($subject . ' - ' . $this->event->titleComputed)
                ->description('Audit Schedule - ' . $this->event->titleComputed)
                ->startsAt(Carbon::parse($this->eventUser->start_date))
                ->endsAt(Carbon::parse($this->eventUser->end_date))
                ->address($this->schedule->city . ' ' . $this->schedule->country));

        // Generate the iCalendar (.ics) content
        $icalContent = $calendar->get();

        $subject = $subject . ' - '. $this->event->titleComputed;
        return $this->markdown('emails.schedule.notification', ['user' => $this->user, 'eventUser' => $this->eventUser, 'event' => $this->event])
                    ->subject($subject)->attachData($icalContent, 'meeting_invite.ics', [
                        'mime' => 'text/calendar',
                    ]);
    }
}
