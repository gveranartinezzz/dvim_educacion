<?php

return [
    // Clave del asistente (Groq). Se escribe en el archivo .env: GROQ_API_KEY=...
    'groq_key'   => env('GROQ_API_KEY'),
    // llama-3.1-8b-instant: rápido y con el límite gratuito más amplio de Groq (alcanza para uso diario).
    'groq_model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
    // Si el modelo de arriba no existe o Groq lo retira, se prueban estos en orden.
    'groq_respaldo' => array_values(array_filter(array_map('trim', explode(',', (string) env('GROQ_RESPALDO', 'llama-3.3-70b-versatile,gemma2-9b-it'))))),
    // Respuestas cortas: tope de tokens por respuesta (150 ≈ 2-3 oraciones) y mensajes de historial que se envían.
    'chat_max_tokens' => (int) env('CHAT_MAX_TOKENS', 150),
    'chat_historial'  => (int) env('CHAT_HISTORIAL', 6),

    // Crear cuenta desde el inicio de sesión: true = la cuenta nueva queda Inactiva hasta que un administrador
    // la active (recomendado, hay datos de pacientes). false = la cuenta nueva entra de inmediato.
    'registro_aprobacion' => env('REGISTRO_APROBACION', true),

    // Verificación en dos pasos (DOS_PASOS=false en .env la apaga para todos)
    'dos_pasos'           => env('DOS_PASOS', true),
    'codigo_minutos'      => 10,   // vigencia del código
    'codigo_intentos'     => 5,    // intentos permitidos
    'codigo_reenvio_seg'  => 60,   // espera mínima entre reenvíos
];
