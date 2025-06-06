<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Pass user to view

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verifyUrl = url('/api/verify-email/' . $this->user->id); // Hoặc token nếu có

        return $this->subject('Verify your email')
            ->view('emails.verify')
            ->with([
                'userName' => $this->user->userName,
                'verifyUrl' => $verifyUrl
            ]);
    }
}
