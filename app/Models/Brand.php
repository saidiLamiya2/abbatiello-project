<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'theme_id',
        'name',
        'tag',
        'logo',
        'favicon',
        'design_config',
        'links',
        'sms_phone_number',
        'email_from_address',
        'email_from_name',
    ];

    protected function casts(): array
    {
        return [
            'design_config' => 'array',
            'links'         => 'array',
        ];
    }

    // ─── Mutators ────────────────────────────────────────────────────────────

    public function setTagAttribute(string $value): void
    {
        $this->attributes['tag'] = strtoupper(trim($value));
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}