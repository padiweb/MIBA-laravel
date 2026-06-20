<?php
namespace App\Helpers;

/**
 * ExcelExport — generate file .xlsx ASLI (OpenXML / OOXML) tanpa dependency
 * eksternal (tidak butuh PhpSpreadsheet/Maatwebsite).
 *
 * Strategi:
 *  - Jika extension ZipArchive tersedia → pakai itu (paling cepat & reliable).
 *  - Jika TIDAK tersedia → fallback ke implementasi ZIP murni PHP (metode
 *    STORE / tanpa kompresi) sehingga tetap menghasilkan .xlsx valid tanpa
 *    butuh extension apapun.
 *
 * Hasil akhir selalu file .xlsx asli yang dikenali Excel/LibreOffice/Google
 * Sheets sebagai spreadsheet native — bukan CSV menyamar.
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
     * $colFormats = ['B' => 'number'] kolom mana yang dipaksa format angka
     */
    public function addSheet(string $name, array $headers, array $data, array $colFormats = []): static
    {
        $this->sheets[] = compact('name', 'headers', 'data', 'colFormats');
        return $this;
    }

    public function download(string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = preg_replace('/\.(xlsx|xls|csv)$/i', '', $filename) . '.xlsx';
        $bytes    = $this->buildXlsx();

        return response()->streamDownload(function () use ($bytes) {
            echo $bytes;
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length'      => strlen($bytes),
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'public',
            'Expires'             => '0',
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // BUILD XLSX (OOXML) CONTENT
    // ════════════════════════════════════════════════════════════

    private function buildXlsx(): string
    {
        $sharedStrings = [];
        $strIndex      = [];

        $getStrId = function (string $val) use (&$sharedStrings, &$strIndex): int {
            if (!isset($strIndex[$val])) {
                $strIndex[$val]   = count($sharedStrings);
                $sharedStrings[]  = $val;
            }
            return $strIndex[$val];
        };

        $sheetFiles = [];
        $sheetRels  = [];

        foreach ($this->sheets as $si => $sheet) {
            $rowsXml   = '';
            $rowNum    = 1;
            $colWidths = [];

            // ── Header row (bold via style index 1) ──
            $cells = '';
            foreach (array_values($sheet['headers']) as $ci => $h) {
                $col   = $this->colLetter($ci);
                $sid   = $getStrId($this->cleanText((string)$h));
                $cells .= '<c r="' . $col . $rowNum . '" t="s" s="1"><v>' . $sid . '</v></c>';
                $colWidths[$ci] = max($colWidths[$ci] ?? 8, mb_strlen((string)$h) + 3);
            }
            $rowsXml .= '<row r="' . $rowNum . '" ht="20" customHeight="1">' . $cells . '</row>';
            $rowNum++;

            // ── Data rows ──
            foreach ($sheet['data'] as $ri => $row) {
                $cells   = '';
                $bandStyle = ($ri % 2 === 0) ? 3 : 0; // 3 = striped background style
                foreach (array_values($row) as $ci => $val) {
                    $col    = $this->colLetter($ci);
                    $colKey = $col;
                    $ref    = $col . $rowNum;
                    $fmtForced = $sheet['colFormats'][$colKey] ?? null;

                    // Default: HANYA nilai numerik asli (int/float dari PHP, bukan string)
                    // yang dianggap angka. String digit (NIS, NISN, dll) TETAP teks,
                    // kecuali kolom tersebut eksplisit diberi colFormats => 'number'.
                    $isNumeric = ($fmtForced === 'number' && $val !== null && $val !== '' && is_numeric($val))
                        || ($fmtForced === null && ($val !== null && $val !== '') && (is_int($val) || is_float($val)));

                    if ($fmtForced === 'text') {
                        $isNumeric = false;
                    }

                    if ($val === null || $val === '') {
                        $cells .= '<c r="' . $ref . '" s="' . $bandStyle . '"/>';
                    } elseif ($isNumeric) {
                        $numStyle = $bandStyle === 3 ? 4 : 2; // style angka (striped / normal)
                        $cells .= '<c r="' . $ref . '" s="' . $numStyle . '"><v>' . (float)$val . '</v></c>';
                    } else {
                        $sid = $getStrId($this->cleanText((string)$val));
                        $cells .= '<c r="' . $ref . '" t="s" s="' . $bandStyle . '"><v>' . $sid . '</v></c>';
                    }

                    $len = mb_strlen((string)$val);
                    $colWidths[$ci] = max($colWidths[$ci] ?? 8, min($len + 3, 45));
                }
                $rowsXml .= '<row r="' . $rowNum . '">' . $cells . '</row>';
                $rowNum++;
            }

            // ── Column width definitions ──
            $colsXml = '';
            foreach ($colWidths as $ci => $w) {
                $colsXml .= '<col min="' . ($ci + 1) . '" max="' . ($ci + 1) . '" width="' . $w . '" customWidth="1"/>';
            }

            $totalCols = count($sheet['headers']);
            $totalRows = $rowNum - 1;
            $dimEnd    = $this->colLetter(max($totalCols - 1, 0)) . max($totalRows, 1);

            $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
                . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
                . '<dimension ref="A1:' . $dimEnd . '"/>'
                . '<sheetViews><sheetView workbookViewId="0"' . ($si === 0 ? ' tabSelected="1"' : '') . '>'
                . '<pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
                . '<selection pane="bottomLeft" activeCell="A2" sqref="A2"/>'
                . '</sheetView></sheetViews>'
                . '<cols>' . $colsXml . '</cols>'
                . '<sheetData>' . $rowsXml . '</sheetData>'
                . '<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>'
                . '</worksheet>';

            $sheetFiles[] = $sheetXml;

            $rawName  = $sheet['name'] !== '' ? $sheet['name'] : ('Sheet' . ($si + 1));
            $safeName = $this->sanitizeSheetName($rawName);
            $sheetRels[] = ['name' => $safeName, 'rid' => 'rId' . ($si + 1)];
        }

        // ── sharedStrings.xml ──
        $ssXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">';
        foreach ($sharedStrings as $s) {
            $ssXml .= '<si><t xml:space="preserve">' . $this->escapeXml($s) . '</t></si>';
        }
        $ssXml .= '</sst>';

        // ── styles.xml ──
        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="#,##0"/></numFmts>'
            . '<fonts count="3">'
            .   '<font><sz val="10"/><name val="Calibri"/></font>'
            .   '<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .   '<font><sz val="10"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="4">'
            .   '<fill><patternFill patternType="none"/></fill>'
            .   '<fill><patternFill patternType="gray125"/></fill>'
            .   '<fill><patternFill patternType="solid"><fgColor rgb="FF0F766E"/><bgColor indexed="64"/></patternFill></fill>'
            .   '<fill><patternFill patternType="solid"><fgColor rgb="FFF0FAF9"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="5">'
            .   '<xf numFmtId="0"   fontId="0" fillId="0" borderId="0" xfId="0"/>'                                  // 0 normal
            .   '<xf numFmtId="0"   fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1">'        // 1 header
            .     '<alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            .   '<xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'             // 2 number
            .   '<xf numFmtId="0"   fontId="0" fillId="3" borderId="0" xfId="0" applyFill="1"/>'                     // 3 striped (fill index 3 = light green)
            .   '<xf numFmtId="164" fontId="0" fillId="3" borderId="0" xfId="0" applyNumberFormat="1" applyFill="1"/>' // 4 striped number
            . '</cellXfs>'
            . '</styleSheet>';

        // ── workbook.xml ──
        $sheetsTag = '';
        foreach ($sheetRels as $i => $sr) {
            $sheetsTag .= '<sheet name="' . $this->escapeXml($sr['name']) . '" sheetId="' . ($i + 1) . '" r:id="' . $sr['rid'] . '"/>';
        }
        $wbXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<fileVersion appName="xl"/>'
            . '<workbookPr/>'
            . '<sheets>' . $sheetsTag . '</sheets>'
            . '</workbook>';

        // ── workbook.xml.rels ──
        $extraRidStr   = 'rId' . (count($this->sheets) + 1);
        $extraRidStyle = 'rId' . (count($this->sheets) + 2);
        $wbRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        foreach ($sheetRels as $i => $sr) {
            $wbRelsXml .= '<Relationship Id="' . $sr['rid'] . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . ($i + 1) . '.xml"/>';
        }
        $wbRelsXml .= '<Relationship Id="' . $extraRidStr . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            . '<Relationship Id="' . $extraRidStyle . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        // ── [Content_Types].xml ──
        $ctXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>';
        foreach ($sheetRels as $i => $sr) {
            $ctXml .= '<Override PartName="/xl/worksheets/sheet' . ($i + 1) . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }
        $ctXml .= '</Types>';

        // ── _rels/.rels ──
        $rootRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        // ── docProps/core.xml & app.xml (opsional tapi membantu kompatibilitas) ──
        $coreXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '<dc:title>' . $this->escapeXml($this->title) . '</dc:title>'
            . '<dcterms:created xsi:type="dcterms:W3CDTF">' . date('Y-m-d\TH:i:s\Z') . '</dcterms:created>'
            . '</cp:coreProperties>';

        $appXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\r\n"
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            . '<Application>MIBA</Application>'
            . '</Properties>';

        // ── Assemble files map ──
        $files = [
            '[Content_Types].xml'      => $ctXml,
            '_rels/.rels'              => $rootRelsXml,
            'docProps/core.xml'        => $coreXml,
            'docProps/app.xml'         => $appXml,
            'xl/workbook.xml'          => $wbXml,
            'xl/_rels/workbook.xml.rels' => $wbRelsXml,
            'xl/styles.xml'            => $stylesXml,
            'xl/sharedStrings.xml'     => $ssXml,
        ];
        foreach ($sheetFiles as $i => $xml) {
            $files['xl/worksheets/sheet' . ($i + 1) . '.xml'] = $xml;
        }

        return $this->zip($files);
    }

    // ════════════════════════════════════════════════════════════
    // ZIP BUILDER — pakai ZipArchive jika ada, fallback manual jika tidak
    // ════════════════════════════════════════════════════════════

    private function zip(array $files): string
    {
        if (class_exists('ZipArchive')) {
            return $this->zipViaZipArchive($files);
        }
        return $this->zipManual($files);
    }

    private function zipViaZipArchive(array $files): string
    {
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::OVERWRITE);
        foreach ($files as $path => $content) {
            $zip->addFromString($path, $content);
        }
        $zip->close();
        $bytes = file_get_contents($tmp);
        @unlink($tmp);
        return $bytes;
    }

    /**
     * Implementasi ZIP murni PHP (metode STORE, tanpa kompresi) — tidak
     * membutuhkan extension apapun. File tetap valid karena format ZIP
     * tidak mewajibkan kompresi, hanya struktur header yang benar.
     */
    private function zipManual(array $files): string
    {
        $localParts   = '';
        $centralParts = '';
        $offset       = 0;

        foreach ($files as $path => $content) {
            $crc       = crc32($content);
            $size      = strlen($content);
            $nameBytes = $path;
            $nameLen   = strlen($nameBytes);

            // Local file header (method 0 = STORE / no compression)
            $localHeader = "PK\x03\x04"
                . pack('v', 20)        // version needed
                . pack('v', 0)         // flags
                . pack('v', 0)         // compression method = STORE
                . pack('v', 0)         // mod time
                . pack('v', 0)         // mod date
                . pack('V', $crc)
                . pack('V', $size)     // compressed size
                . pack('V', $size)     // uncompressed size
                . pack('v', $nameLen)
                . pack('v', 0)         // extra field length
                . $nameBytes;

            $localParts .= $localHeader . $content;

            // Central directory header
            $centralParts .= "PK\x01\x02"
                . pack('v', 20)        // version made by
                . pack('v', 20)        // version needed
                . pack('v', 0)         // flags
                . pack('v', 0)         // compression method
                . pack('v', 0)         // mod time
                . pack('v', 0)         // mod date
                . pack('V', $crc)
                . pack('V', $size)
                . pack('V', $size)
                . pack('v', $nameLen)
                . pack('v', 0)         // extra length
                . pack('v', 0)         // comment length
                . pack('v', 0)         // disk number start
                . pack('v', 0)         // internal attrs
                . pack('V', 0)         // external attrs
                . pack('V', $offset)   // offset of local header
                . $nameBytes;

            $offset += strlen($localHeader) + $size;
        }

        $centralStart = $offset;
        $centralSize  = strlen($centralParts);
        $count        = count($files);

        $endRecord = "PK\x05\x06"
            . pack('v', 0)            // disk number
            . pack('v', 0)            // disk with central dir
            . pack('v', $count)       // entries this disk
            . pack('v', $count)       // entries total
            . pack('V', $centralSize)
            . pack('V', $centralStart)
            . pack('v', 0);           // comment length

        return $localParts . $centralParts . $endRecord;
    }

    // ════════════════════════════════════════════════════════════
    // HELPERS
    // ════════════════════════════════════════════════════════════

    private function cleanText(string $str): string
    {
        if (!mb_check_encoding($str, 'UTF-8')) {
            $detected = mb_detect_encoding($str, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
            $str = $detected ? @mb_convert_encoding($str, 'UTF-8', $detected) : @utf8_encode($str);
            if ($str === false) $str = '';
        }
        // Buang karakter kontrol ilegal XML (selain tab/newline/CR)
        $str = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $str);
        return $str;
    }

    private function escapeXml(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function sanitizeSheetName(string $name): string
    {
        // Sheet name Excel: max 31 char, tidak boleh : \ / ? * [ ]
        $name = preg_replace('/[:\\\\\/\?\*\[\]]/', '-', $name);
        $name = mb_substr(trim($name), 0, 31);
        return $name !== '' ? $name : 'Sheet1';
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
