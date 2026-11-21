<?php

namespace App\Providers;

use App\Mail\BrevoTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class BrevoMailServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        config(['mail.mailers.brevo' => ['transport' => 'brevo']]);
        Mail::extend('brevo', fn () => new BrevoTransport((string) env('BREVO_API_KEY')));
    }
}
