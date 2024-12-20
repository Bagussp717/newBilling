<?php

if (!function_exists('terbilang_rupiah')) {
    function terbilang_rupiah($number)
    {
        $units = ['', 'ribu', 'juta', 'miliar', 'triliun'];
        $words = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan'];
        
        if ($number < 0) {
            return 'minus ' . terbilang_rupiah(abs($number));
        }

        $result = '';
        $i = 0;

        while ($number != 0) {
            $part = $number % 1000;

            if ($part != 0) {
                $hundreds = (int) ($part / 100);
                $tens = $part % 100;
                $text = '';

                if ($hundreds > 0) {
                    $text .= ($hundreds == 1 ? 'seratus' : $words[$hundreds] . ' ratus') . ' ';
                }

                if ($tens > 0) {
                    if ($tens < 10) {
                        $text .= $words[$tens] . ' ';
                    } elseif ($tens < 20) {
                        $text .= ($tens == 10 ? 'sepuluh' : ($tens == 11 ? 'sebelas' : $words[$tens - 10] . ' belas')) . ' ';
                    } else {
                        $text .= $words[(int) ($tens / 10)] . ' puluh ';
                        if ($tens % 10 > 0) {
                            $text .= $words[$tens % 10] . ' ';
                        }
                    }
                }

                $result = $text . $units[$i] . ' ' . $result;
            }

            $number = (int) ($number / 1000);
            $i++;
        }

        return trim($result) . ' rupiah';
    }
}
