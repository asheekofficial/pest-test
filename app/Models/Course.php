<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * Class Course
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property array $learnings
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property-read Video[] $videos
 */
class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'learnings' => 'array',
        ];
    }

    /**
     * Scope a query to only include courses that have been released.
     */
    public function scopeReleased(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function videosCount(): int
    {
        return $this->videos()->count();
    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class,'course_transactions')
        ->withTimestamps();
    }

    public function courseUsers(Builder $query)
    {
        $userId = Auth::id();

        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
