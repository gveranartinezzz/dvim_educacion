<?php
/*
 * Activa FORMULARIOS (nombres claros, ayudas, validación, confirmaciones) y CITAS sin reemplazar tus archivos: solo agrega unas líneas a los que ya tienes.
 * Guarda copia (.bak) de cada archivo que toca y se puede correr varias veces sin duplicar nada.
 *
 * Uso (terminal de Laragon, dentro de la carpeta del proyecto, después de descomprimir el zip encima):
 *     php instalar_formularios.php
 */
$raiz = getcwd();
function decir(string $t): void { echo $t . PHP_EOL; }
if (!is_file($raiz . '/artisan')) { decir('ERROR: ejecútalo dentro de la carpeta del proyecto (donde está "artisan").'); exit(1); }
foreach (['public/js/formularios.js', 'app/Http/Middleware/ValidarFormularios.php', 'app/Http/Controllers/CitaController.php', 'resources/views/citas.blade.php'] as $f) {
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

parchar('app/Providers/AppServiceProvider.php', function ($s) {
    $linea = "        \$this->app['router']->pushMiddlewareToGroup('web', \\App\\Http\\Middleware\\ValidarFormularios::class);\n";
    if (!preg_match('/pushMiddlewareToGroup\(\'web\', \\\\App\\\\Http\\\\Middleware\\\\RegistrarBitacora::class\);\R/', $s, $m)) return null;
    return preg_replace('/(pushMiddlewareToGroup\(\'web\', \\\\App\\\\Http\\\\Middleware\\\\RegistrarBitacora::class\);\R)/', '$1' . $linea, $s, 1);
}, 'ValidarFormularios');

parchar('resources/views/layouts/app.blade.php', function ($s) {
    return preg_replace('/(\s*)(@stack\(\'scripts\'\))/', '$1<script src="{{ asset(\'js/formularios.js\') }}?v=1"></script>$1$2', $s, 1);
}, 'js/formularios.js');

parchar('resources/views/layouts/sidebar.blade.php', function ($s) {
    if (!preg_match("/^([ \t]*)\['\/usuarios',/m", $s, $m)) return null;
    return preg_replace("/^[ \t]*\['\/usuarios',/m", $m[1] . "['/citas',           'Citas',           'bi-calendar-check',         'citas*',           false],\n$0", $s, 1);
}, "'/citas'");

parchar('resources/views/layouts/menu.blade.php', function ($s) {
    $li = "                <li class=\"nav-item\">\n                    <a class=\"nav-link text-white px-3 menu-link {{ request()->is('citas*') ? 'active-link' : '' }}\"\n                       href=\"{{ url('/citas') }}\">Citas</a>\n                </li>\n";
    $pos = strrpos($s, '</ul>');
    if ($pos === false) return null;
    return substr($s, 0, $pos) . $li . '            ' . substr($s, $pos);
}, "url('/citas')");

if (is_file("$raiz/bootstrap/cache/routes-v7.php")) { @unlink("$raiz/bootstrap/cache/routes-v7.php"); }
decir('Creando la tabla de citas (si falta)...');
passthru(escapeshellarg(PHP_BINARY) . ' artisan migrate --force 2>&1');
passthru(escapeshellarg(PHP_BINARY) . ' artisan optimize:clear 2>&1');
decir('');
decir('Listo. Abre el sistema y prueba: Pacientes (ayudas bajo cada campo), eliminar un registro (ventana de confirmación) y el menú Citas.');
