<?php

namespace App\Libraries;

define('FPDF_FONTPATH', __DIR__ . '/fpdf/font/');
require_once __DIR__ . '/fpdf/fpdf.php';

/**
 * PDF institucional del Centro DIVM (membrete, barra de título y pie de página).
 * Usa FPDF (incluida en app/Libraries/fpdf). No depende de Laravel para poder probarse sola.
 */
class DivmPdf extends \FPDF
{
    private string $titulo;
    private ?string $logo;

    public function __construct(string $titulo, ?string $logo = null)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->titulo = $titulo;
        $this->logo   = ($logo && is_file($logo)) ? $logo : null;
        $this->SetTitle(self::txt($titulo));
        $this->SetAuthor('DIVM Educación Especial');
        $this->AliasNbPages();
        $this->SetAutoPageBreak(true, 20);
        $this->SetMargins(15, 15, 15);
    }

    /** Convierte UTF-8 a la codificación que entiende FPDF (acentos y ñ). */
    public static function txt($texto): string
    {
        $texto = (string) ($texto ?? '');
        $r = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $texto);
        return $r === false ? $texto : $r;
    }

    public function Header(): void
    {
        if ($this->logo) {
            try {
                $this->Image($this->logo, 15, 8, 26);
            } catch (\Throwable $e) {
                // Si el PHP no tiene la extensión GD (PNG con transparencia), el PDF sale sin logo en vez de fallar
                $this->logo = null;
            }
        }
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(84, 6, 62);
        $this->SetY(13);
        $this->Cell(0, 8, self::txt('CENTRO DIVM'), 0, 1, 'C');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 5, self::txt('Educación Especial'), 0, 1, 'C');

        $this->SetFillColor(197, 0, 90);
        $this->Rect(0, 38, 210, 10, 'F');
        $this->SetY(38);
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 10, self::txt($this->titulo), 0, 1, 'C');
        $this->SetY(54);
    }

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, self::txt('Página ' . $this->PageNo() . ' de ') . '{nb}' . self::txt('  ·  Documento generado el ' . date('d/m/Y H:i')), 0, 0, 'C');
    }

    /** Barra gris con el nombre de la sección. */
    public function seccion(string $label): void
    {
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(245, 245, 245);
        $this->SetTextColor(197, 0, 90);
        $this->Cell(0, 7, self::txt('  ' . $label), 0, 1, 'L', true);
        $this->Ln(2);
    }

    /** Fila "ETIQUETA: valor". */
    public function fila(string $label, $valor): void
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(55, 6, self::txt($label), 0, 0);
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 6, self::txt($this->limpio($valor)), 0, 'L');
    }

    /** Título pequeño y debajo un párrafo largo. */
    public function bloque(string $titulo, $contenido): void
    {
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(0, 6, self::txt($titulo), 0, 1);
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(30, 30, 30);
        $this->MultiCell(0, 5, self::txt($this->limpio($contenido)), 0, 'L');
        $this->Ln(3);
    }

    private function limpio($v): string
    {
        $v = trim((string) ($v ?? ''));
        return $v === '' ? '—' : $v;
    }
}
