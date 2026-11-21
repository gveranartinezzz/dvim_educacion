<?php
/*
 * Activa las MEJORAS sin reemplazar tus archivos: solo agrega unas líneas a los que ya tienes.
 * Guarda copia (.bak) de cada archivo que toca y se puede correr varias veces sin duplicar nada.
 *
 * Uso (terminal de Laragon, dentro de la carpeta del proyecto, después de descomprimir el zip encima):
 *     php instalar_mejoras.php
 */
$raiz = getcwd();
function decir(string $t): void { echo $t . PHP_EOL; }
if (!is_file($raiz . '/artisan')) { decir('ERROR: ejecútalo dentro de la carpeta del proyecto (donde está "artisan").'); exit(1); }
foreach (['routes/mejoras.php', 'app/Http/Controllers/MejorasController.php', 'public/css/mejoras.css'] as $f) {
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

decir('Aplicando mejoras...');

parchar('app/Providers/AppServiceProvider.php', function ($s) {
    $bloque = "        // Mejoras: cabeceras de seguridad, bitácora y rutas nuevas\n"
        . "        \$this->app['router']->pushMiddlewareToGroup('web', \\App\\Http\\Middleware\\CabecerasSeguridad::class);\n"
        . "        \$this->app['router']->pushMiddlewareToGroup('web', \\App\\Http\\Middleware\\RegistrarBitacora::class);\n"
        . "        if (! \$this->app->routesAreCached()) {\n"
        . "            \\Illuminate\\Support\\Facades\\Route::middleware('web')->group(base_path('routes/mejoras.php'));\n"
        . "        }\n\n";
    return preg_replace('/(public function boot\(\)(?::\s*void)?\s*\{\R)/', '$1' . $bloque, $s, 1, $c) ?: null;
}, 'routes/mejoras.php');

parchar('resources/views/layouts/app.blade.php', function ($s) {
    return preg_replace('/(\s*)(<link[^>]*css\/divm\.css[^>]*>)/', '$1$2$1<link rel="stylesheet" href="{{ asset(\'css/mejoras.css\') }}">', $s, 1);
}, 'css/mejoras.css');

parchar('resources/views/layouts/sidebar.blade.php', function ($s) {
    if (!preg_match("/^([ \t]*)\['\/registro',/m", $s, $m)) return null;
    $i = $m[1];
    $s = preg_replace("/^[ \t]*\['\/registro',/m", $i . "['/panel',           'Panel',           'bi-speedometer2',           'panel*',           false],\n$0", $s, 1);
    $s = preg_replace("/^[ \t]*\['\/usuarios',[^\n]*\n/m",
        $i . "['/expediente',      'Expediente',      'bi-folder2-open',           'expediente*',      false],\n$0"
        . $i . "['/bitacora',        'Bitácora',        'bi-clock-history',          'bitacora*',        true],\n", $s, 1);
    return $s;
}, "'/panel'");

$logout = function ($s) {
    $re = '/<a\b([^>]*)href="\{\{\s*url\(\'\/logout\'\)\s*\}\}"([^>]*)>\s*Cerrar Sesi[óo]n\s*<\/a>/u';
    if (!preg_match($re, $s, $m)) return null;
    $attrs = $m[1] . $m[2];
    preg_match('/class="([^"]*)"/', $attrs, $c); preg_match('/style="([^"]*)"/s', $attrs, $st);
    $b = '<form method="POST" action="{{ url(\'/logout\') }}"' . (strpos($c[1] ?? '', 'w-100') !== false ? '' : ' class="d-inline"') . '>@csrf<button type="submit" class="' . ($c[1] ?? 'btn btn-danger')
       . '"' . (isset($st[1]) ? ' style="' . preg_replace('/\s+/', ' ', trim($st[1])) . '"' : '') . '>Cerrar Sesión</button></form>';
    return preg_replace($re, $b, $s, 1);
};
parchar('resources/views/layouts/sidebar.blade.php', $logout, 'action="{{ url(\'/logout\')');
parchar('resources/views/layouts/menu.blade.php', $logout, 'action="{{ url(\'/logout\')');

parchar('app/Http/Controllers/UsuarioController.php', fn ($s) => str_replace(["'contrasena' => 'required|min:6'", "'nueva_contrasena' => 'nullable|min:6'"],
    ["'contrasena' => 'required|min:8|max:72'", "'nueva_contrasena' => 'nullable|min:8|max:72'"], $s), '|min:8|max:72');

parchar('app/Http/Controllers/PacienteController.php', function ($s) {
    return preg_replace('/(public function store\(Request \$request\)\s*\{\R)/',
        "$1        if (is_string(\$request->input('nombre'))) {\n            \$request->merge(['nombre' => preg_replace('/\\\\s+/', ' ', trim(\$request->input('nombre')))]);\n        }\n", $s, 1);
}, "merge(['nombre' => preg_replace(");

parchar('app/Http/Controllers/RegistroUsuarioController.php', fn ($s) => str_replace('required|email|max:150|unique', 'required|email|max:100|unique', $s), 'email|max:100|unique');

decir('');
decir('Siguientes pasos (en la terminal del proyecto):');
decir('  php artisan migrate                      (crea la bitácora e índices; no toca tus datos)');
decir('  php artisan divm:encriptar-contrasenas   (cifra las contraseñas guardadas en texto; siguen siendo las mismas)');
decir('  php artisan optimize:clear               (y recarga el navegador con Ctrl+F5)');
decir('Cuando todo funcione puedes borrar este archivo y los .bak.');
