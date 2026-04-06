<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;



class VerificarCuentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $link;

    public function __construct($usuario, $link)
    {
        $this->usuario = $usuario;
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Verifica tu cuenta')
                    ->view('mails.verificar-cuenta');
    }
}
