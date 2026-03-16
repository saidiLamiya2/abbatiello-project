<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'core_store_id',
        'store_employee_id',
        'name',
        'slug',
        'franchise_number',
        'primary_color',
        'secondary_color',
        'logo',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
        'email',
        'is_active',
        'project_type',
        'start_date',
        'expected_opening_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active'             => 'boolean',
            'start_date'            => 'date',
            'expected_opening_date' => 'date',
        ];
    }

    // ─── Mutators ────────────────────────────────────────────────────────────

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootStores($query)
    {
        return $query->whereNull('core_store_id');
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'core_store_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Store::class, 'core_store_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(StoreEmployee::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Returns the resolved primary color:
     * Store override → Brand design_config → null
     */
    public function getResolvedPrimaryColorAttribute(): ?string
    {
        return $this->primary_color
            ?? $this->brand?->design_config['primary_color']
            ?? null;
    }

    /**
     * Returns the resolved logo:
     * Store override → Brand logo → null
     */
    public function getResolvedLogoAttribute(): ?string
    {
        return $this->logo ?? $this->brand?->logo ?? null;
    }

    public function getFullAddressAttribute(): string
    {
        return collect([$this->address, $this->city, $this->province, $this->postal_code])
            ->filter()
            ->implode(', ');
    }
}