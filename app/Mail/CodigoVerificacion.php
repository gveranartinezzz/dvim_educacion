<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

/** Correo con el código de 6 dígitos de la verificación en dos pasos. */
class CodigoVerificacion extends Mailable
{
    public function __construct(
        public string $nombre,
        public string $codigo,
        public int $minutos = 10,
    ) {}

    public function build()
    {
        return $this->subject('Código de verificación - DIVM Educación Especial')
                    ->view('emails.codigo');
    }
}
