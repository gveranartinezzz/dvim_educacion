<?php
/* Registra el envío de correo por API (Brevo) en bootstrap/providers.php. Uso: php instalar_correo_brevo.php */
$f = getcwd() . '/bootstrap/providers.php';
if (!is_file($f) || !is_file(getcwd() . '/app/Providers/BrevoMailServiceProvider.php')) { echo "ERROR: ejecútalo en la carpeta del proyecto, con el zip ya descomprimido.\n"; exit(1); }
$s = file_get_contents($f);
if (strpos($s, 'BrevoMailServiceProvider') !== false) { echo "Ya estaba registrado.\n"; exit(0); }
$n = preg_replace('/(\];\s*)$/', "    App\\Providers\\BrevoMailServiceProvider::class,\n$1", $s, 1);
if ($n === null || $n === $s) { echo "No pude editarlo: agrega a mano  App\\Providers\\BrevoMailServiceProvider::class,  dentro de bootstrap/providers.php\n"; exit(1); }
copy($f, $f . '.bak'); file_put_contents($f, $n);
exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($f), $o, $c);
if ($c !== 0) { copy($f . '.bak', $f); echo "Quedó con error; se restauró.\n"; exit(1); }
echo "Listo. En el .env pon MAIL_MAILER=brevo y BREVO_API_KEY=tu_clave; luego: php artisan optimize:clear\n";
