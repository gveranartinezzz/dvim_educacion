<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
Responde siempre en español, cálido y profesional.
REGLA IMPORTANTE: responde CORTO, máximo 2 o 3 oraciones (menos de 50 palabras). Sin listas largas ni introducciones. Si piden más detalle, da solo el siguiente paso clave. Si algo no tiene que ver con el Centro DIVM o el sistema, dilo en una oración.";

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

        // Solo se envían los últimos mensajes: ahorra cuota gratuita y mantiene las respuestas cortas.
        $historial = array_slice($data['messages'], -max(1, (int) config('divm.chat_historial', 6)));
        if (($historial[0]['role'] ?? '') === 'assistant') {
            array_shift($historial);
        }

        // Modelo configurado y, si Groq dice que no existe o fue retirado, los de respaldo.
        $modelos = array_values(array_unique(array_filter(array_merge(
            [config('divm.groq_model')],
            (array) config('divm.groq_respaldo', [])
        ))));

        $resp = null;
        foreach ($modelos as $modelo) {
            try {
                $resp = Http::withToken($clave)->timeout(25)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model'       => $modelo,
                    'max_tokens'  => (int) config('divm.chat_max_tokens', 150),
                    'temperature' => 0.4,
                    'messages'    => array_merge([['role' => 'system', 'content' => self::PROMPT]], $historial),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Chatbot: no hubo conexión con Groq: ' . $e->getMessage());
                return response()->json(['error' => 'No se pudo conectar con el asistente. Detalle: ' . mb_substr($e->getMessage(), 0, 140)]);
            }

            if ($resp->successful()) {
                break;
            }

            Log::warning('Chatbot Groq ' . $resp->status() . ' (modelo ' . $modelo . '): ' . mb_substr($resp->body(), 0, 500));

            // Solo se prueba otro modelo si este no existe, fue retirado o la petición fue rechazada.
            if (!in_array($resp->status(), [400, 404], true)) {
                break;
            }
        }

        if ($resp->status() === 429) {
            return response()->json(['error' => 'El asistente tiene mucho uso ahorita. Intenta de nuevo en un minuto.']);
        }
        if (in_array($resp->status(), [401, 403], true)) {
            return response()->json(['error' => 'La clave del asistente no es válida. Revisa GROQ_API_KEY en el archivo .env.']);
        }
        if (!$resp->successful()) {
            $motivo = mb_substr((string) ($resp->json('error.message') ?: $resp->body()), 0, 160);
            return response()->json(['error' => 'El asistente no respondió (código ' . $resp->status() . '): ' . $motivo]);
        }

        $texto = trim((string) $resp->json('choices.0.message.content'));
        // Si el tope de tokens cortó la respuesta a la mitad, se recorta hasta la última oración completa.
        if ($resp->json('choices.0.finish_reason') === 'length' && preg_match('/^(.*[.!?])\s/su', $texto . ' ', $m)) {
            $texto = trim($m[1]);
        }

        return response()->json([
            'respuesta' => $texto !== '' ? $texto : 'No pude generar una respuesta.',
        ]);
    }
}
