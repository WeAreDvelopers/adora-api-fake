<?php

namespace App\Helpers;

class NumberToWords
{
    private static array $units = [
        '', 'um', 'dois', 'três', 'quatro', 'cinco',
        'seis', 'sete', 'oito', 'nove',
    ];

    private static array $teens = [
        'dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze',
        'dezesseis', 'dezessete', 'dezoito', 'dezenove',
    ];

    private static array $tens = [
        '', '', 'vinte', 'trinta', 'quarenta', 'cinquenta',
        'sessenta', 'setenta', 'oitenta', 'noventa',
    ];

    private static array $hundreds = [
        '', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos',
        'seiscentos', 'setecentos', 'oitocentos', 'novecentos',
    ];

    /**
     * Converte um valor monetário em reais para extenso.
     * Ex: 1234.56 → "mil duzentos e trinta e quatro reais e cinquenta e seis centavos"
     */
    public static function brl(float $value): string
    {
        if ($value == 0) {
            return 'zero reais';
        }

        $inteiro = (int) floor(abs($value));
        $centavos = (int) round((abs($value) - $inteiro) * 100);

        $parts = [];

        if ($inteiro > 0) {
            $inteiroExtenso = self::intToWords($inteiro);
            $parts[] = $inteiroExtenso . ' ' . ($inteiro === 1 ? 'real' : 'reais');
        }

        if ($centavos > 0) {
            $centavosExtenso = self::intToWords($centavos);
            $parts[] = $centavosExtenso . ' ' . ($centavos === 1 ? 'centavo' : 'centavos');
        }

        return implode(' e ', $parts);
    }

    private static function intToWords(int $number): string
    {
        if ($number === 0) {
            return 'zero';
        }

        if ($number === 100) {
            return 'cem';
        }

        $parts = [];

        if ($number >= 1000000) {
            $milhoes = (int) floor($number / 1000000);
            $parts[] = self::intToWords($milhoes) . ($milhoes === 1 ? ' milhão' : ' milhões');
            $number %= 1000000;
        }

        if ($number >= 1000) {
            $milhares = (int) floor($number / 1000);
            if ($milhares === 1) {
                $parts[] = 'mil';
            } else {
                $parts[] = self::intToWords($milhares) . ' mil';
            }
            $number %= 1000;
        }

        if ($number >= 100) {
            if ($number === 100) {
                $parts[] = 'cem';
                $number = 0;
            } else {
                $centena = (int) floor($number / 100);
                $parts[] = self::$hundreds[$centena];
                $number %= 100;
            }
        }

        if ($number >= 10 && $number <= 19) {
            $parts[] = self::$teens[$number - 10];
            $number = 0;
        } elseif ($number >= 20) {
            $dezena = (int) floor($number / 10);
            $unidade = $number % 10;
            if ($unidade > 0) {
                $parts[] = self::$tens[$dezena] . ' e ' . self::$units[$unidade];
            } else {
                $parts[] = self::$tens[$dezena];
            }
            $number = 0;
        } elseif ($number > 0) {
            $parts[] = self::$units[$number];
        }

        return implode(' e ', $parts);
    }
}
