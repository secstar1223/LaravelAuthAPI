<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $acceptUrl;
    public $registerUrl;
    public $team;

    public function __construct($team, $acceptUrl, $registerUrl)
    {
        $this->acceptUrl = $acceptUrl;
        $this->registerUrl = $registerUrl;
        $this->team = $team;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'noreply@bookings247.co', $name = 'bookings247.co')->view('mail.teamInvitation');
    }
}
