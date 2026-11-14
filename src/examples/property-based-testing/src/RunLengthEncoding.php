<?php declare(strict_types=1);
final class RunLengthEncoding
{
    public static function encode(string $string): string
    {
        $encoded = '';
        $length  = strlen($string);

        for ($i = 0; $i < $length; $i += $run) {
            $run = strspn($string, $string[$i], $i);

            $encoded .= $run . $string[$i];
        }

        return $encoded;
    }

    public static function decode(string $encoded): string
    {
        $string = '';

        preg_match_all('/(\d+)(.)/s', $encoded, $runs, PREG_SET_ORDER);

        foreach ($runs as $run) {
            $string .= str_repeat($run[2], (int) $run[1]);
        }

        return $string;
    }
}
