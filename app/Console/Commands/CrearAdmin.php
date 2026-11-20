<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Crea un Administrador con la contraseña ya cifrada (bcrypt) o cambia la contraseña de un usuario existente.
 * Sirve para el primer acceso en un servidor nuevo (Railway) sin importar usuarios con contraseña en texto plano,
 * y para restablecer una contraseña cuando el correo del servidor todavía no funciona.
 *
 *   php artisan divm:crear-admin correo@ejemplo.com
 *
 * - Usuario nuevo: queda como Administrador activo (o el rol de --rol).
 * - Usuario existente: SOLO cambia la contraseña; su rol y estado se conservan (salvo que pases --rol).
 */
class CrearAdmin extends Command
{
    protected $signature = 'divm:crear-admin
                            {correo : Correo del administrador}
                            {--nombre=Administrador : Nombre(s)}
                            {--apaterno=DIVM : Apellido paterno}
                            {--amaterno=Sistema : Apellido materno}
                            {--rol= : Administrador o Personal (nuevo: Administrador por defecto; existente: se conserva)}
                            {--dos-pasos : Pedir código por correo al iniciar sesión (solo si el correo del servidor ya funciona)}';

    protected $description = 'Crea un Administrador o cambia la contraseña de un usuario, con la contraseña cifrada.';

    public function handle(): int
    {
        $correo = trim((string) $this->argument('correo'));
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->error('El correo no es válido.');
            return self::FAILURE;
        }

        // Plan B si la terminal no deja escribir contraseñas: variable DIVM_ADMIN_PASSWORD (bórrala después).
        $enEntorno = (string) getenv('DIVM_ADMIN_PASSWORD');
        $clave = $enEntorno !== '' ? $enEntorno : (string) $this->secret('Contraseña (mínimo 8 caracteres)');

        if (mb_strlen($clave) < 8) {
            $this->error('La contraseña debe tener al menos 8 caracteres.');
            return self::FAILURE;
        }
        if ($enEntorno === '' && !hash_equals($clave, (string) $this->secret('Repite la contraseña'))) {
            $this->error('Las contraseñas no coinciden.');
            return self::FAILURE;
        }

        $rol = $this->option('rol');
        if ($rol !== null && $rol !== '' && !in_array($rol, ['Administrador', 'Personal'], true)) {
            $this->error('El rol debe ser Administrador o Personal.');
            return self::FAILURE;
        }

        $existente = Usuario::query()->where('correo', $correo)->first();

        if ($existente) {
            // Solo la contraseña (y lo que se pida expresamente): no se sube de rol a nadie por accidente
            $cambios = ['password' => Hash::make($clave)];
            if ($rol) {
                $cambios['rol'] = $rol;
            }
            if ($this->option('dos-pasos')) {
                $cambios['dos_factores_activado'] = 1;
            }
            Usuario::query()->where('id_usuario', $existente->id_usuario)->update($cambios);

            $this->info("Listo: se cambió la contraseña de {$correo}. Rol: " . ($rol ?: $existente->rol) . ", estado: {$existente->estado}.");
            if ($existente->estado !== 'Activo') {
                $this->warn('Ese usuario está Inactivo y no podrá entrar hasta que un administrador lo active.');
            }
        } else {
            Usuario::query()->create([
                'correo'                => $correo,
                'nombre'                => (string) $this->option('nombre'),
                'apaterno'              => (string) $this->option('apaterno'),
                'amaterno'              => (string) $this->option('amaterno'),
                'password'              => Hash::make($clave),
                'rol'                   => $rol ?: 'Administrador',
                'estado'                => 'Activo',
                'dos_factores_activado' => $this->option('dos-pasos') ? 1 : 0,
            ]);
            $this->info("Listo: se creó el usuario {$correo} como " . ($rol ?: 'Administrador') . '.');
        }

        return self::SUCCESS;
    }
}
