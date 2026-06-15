<?php
namespace App\Helpers;

/**
 * ExcelExport — generate file Excel tanpa library dan tanpa ZipArchive.
 * Menggunakan format SpreadsheetML (XML Excel 2003) yang didukung
 * Excel 2007+, LibreOffice, dan Google Sheets.
 * Extension PHP yang dibutuhkan: TIDAK ADA (pure PHP string).
 */
class ExcelExport
{
    private array $sheets = [];
    private string $title = 'Export';

    public function __construct(string $title = 'Export')
    {
        $this->title = $title;
    }

    /**
     * Tambah sheet.
     * $headers    = ['Kolom A', 'Kolom B', ...]
     * $data       = array of rows (array of values)
     * $colFormats = ['B' => 'number'] untuk kolom angka (opsional, auto-detect)
     */
    public function addSheet(string $name, array $headers, array $data, array $colFormats = []): static
    {
        $this->sheets[] = compact('name', 'headers', 'data', 'colFormats');
        return $this;
    }

    /** Stream file ke browser sebagai download .xlsx */
    public function download(string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $content = $this->buildXml();

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
            'Pragma'              => 'public',
        ]);
    }

    private function buildXml(): string
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
              . ' xmlns:o="urn:schemas-microsoft-com:office:office"'
              . ' xmlns:x="urn:schemas-microsoft-com:office:excel"'
              . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
              . ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

        // Styles
        $xml .= '<Styles>'
              . '<Style ss:ID="Default" ss:Name="Normal">'
              .   '<Alignment ss:Vertical="Center"/>'
              .   '<Font ss:FontName="Calibri" ss:Size="11"/>'
              . '</Style>'
              . '<Style ss:ID="Header">'
              .   '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>'
              .   '<Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>'
              .   '<Interior ss:Color="#0F766E" ss:Pattern="Solid"/>'
              .   '<Borders>'
              .     '<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CCCCCC"/>'
              .   '</Borders>'
              . '</Style>'
              . '<Style ss:ID="NumberFmt">'
              .   '<Alignment ss:Vertical="Center"/>'
              .   '<Font ss:FontName="Calibri" ss:Size="11"/>'
              .   '<NumberFormat ss:Format="#,##0"/>'
              . '</Style>'
              . '<Style ss:ID="DateFmt">'
              .   '<Alignment ss:Vertical="Center"/>'
              .   '<Font ss:FontName="Calibri" ss:Size="11"/>'
              .   '<NumberFormat ss:Format="DD/MM/YYYY"/>'
              . '</Style>'
              . '<Style ss:ID="RowEven">'
              .   '<Alignment ss:Vertical="Center"/>'
              .   '<Font ss:FontName="Calibri" ss:Size="11"/>'
              .   '<Interior ss:Color="#F0FAF9" ss:Pattern="Solid"/>'
              . '</Style>'
              . '<Style ss:ID="RowEvenNum">'
              .   '<Alignment ss:Vertical="Center"/>'
              .   '<Font ss:FontName="Calibri" ss:Size="11"/>'
              .   '<Interior ss:Color="#F0FAF9" ss:Pattern="Solid"/>'
              .   '<NumberFormat ss:Format="#,##0"/>'
              . '</Style>'
              . '</Styles>' . "\n";

        foreach ($this->sheets as $sheet) {
            $safeName = htmlspecialchars(substr($sheet['name'], 0, 31));
            $xml .= '<Worksheet ss:Name="' . $safeName . '">' . "\n";
            $xml .= '<Table>' . "\n";

            // Auto-column width berdasarkan header
            foreach ($sheet['headers'] as $i => $h) {
                $w = max(mb_strlen((string)$h) * 7, 80);
                $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
            }

            // Header row
            $xml .= '<Row ss:Height="22">' . "\n";
            foreach ($sheet['headers'] as $h) {
                $xml .= '<Cell ss:StyleID="Header">'
                      . '<Data ss:Type="String">' . htmlspecialchars((string)$h) . '</Data>'
                      . '</Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";

            // Data rows
            foreach ($sheet['data'] as $ri => $row) {
                $even   = ($ri % 2 === 0);
                $rowStyle = $even ? 'RowEven' : 'Default';
                $numStyle = $even ? 'RowEvenNum' : 'NumberFmt';

                $xml .= '<Row>' . "\n";
                foreach (array_values($row) as $ci => $val) {
                    $colKey = $this->colLetter($ci);
                    $fmt    = $sheet['colFormats'][$colKey] ?? null;

                    if ($val === null || $val === '') {
                        $xml .= '<Cell ss:StyleID="' . $rowStyle . '">'
                              . '<Data ss:Type="String"></Data></Cell>' . "\n";
                    } elseif ($fmt === 'number' || (is_numeric($val) && !is_string($val) && $ci > 0)) {
                        $xml .= '<Cell ss:StyleID="' . $numStyle . '">'
                              . '<Data ss:Type="Number">' . (float)$val . '</Data></Cell>' . "\n";
                    } else {
                        $xml .= '<Cell ss:StyleID="' . $rowStyle . '">'
                              . '<Data ss:Type="String">' . htmlspecialchars((string)$val) . '</Data></Cell>' . "\n";
                    }
                }
                $xml .= '</Row>' . "\n";
            }

            $xml .= '</Table>' . "\n";

            // Freeze top row
            $xml .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">'
                  . '<FreezePanes/><FrozenNoSplit/>'
                  . '<SplitHorizontal>1</SplitHorizontal>'
                  . '<TopRowBottomPane>1</TopRowBottomPane>'
                  . '<ActivePane>2</ActivePane>'
                  . '</WorksheetOptions>' . "\n";

            $xml .= '</Worksheet>' . "\n";
        }

        $xml .= '</Workbook>';
        return $xml;
    }

    private function colLetter(int $index): string
    {
        $letter = '';
        $index++;
        while ($index > 0) {
            $letter = chr(65 + (($index - 1) % 26)) . $letter;
            $index  = (int)(($index - 1) / 26);
        }
        return $letter;
    }
}
