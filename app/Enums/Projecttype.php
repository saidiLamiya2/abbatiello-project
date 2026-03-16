<?php

namespace App\Enums;

enum ProjectType: string
{
    case Nouveau = 'Nouveau';
    case Corpo   = 'Corpo';
    case Reprise = 'Reprise';
    case Vente   = 'Vente';

    public function label(): string
    {
        return match ($this) {
            self::Nouveau => __('app.enums.project_type.nouveau'),
            self::Corpo   => __('app.enums.project_type.corpo'),
            self::Reprise => __('app.enums.project_type.reprise'),
            self::Vente   => __('app.enums.project_type.vente'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Nouveau => 'success',
            self::Corpo   => 'info',
            self::Reprise => 'warning',
            self::Vente   => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}