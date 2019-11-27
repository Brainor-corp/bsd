<?php

namespace App\Mail;

use App\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewLeft extends Mailable
{
    use Queueable, SerializesModels;

    private $review;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Новый отзыв')
            ->view('emails.review-left')
            ->with(['review' => $this->review]);
    }
}
