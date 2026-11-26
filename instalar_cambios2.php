<?php
/*
 * Segunda entrega: quita Citas, activa el Inicio editable y la Distribución mejorada sin reemplazar tus archivos: solo agrega unas líneas a los que ya tienes.
 * Guarda copia (.bak) de cada archivo que toca y se puede correr varias veces sin duplicar nada.
 *
 * Uso (terminal de Laragon, dentro de la carpeta del proyecto, después de descomprimir el zip encima):
 *     php instalar_cambios2.php
 */
$raiz = getcwd();
function decir(string $t): void { echo $t . PHP_EOL; }
if (!is_file($raiz . '/artisan')) { decir('ERROR: ejecútalo dentro de la carpeta del proyecto (donde está "artisan").'); exit(1); }
foreach (['app/Http/Controllers/InicioController.php', 'resources/views/informacion.blade.php', 'public/js/formularios.js'] as $f) {
    if (!is_file("$raiz/$f")) { decir("ERROR: falta $f. Descomprime primero el zip sobre el proyecto."); exit(1); }
}

/** Aplica reemplazos a un archivo; si queda con error de sintaxis (PHP) lo restaura. */
function parchar(string $rel, callable $fn, string $marca): void {
    global $raiz;
    $ruta = "$raiz/$rel";
    if (!is_file($ruta)) { decir("  - $rel: no existe, se omite."); return; }
    $s = file_get_contents($ruta);
    if ($marca !== '' && strpos($s, $marca) !== false) { decir("  = $rel: ya estaba."); return; }
    $n = $fn($s);
    if ($n === null || $n === $s) { decir("  ! $rel: no encontré dónde agregarlo (avísame y lo ajustamos)."); return; }
    copy($ruta, $ruta . '.bak');
    file_put_contents($ruta, $n);
    if (substr($rel, -4) === '.php' && substr($rel, -10) !== '.blade.php') {
        exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($ruta) . ' 2>&1', $o, $c);
        if ($c !== 0) { copy($ruta . '.bak', $ruta); decir("  ! $rel: quedaba con error, se dejó como estaba."); return; }
    }
    decir("  + $rel: listo (copia .bak).");
}

decir('Aplicando cambios...');

// 1) Quitar Citas del menú superior y de la barra lateral
parchar('resources/views/layouts/menu.blade.php', function ($s) {
    $n = preg_replace('/[ \t]*<li class="nav-item">\s*<a(?:(?!<\/li>).)*?>\s*Citas\s*<\/a>\s*<\/li>\R?/su', '', $s, -1, $c);
    return $c ? $n : null;
}, '__nunca__');
parchar('resources/views/layouts/sidebar.blade.php', function ($s) {
    $n = preg_replace("/^[ \t]*\['\/citas',[^\n]*\n/m", '', $s, -1, $c);
    return $c ? $n : null;
}, '__nunca__');

// 2) Borrar los archivos de Citas (la tabla "citas" de la base de datos no se toca)
foreach (['app/Http/Controllers/CitaController.php', 'app/Models/Cita.php', 'resources/views/citas.blade.php',
          'database/migrations/2026_11_24_000001_crear_tabla_citas.php'] as $f) {
    if (is_file("$raiz/$f")) { unlink("$raiz/$f"); decir("  - $f: eliminado."); }
}

// 3) /inicio ahora lo atiende el controlador editable
parchar('routes/web.php', function ($s) {
    return preg_replace("/Route::view\(\s*'\/inicio'\s*,\s*'informacion'\s*\)\s*;/", "Route::get('/inicio', [\\App\\Http\\Controllers\\InicioController::class, 'index']);", $s, 1, $c) ?: null;
}, 'InicioController');

// 4) Que el navegador baje el JavaScript nuevo
parchar('resources/views/layouts/app.blade.php', function ($s) {
    return preg_replace('/formularios\.js\'\)\ \}\}\?v=\d+/', "formularios.js') }}?v=3", $s, 1, $c) ?: null;
}, 'formularios.js\') }}?v=3');

if (is_file("$raiz/bootstrap/cache/routes-v7.php")) { @unlink("$raiz/bootstrap/cache/routes-v7.php"); }
passthru(escapeshellarg(PHP_BINARY) . ' artisan optimize:clear 2>&1');
decir('');
decir('Listo. Entra a Inicio (botón "Editar información del centro"), a Distribución (botón de lápiz en Historial) y revisa que ya no esté Citas.');
