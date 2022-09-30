<?php

namespace App\Models;

use Database\Factories\PositionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Position
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Employee[] $employees
 * @property-read int|null $employees_count
 * @property-read Collection|JobHistory[] $jobHistories
 * @property-read int|null $job_histories_count
 * @method static PositionFactory factory(...$parameters)
 * @method static Builder|Position newModelQuery()
 * @method static Builder|Position newQuery()
 * @method static \Illuminate\Database\Query\Builder|Position onlyTrashed()
 * @method static Builder|Position query()
 * @method static Builder|Position whereCreatedAt($value)
 * @method static Builder|Position whereDeletedAt($value)
 * @method static Builder|Position whereDescription($value)
 * @method static Builder|Position whereId($value)
 * @method static Builder|Position whereName($value)
 * @method static Builder|Position whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Position withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Position withoutTrashed()
 * @mixin Eloquent
 */
class Position extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable=[
        'name',
        'description'
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function jobHistories(): HasMany
    {
        return $this->hasMany(JobHistory::class, 'position_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'position_id');
    }
}
