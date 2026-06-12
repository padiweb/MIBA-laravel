<?php
namespace App\Helpers;

class Barcode {

    // Tabel konversi Code 39 (standar ANSI MH10.8M-1983 / USD-3 / 3-of-9)
    // Setiap karakter = 9 elemen (bar-space berselang, mulai dari bar), 1=narrow, 3=wide
    protected static array $table = [
        '0' => '111331311', '1' => '311311113', '2' => '113311113', '3' => '313311111',
        '4' => '111331113', '5' => '311331111', '6' => '113331111', '7' => '111311313',
        '8' => '311311311', '9' => '113311311', 'A' => '311113113', 'B' => '113113113',
        'C' => '313113111', 'D' => '111133113', 'E' => '311133111', 'F' => '113133111',
        'G' => '111113313', 'H' => '311113311', 'I' => '113113311', 'J' => '111133311',
        'K' => '311111133', 'L' => '113111133', 'M' => '313111131', 'N' => '111131133',
        'O' => '311131131', 'P' => '113131131', 'Q' => '111111333', 'R' => '311111331',
        'S' => '113111331', 'T' => '111131331', 'U' => '331111113', 'V' => '133111113',
        'W' => '333111111', 'X' => '131131113', 'Y' => '331131111', 'Z' => '133131111',
        '-' => '131111313', '.' => '331111311', ' ' => '133111311', '$' => '131313111',
        '/' => '131311131', '+' => '131113131', '%' => '111313131', '*' => '131131311',
    ];

    /**
     * Generate SVG Code39 barcode.
     *
     * @param string $code     Teks yang akan di-encode (akan diubah ke huruf besar)
     * @param int    $height   Tinggi barcode (px)
     * @param int    $narrow   Lebar bar tersempit (px)
     * @param bool   $showText Tampilkan teks di bawah barcode
     */
    public static function code39Svg(string $code, int $height = 50, int $narrow = 2, bool $showText = true): string {
        $code = strtoupper($code);
        $full = '*' . $code . '*';

        $x = 0;
        $bars = '';
        for ($i = 0; $i < strlen($full); $i++) {
            $char = $full[$i];
            $pattern = self::$table[$char] ?? self::$table['*'];
            for ($j = 0; $j < 9; $j++) {
                $width = ((int)$pattern[$j]) * $narrow;
                $isBar = ($j % 2 === 0); // index genap = bar (hitam), ganjil = spasi (putih)
                if ($isBar) {
                    $bars .= '<rect x="'.$x.'" y="0" width="'.$width.'" height="'.$height.'" fill="#000"/>';
                }
                $x += $width;
            }
        }
        $totalWidth = $x;
        $svgHeight = $showText ? $height + 14 : $height;

        $textEl = $showText
            ? '<text x="'.($totalWidth/2).'" y="'.($height+12).'" font-size="11" font-family="monospace" text-anchor="middle">'.htmlspecialchars($code).'</text>'
            : '';

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$totalWidth.' '.$svgHeight.'" width="'.$totalWidth.'" height="'.$svgHeight.'">'
             . '<rect x="0" y="0" width="'.$totalWidth.'" height="'.$svgHeight.'" fill="#fff"/>'
             . $bars . $textEl
             . '</svg>';
    }
}
