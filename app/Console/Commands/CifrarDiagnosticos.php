<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CifrarDiagnosticos extends Command
{
    protected $signature = 'diagnosticos:cifrar';
    protected $description = 'Cifra los diagnósticos que todavía están guardados como texto normal (se puede correr varias veces)';

    private const CAMPOS = [
        ['pacientes', 'id', 'diagnostico'],
        ['informes', 'id', 'hc_diagnostico'],
        ['informes_valoracion', 'id', 'hc_diagnostico'],
    ];

    public function handle(): int
    {
        foreach (self::CAMPOS as [$tabla, $pk, $col]) {
            if (!Schema::hasTable($tabla) || !Schema::hasColumn($tabla, $col)) { $this->line("- $tabla.$col: no existe, se omite."); continue; }

            // El texto cifrado es más largo que el original: la columna debe ser TEXT
            try { Schema::table($tabla, fn ($t) => $t->text($col)->nullable()->change()); } catch (\Throwable $e) { $this->warn("  No pude ampliar $tabla.$col: " . $e->getMessage()); }

            $n = 0;
            DB::table($tabla)->select($pk, $col)->whereNotNull($col)->where($col, '!=', '')->orderBy($pk)
                ->chunkById(200, function ($filas) use ($tabla, $pk, $col, &$n) {
                    foreach ($filas as $f) {
                        try { Crypt::decryptString($f->$col); continue; } catch (DecryptException $e) {}
                        DB::table($tabla)->where($pk, $f->$pk)->update([$col => Crypt::encryptString((string) $f->$col)]);
                        $n++;
                    }
                }, $pk);
            $this->info("+ $tabla.$col: $n diagnóstico(s) cifrado(s).");
        }
        $this->newLine();
        $this->warn('Guarda una copia de tu APP_KEY (archivo .env). Sin ella no se pueden leer los diagnósticos cifrados.');
        return self::SUCCESS;
    }
}
