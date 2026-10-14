<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/** Asistente virtual del Centro DIVM. La clave de la API nunca llega al navegador. */
class ChatbotController extends Controller
{
    private const PROMPT = "Eres el asistente virtual del Centro DIVM, especializado en informes pedagógicos y evaluación del desarrollo infantil. Ayudas al personal clínico y administrativo con:
- Registro y consulta de informes pedagógicos en el sistema
- Interpretación de áreas de desarrollo: cognitiva, motriz y social
- Orientación sobre diagnósticos como Autismo, TDAH, Síndrome de Down, Parálisis Cerebral, etc.
- Guía sobre campos del formulario: gestación, talla/peso, pruebas aplicadas, aplicadora, motivo de evaluación
- Indicaciones clínicas, familiares, escolares y rehabilitadoras
- Uso del historial clínico, filtros, estadísticas y generación de PDFs
Responde siempre en español, de manera cálida, profesional y concisa.";

    public function responder(Request $request)
    {
        $data = $request->validate([
            'messages'           => 'required|array|min:1|max:20',
            'messages.*.role'    => 'required|in:user,assistant',
            'messages.*.content' => 'required|string|max:2000',
        ]);

        $clave = config('divm.groq_key');
        if (!$clave) {
            return response()->json(['error' => 'El asistente no está configurado (falta GROQ_API_KEY en el archivo .env).']);
        }

        try {
            $resp = Http::withToken($clave)->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'      => config('divm.groq_model'),
                'max_tokens' => 1000,
                'messages'   => array_merge([['role' => 'system', 'content' => self::PROMPT]], $data['messages']),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'No se pudo conectar con el servicio del asistente.']);
        }

        if (!$resp->successful()) {
            return response()->json(['error' => $resp->json('error.message') ?: 'El asistente no respondió.']);
        }

        return response()->json([
            'respuesta' => $resp->json('choices.0.message.content') ?: 'No pude generar una respuesta.',
        ]);
    }
}
