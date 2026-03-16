<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theme extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'font_family',
        'filament_color',
    ];

    // ─── Relations ───────────────────────────────────────────────────────────

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}