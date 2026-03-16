<?php

namespace App\Enums;

enum UserLocale: string
{
    case French  = 'fr';
    case English = 'en';

    public function label(): string
    {
        return match ($this) {
            self::French  => 'Français',
            self::English => 'English',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}