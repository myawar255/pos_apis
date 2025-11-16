<?php
if (!function_exists('activeSegment')) {
    function activeSegment($name, $segment = 2, $class = 'active')
    {
        return request()->segment($segment) == $name ? $class : '';
    }
}

if (!function_exists('code39_svg')) {
    /**
     * Generate a simple Code 39 barcode SVG.
     *
     * @param  string  $text
     * @param  int  $height
     * @param  int  $moduleWidth
     * @return string
     */
    function code39_svg($text, $height = 60, $moduleWidth = 2)
    {
        $alphabet = [
            '0' => 'nnnwwnwnn',
            '1' => 'wnnwnnnnw',
            '2' => 'nnwwnnnnw',
            '3' => 'wnwwnnnnn',
            '4' => 'nnnwwnnnw',
            '5' => 'wnnwwnnnn',
            '6' => 'nnwwwnnnn',
            '7' => 'nnnwnnwnw',
            '8' => 'wnnwnnwnn',
            '9' => 'nnwwnnwnn',
            'A' => 'wnnnnwnnw',
            'B' => 'nnwnnwnnw',
            'C' => 'wnwnnwnnn',
            'D' => 'nnnnwwnnw',
            'E' => 'wnnnwwnnn',
            'F' => 'nnwnwwnnn',
            'G' => 'nnnnnwwnw',
            'H' => 'wnnnnwwnn',
            'I' => 'nnwnnwwnn',
            'J' => 'nnnnwwwnn',
            'K' => 'wnnnnnnww',
            'L' => 'nnwnnnnww',
            'M' => 'wnwnnnnwn',
            'N' => 'nnnnwnnww',
            'O' => 'wnnnwnnwn',
            'P' => 'nnwnwnnwn',
            'Q' => 'nnnnnnwww',
            'R' => 'wnnnnnwwn',
            'S' => 'nnwnnnwwn',
            'T' => 'nnnnwnwwn',
            'U' => 'wwnnnnnnw',
            'V' => 'nwwnnnnnw',
            'W' => 'wwwnnnnnn',
            'X' => 'nwnnwnnnw',
            'Y' => 'wwnnwnnnn',
            'Z' => 'nwwnwnnnn',
            '-' => 'nwnnnnwnw',
            '.' => 'wwnnnnwnn',
            ' ' => 'nwwnnnwnn',
            '$' => 'nwnwnwnnn',
            '/' => 'nwnwnnnwn',
            '+' => 'nwnnnwnwn',
            '%' => 'nnnwnwnwn',
            '*' => 'nwnnwnwnn',
        ];

        $ratio = 3; // wide bar width multiplier
        $allowed = array_keys($alphabet);
        $allowed[] = '*';
        $text = strtoupper($text);
        $encoded = '*' . $text . '*';

        $elements = str_split($encoded);
        foreach ($elements as $char) {
            if (!array_key_exists($char, $alphabet)) {
                return '';
            }
        }

        $bars = [];
        $position = 0;
        $narrow = $moduleWidth;
        $wide = $moduleWidth * $ratio;
        $barHeight = $height;

        foreach ($elements as $index => $char) {
            $pattern = $alphabet[$char];
            $isBar = true;
            foreach (str_split($pattern) as $token) {
                $width = $token === 'w' ? $wide : $narrow;
                if ($isBar) {
                    $bars[] = [
                        'x' => $position,
                        'width' => $width,
                    ];
                }
                $position += $width;
                $isBar = !$isBar;
            }
            // Add inter-character gap except after final character
            if ($index < count($elements) - 1) {
                $position += $narrow;
            }
        }

        $totalWidth = $position;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $totalWidth . '" height="' . $barHeight . '" viewBox="0 0 ' . $totalWidth . ' ' . $barHeight . '">';
        foreach ($bars as $bar) {
            $svg .= '<rect x="' . $bar['x'] . '" y="0" width="' . $bar['width'] . '" height="' . $barHeight . '" fill="#000" />';
        }
        $svg .= '</svg>';

        return $svg;
    }
}
