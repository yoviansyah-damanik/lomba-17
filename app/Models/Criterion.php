<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['competition_id', 'name', 'description', 'school_types'])]
class Criterion extends Model
{
    use HasUuids;

    public const MIN_SCORE = 0;

    public const MAX_SCORE = 31;

    protected function casts(): array
    {
        return [
            'school_types' => 'array',
        ];
    }

    /** @return BelongsTo<Competition, $this> */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function judges(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('school_type');
    }

    /** @return BelongsToMany<User, $this> */
    public function judgesFor(string $schoolType): BelongsToMany
    {
        return $this->judges()->wherePivot('school_type', $schoolType);
    }

    /** @return HasMany<Evaluation, $this> */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
