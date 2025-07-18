<?php

declare(strict_types=1);

namespace App\Helpers;

final class Initials
{
    /**
     * Generate initials from a name
     */
    public static function generate(string $name): string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(
                mb_substr($words[0], 0, 1, 'UTF-8').
                mb_substr(end($words), 0, 1, 'UTF-8'),
                'UTF-8');
        }

        return self::makeInitialsFromSingleWord($name);
    }

    /**
     * Make initials from a word with no spaces
     */
    protected static function makeInitialsFromSingleWord(string $name): string
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return mb_substr(implode('', $capitals[1]), 0, 2, 'UTF-8');
        }

        return mb_strtoupper(mb_substr($name, 0, 2, 'UTF-8'), 'UTF-8');
    }
}
