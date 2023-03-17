<?php

declare(strict_types=1);

namespace Kishlin\Backend\Tools\Helpers;

final class StringHelper
{
    public static function slugify(string ...$strings): string
    {
        return implode(
            '_',
            array_map(
                static function (string $str) {
                    // replace non letter or digits by divider, but allows _
                    $str = preg_replace('~[^\pL\d_]+~u', '-', $str);
                    assert(is_string($str));

                    // transliterate
                    $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
                    assert(is_string($str));

                    // remove unwanted characters
                    $str = preg_replace('~[^-\w]+~', '', $str);
                    assert(is_string($str));

                    // trim
                    $str = trim($str, '-');

                    // remove duplicate divider
                    $str = preg_replace('~-+~', '-', $str);
                    assert(is_string($str));

                    // lowercase
                    $str = strtolower($str);

                    if (empty($str)) {
                        return 'n-a';
                    }

                    return $str;
                },
                $strings,
            ),
        );
    }
}
