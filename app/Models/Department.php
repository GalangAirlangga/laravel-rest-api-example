<?php

namespace App\Models;

use Database\Factories\DepartmentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Department
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
 * @method static DepartmentFactory factory(...$parameters)
 * @method static Builder|Department newModelQuery()
 * @method static Builder|Department newQuery()
 * @method static \Illuminate\Database\Query\Builder|Department onlyTrashed()
 * @method static Builder|Department query()
 * @method static Builder|Department whereCreatedAt($value)
 * @method static Builder|Department whereDeletedAt($value)
 * @method static Builder|Department whereDescription($value)
 * @method static Builder|Department whereId($value)
 * @method static Builder|Department whereName($value)
 * @method static Builder|Department whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Department withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Department withoutTrashed()
 * @mixin Eloquent
 */
class Department extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function jobHistories(): HasMany
    {
        return $this->hasMany(JobHistory::class, 'department_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
}
