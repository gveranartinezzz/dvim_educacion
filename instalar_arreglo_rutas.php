<?php
/* Arregla routes/web.php para que Railway pueda guardar las rutas en caché
   (cambia las dos funciones de acceso por clases). Uso: php instalar_arreglo_rutas.php */
$f = getcwd() . '/routes/web.php';
foreach (['routes/web.php', 'app/Http/Middleware/SoloSesion.php', 'app/Http/Middleware/SoloAdmin.php'] as $r) {
    if (!is_file(getcwd() . '/' . $r)) { echo "ERROR: falta $r (descomprime el zip en la carpeta del proyecto).\n"; exit(1); }
}
$s = file_get_contents($f);
if (strpos($s, 'SoloSesion::class') !== false) { echo "Ya estaba arreglado.\n"; exit(0); }
$n = preg_replace('/\$soloSesion\s*=\s*function\s*\([^)]*\)\s*\{.*?\n\};/s', '$soloSesion = \App\Http\Middleware\SoloSesion::class;', $s, 1, $c1);
$n = preg_replace('/\$soloAdmin\s*=\s*function\s*\([^)]*\)\s*\{.*?\n\};/s', '$soloAdmin = \App\Http\Middleware\SoloAdmin::class;', $n, 1, $c2);
if ($c1 !== 1 || $c2 !== 1) { echo "No encontré las dos funciones en web.php; no cambié nada. Mándame tu routes/web.php.\n"; exit(1); }
copy($f, $f . '.bak'); file_put_contents($f, $n);
exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($f), $o, $c);
if ($c !== 0) { copy($f . '.bak', $f); echo "Quedó con error; se restauró.\n"; exit(1); }
echo "Listo: routes/web.php arreglado (copia: routes/web.php.bak).\n";
