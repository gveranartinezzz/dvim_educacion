<?php

return [
    // Clave del asistente (Groq). Se escribe en el archivo .env: GROQ_API_KEY=...
    'groq_key'   => env('GROQ_API_KEY'),
    'groq_model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),

    // Verificación en dos pasos (DOS_PASOS=false en .env la apaga para todos)
    'dos_pasos'           => env('DOS_PASOS', true),
    'codigo_minutos'      => 10,   // vigencia del código
    'codigo_intentos'     => 5,    // intentos permitidos
    'codigo_reenvio_seg'  => 60,   // espera mínima entre reenvíos
];
