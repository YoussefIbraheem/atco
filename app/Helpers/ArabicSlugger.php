<?php

namespace App\Helpers;

final class ArabicSlugger
{
    public static function handle(string $value): string
    {
        return self::slug(self::normalize($value));
    }

    private static function normalize(string $value): string
    {
        $value = strtr($value, [
            'أ' => 'ا',
            'إ' => 'ا',
            'آ' => 'ا',
            'ٱ' => 'ا',
            'ة' => 'ه',
            'ى' => 'ي',
        ]);

        $value = preg_replace('/\s+/u', ' ', $value);

        return trim($value);
    }

    private static function slug(string $value): string
    {
        $value = preg_replace(
            '/[^\p{Arabic}\p{Latin}\p{N}]+/u',
            '-',
            $value
        );

        return trim($value, '-');
    }
}
