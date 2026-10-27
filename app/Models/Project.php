<?php

namespace App\Models;

use App\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'client',
    'category',
    'summary',
    'description',
    'status',
    'progress',
    'started_on',
    'due_on',
    'project_url',
    'tags',
    'theme',
    'is_featured',
    'display_order',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /** @return BelongsToMany<TeamMember, $this> */
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class)->withTimestamps();
    }

    /** @return HasMany<ProjectImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)
            ->orderBy('display_order')
            ->orderBy('id');
    }

    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->where('is_featured', true)
            ->whereNot('status', ProjectStatus::Draft)
            ->orderBy('display_order')
            ->orderByDesc('created_at');
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'progress' => 'integer',
            'started_on' => 'date',
            'due_on' => 'date',
            'tags' => 'array',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
        ];
    }
}
