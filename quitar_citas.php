<?php
/* Quita "Citas" del menú superior y de la barra lateral. Uso: php quitar_citas.php (dentro del proyecto) */
foreach (['resources/views/layouts/menu.blade.php', 'resources/views/layouts/sidebar.blade.php'] as $rel) {
    if (!is_file($rel)) { echo "- $rel: no existe\n"; continue; }
    $s = file_get_contents($rel); $o = $s;
    // bloque <li ...> ... Citas ... </li>
    $s = preg_replace('/[ \t]*<li\b(?:(?!<\/li>).)*?(?:\/citas|>\s*Citas\s*<)(?:(?!<\/li>).)*?<\/li>\R?/su', '', $s);
    // fila del arreglo $modulos: ['/citas', ...],
    $s = preg_replace("/^[ \t]*\['\/citas',[^\n]*\n/m", '', $s);
    if ($s === $o) { echo "= $rel: ya no tenía Citas\n"; continue; }
    copy($rel, $rel . '.bak'); file_put_contents($rel, $s); echo "+ $rel: Citas quitado (copia .bak)\n";
}
@unlink('bootstrap/cache/routes-v7.php');
passthru(escapeshellarg(PHP_BINARY) . ' artisan optimize:clear 2>&1');
