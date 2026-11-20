<?php
/*
 * Agrega "Crear cuenta" al inicio de sesión SIN reemplazar tus archivos:
 * solo inserta las 2 rutas en routes/web.php y el botón en resources/views/inicio.blade.php.
 * Guarda copia (.bak) de lo que toca y se puede correr varias veces sin duplicar nada.
 *
 * Uso (en la terminal de Laragon, dentro de la carpeta del proyecto):
 *     php instalar_crear_cuenta.php
 */

$raiz = getcwd();
$web  = $raiz . '/routes/web.php';
$ini  = $raiz . '/resources/views/inicio.blade.php';
$ok   = true;

function decir(string $t): void { echo $t . PHP_EOL; }

if (!is_file($raiz . '/artisan') || !is_file($web) || !is_file($ini)) {
    decir('ERROR: ejecútalo dentro de la carpeta del proyecto (donde está el archivo "artisan").');
    exit(1);
}

foreach (['app/Http/Controllers/RegistroUsuarioController.php', 'resources/views/crear_cuenta.blade.php'] as $f) {
    if (!is_file($raiz . '/' . $f)) {
        decir("ERROR: falta $f. Primero descomprime el zip sobre el proyecto y vuelve a correr este archivo.");
        exit(1);
    }
}

function eol_de(string $s): string { return strpos($s, "\r\n") !== false ? "\r\n" : "\n"; }

function sintaxis_ok(string $archivo): bool {
    $salida = []; $codigo = 0;
    exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($archivo) . ' 2>&1', $salida, $codigo);
    return $codigo === 0;
}

/* ---------- 1) Rutas ---------- */
$s = file_get_contents($web);
if (strpos($s, 'crear-cuenta') !== false) {
    decir('Rutas: ya estaban agregadas.');
} else {
    $eol = eol_de($s);
    $lineas = [
        "// Crear cuenta (público): la cuenta nueva queda pendiente hasta que un administrador la active",
        "Route::get('/crear-cuenta', [\\App\\Http\\Controllers\\RegistroUsuarioController::class, 'form'])->name('crear-cuenta');",
        "Route::post('/crear-cuenta', [\\App\\Http\\Controllers\\RegistroUsuarioController::class, 'guardar'])->middleware('throttle:5,10')->name('crear-cuenta.post'); // máx. 5 intentos cada 10 minutos",
    ];
    $bloque = implode($eol, $lineas);

    // Se agrega justo debajo de la ruta POST del login (zona pública)
    if (preg_match("/^[^\r\n]*Route::post\(\s*'\/login'[^\r\n]*/m", $s, $m, PREG_OFFSET_CAPTURE)) {
        $pos = $m[0][1] + strlen($m[0][0]);
        $nuevo = substr($s, 0, $pos) . $eol . $bloque . substr($s, $pos);
    } else {
        $nuevo = rtrim($s) . $eol . $eol . $bloque . $eol;
    }

    copy($web, $web . '.bak');
    file_put_contents($web, $nuevo);
    if (sintaxis_ok($web)) {
        decir('Rutas: agregadas en routes/web.php (copia: routes/web.php.bak).');
    } else {
        copy($web . '.bak', $web);
        decir('ERROR: no se pudo agregar sin romper routes/web.php; se dejó como estaba.');
        $ok = false;
    }
}

/* ---------- 2) Botón en el inicio de sesión ---------- */
$s = file_get_contents($ini);
if (strpos($s, 'crear-cuenta') !== false) {
    decir('Botón: ya estaba agregado.');
} else {
    $eol = eol_de($s);
    $boton = '<a href="{{ url(\'/crear-cuenta\') }}" class="btn w-100 py-2 mt-2" '
           . 'style="border: 2px solid #580c33; color: #580c33; font-weight: bold;">Crear cuenta nueva</a>';

    // Primer <form ...login...> y su </form>
    if (preg_match('/<form[^>]*login[^>]*>/i', $s, $m, PREG_OFFSET_CAPTURE)
        && ($fin = stripos($s, '</form>', $m[0][1])) !== false) {
        $pos = $fin + strlen('</form>');
        $nuevo = substr($s, 0, $pos) . $eol . $eol . '                    ' . $boton . substr($s, $pos);
        copy($ini, $ini . '.bak');
        file_put_contents($ini, $nuevo);
        decir('Botón: agregado en resources/views/inicio.blade.php (copia: inicio.blade.php.bak).');
    } else {
        decir('AVISO: no encontré el formulario de inicio de sesión. Pega esta línea debajo del botón "Ingresar" de inicio.blade.php:');
        decir('    ' . $boton);
        $ok = false;
    }
}

decir('');
decir($ok ? 'Listo. Ahora corre:  php artisan optimize:clear   y abre el inicio de sesión (Ctrl+F5).'
          : 'Terminó con avisos: revísalos arriba.');
decir('Cuando termines puedes borrar este archivo (instalar_crear_cuenta.php) y los .bak.');
exit($ok ? 0 : 1);
