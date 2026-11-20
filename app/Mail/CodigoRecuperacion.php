<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

/** Correo con el código de 6 dígitos para recuperar la contraseña. */
class CodigoRecuperacion extends Mailable
{
    public function __construct(
        public string $nombre,
        public string $codigo,
        public int $minutos = 10,
    ) {}

    public function build()
    {
        return $this->subject('Recuperar contraseña - DIVM Educación Especial')
                    ->view('emails.recuperar');
    }
}
