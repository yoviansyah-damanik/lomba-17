<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable(['name', 'start_time', 'end_time', 'rank_open', 'hide_identity'])]
class Competition extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'rank_open' => 'boolean',
            'hide_identity' => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        $now = Carbon::now();

        return $now->between($this->start_time, $this->end_time);
    }

    /** @return HasMany<Criterion, $this> */
    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }

    /** @return HasMany<Registration, $this> */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
