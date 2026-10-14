<?php
$servidor = "localhost";
$usuario = "root";
$password = ""; // VACÍO en Laragon
$bd = "divm";   // Asegúrate de que tu BD en HeidiSQL se llame exactamente 'divm'

$conexion = new mysqli($servidor, $usuario, $password, $bd);
if ($conexion->connect_error) {
    die("Error: " . $conexion->connect_error);
}
?>
