<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;


    protected $fillable = [
        'brand_id',
        'store_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'user_code',
        'is_active',
        'hired_at',
        'terminated_at',
        'termination_reason',
        'is_work_stoppage',
        'work_stoppage_start_date',
        'work_stoppage_end_date',
        'birth_date',
        'locale',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'                 => 'hashed',
            'email_verified_at'        => 'datetime',
            'is_active'                => 'boolean',
            'is_work_stoppage'         => 'boolean',
            'hired_at'                 => 'date',
            'terminated_at'            => 'date',
            'work_stoppage_start_date' => 'date',
            'work_stoppage_end_date'   => 'date',
            'birth_date'               => 'date',
        ];
    }

    // ─── FilamentUser ────────────────────────────────────────────────────────

    /**
     * Controls who can access the Filament panel.
     * is_active must be true — terminated or inactive users are blocked.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    // ─── Accessors ───────────────────────────────────────────────────────────

    /**
     * Virtual full name — combines first + last.
     * Used wherever the app expects a single $user->name.
     */
    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /** Whether this user is currently terminated */
    public function getIsTerminatedAttribute(): bool
    {
        return $this->terminated_at !== null;
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnWorkStoppage($query)
    {
        return $query->where('is_work_stoppage', true);
    }

    public function scopeTerminated($query)
    {
        return $query->whereNotNull('terminated_at');
    }

    public function scopeForBrand($query, int $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeForStore($query, int $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}