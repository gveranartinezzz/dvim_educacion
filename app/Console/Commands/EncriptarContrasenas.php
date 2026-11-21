<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Cifra las contraseñas que todavía están guardadas en texto plano.
 * Uso:  php artisan divm:encriptar-contrasenas
 * (Los usuarios siguen entrando con la MISMA contraseña de siempre.)
 */
class EncriptarContrasenas extends Command
{
    protected $signature = 'divm:encriptar-contrasenas';
    protected $description = 'Cifra (bcrypt) las contraseñas de usuarios que están en texto plano';

    public function handle(): int
    {
        $total = 0;
        foreach (DB::table('usuarios')->select('id_usuario', 'password')->get() as $u) {
            $p = (string) $u->password;
            if ($p === '' || preg_match('/^\$(2y|2a|argon2i|argon2id)\$/', $p)) {
                continue;
            }
            DB::table('usuarios')->where('id_usuario', $u->id_usuario)->update(['password' => Hash::make($p)]);
            $total++;
        }
        $this->info($total === 0 ? 'Todas las contraseñas ya estaban cifradas.' : "Contraseñas cifradas: $total.");
        return self::SUCCESS;
    }
}
