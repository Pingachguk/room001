<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
//        $subject = 'Заявка на верификацию FITROOM.RU';
//        return $this->markdown('emails.verification');
        foreach ($this->data->images as $image) {
            $this->attach(storage_path("app/public/{$image}"));
        }

        return $this->markdown('emails.verification');
    }
}
