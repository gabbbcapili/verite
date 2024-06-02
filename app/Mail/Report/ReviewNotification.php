<?php

namespace App\Mail\Report;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ReportReview;

class ReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ReportReview $reportReview)
    {
        $this->reportReview = $reportReview;
        $this->report = $this->reportReview->report;
        $this->user = $this->reportReview->created_by_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.report.review_notification', ['reportReview' => $this->reportReview, 'report' => $this->report, 'user' => $this->user])
                    ->subject('Report Review ' . $this->report->title);
    }
}
