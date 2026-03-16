<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin      = 'admin';
    case Manager    = 'manager';
    case Employee   = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => __('app.enums.role.super_admin'),
            self::Admin      => __('app.enums.role.admin'),
            self::Manager    => __('app.enums.role.manager'),
            self::Employee   => __('app.enums.role.employee'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SuperAdmin => 'danger',
            self::Admin      => 'warning',
            self::Manager    => 'info',
            self::Employee   => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function optionsExcluding(self ...$excluded): array
    {
        $excludedValues = array_map(fn (self $case) => $case->value, $excluded);

        return collect(self::cases())
            ->filter(fn (self $case) => ! in_array($case->value, $excludedValues))
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}