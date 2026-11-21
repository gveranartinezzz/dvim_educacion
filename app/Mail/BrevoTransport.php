<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * Envía el correo por la API web de Brevo (https), no por SMTP.
 * Sirve en Railway gratis/Hobby, donde los puertos SMTP están bloqueados.
 * .env:  MAIL_MAILER=brevo   BREVO_API_KEY=xkeysib-...
 */
class BrevoTransport extends AbstractTransport
{
    public function __construct(private string $apiKey)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $de = $email->getFrom()[0];
        $para = array_map(fn ($a) => array_filter(['email' => $a->getAddress(), 'name' => $a->getName()]), $email->getTo());

        $datos = array_filter([
            'sender'      => array_filter(['email' => $de->getAddress(), 'name' => $de->getName()]),
            'to'          => $para,
            'subject'     => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody(),
            'textContent' => $email->getTextBody(),
        ]);

        Http::withHeaders(['api-key' => $this->apiKey, 'accept' => 'application/json'])
            ->timeout(15)
            ->post('https://api.brevo.com/v3/smtp/email', $datos)
            ->throw();
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
