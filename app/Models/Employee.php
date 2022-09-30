<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Employee
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone_number
 * @property string $hire_date
 * @property float $salary
 * @property int $department_id
 * @property int $position_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department $department
 * @property-read Collection|JobHistory[] $jobHistories
 * @property-read int|null $job_histories_count
 * @property-read Position $position
 * @method static EmployeeFactory factory(...$parameters)
 * @method static Builder|Employee newModelQuery()
 * @method static Builder|Employee newQuery()
 * @method static \Illuminate\Database\Query\Builder|Employee onlyTrashed()
 * @method static Builder|Employee query()
 * @method static Builder|Employee whereCreatedAt($value)
 * @method static Builder|Employee whereDeletedAt($value)
 * @method static Builder|Employee whereDepartmentId($value)
 * @method static Builder|Employee whereEmail($value)
 * @method static Builder|Employee whereFirstName($value)
 * @method static Builder|Employee whereHireDate($value)
 * @method static Builder|Employee whereId($value)
 * @method static Builder|Employee whereLastName($value)
 * @method static Builder|Employee wherePhoneNumber($value)
 * @method static Builder|Employee wherePositionId($value)
 * @method static Builder|Employee whereSalary($value)
 * @method static Builder|Employee whereUpdatedAt($value)
 * @method static Builder|Employee withPositionAndDepartment()
 * @method static \Illuminate\Database\Query\Builder|Employee withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Employee withoutTrashed()
 * @mixin Eloquent
 */
class Employee extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'hire_date',
        'salary',
        'department_id',
        'position_id'
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function jobHistories(): HasMany
    {
        return $this->hasMany(JobHistory::class, 'employee_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    /**
     * Scope a query
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithPositionAndDepartment(Builder $query): Builder
    {
        return $query->leftJoin("departments", function ($join) {
        $join->on("departments.id", "=", "employees.department_id");
    })
        ->leftJoin("positions", function ($join) {
            $join->on("employees.position_id", "=", "positions.id");
        })
        ->select([
            "employees.id",
            "employees.first_name",
            "employees.last_name",
            "employees.email",
            "employees.phone_number",
            "employees.salary",
            "employees.hire_date",
            "positions.name as position",
            "departments.name as department"
        ]);
    }

}
