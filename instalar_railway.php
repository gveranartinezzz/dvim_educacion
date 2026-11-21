<?php
/* Prepara el proyecto para Railway: confía en el proxy de Railway (para que funcione con https).
   Uso: php instalar_railway.php   (se puede correr varias veces) */
$f = getcwd() . '/bootstrap/app.php';
if (!is_file($f)) { echo "ERROR: ejecútalo en la carpeta del proyecto.\n"; exit(1); }
$s = file_get_contents($f);
if (strpos($s, 'trustProxies') !== false) { echo "Ya estaba.\n"; exit(0); }
$n = preg_replace('/(->withMiddleware\(function \(Middleware \$middleware\)(?:\s*:\s*void)?\s*\{\R)/',
    "$1        \$middleware->trustProxies(at: '*');\n", $s, 1);
if ($n === null || $n === $s) { echo "No pude editarlo. Agrega dentro de withMiddleware:  \$middleware->trustProxies(at: '*');\n"; exit(1); }
copy($f, $f . '.bak'); file_put_contents($f, $n);
exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($f), $o, $c);
if ($c !== 0) { copy($f . '.bak', $f); echo "Quedó con error; se restauró.\n"; exit(1); }
echo "Listo: bootstrap/app.php preparado para Railway.\n";
