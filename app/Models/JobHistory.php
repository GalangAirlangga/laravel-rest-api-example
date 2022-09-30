<?php

namespace App\Models;

use Database\Factories\JobHistoryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobHistory
 *
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 * @property int $employee_id
 * @property int $department_id
 * @property int $position_id
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department $department
 * @property-read Employee $employee
 * @property-read Position $position
 * @method static JobHistoryFactory factory(...$parameters)
 * @method static Builder|JobHistory newModelQuery()
 * @method static Builder|JobHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|JobHistory onlyTrashed()
 * @method static Builder|JobHistory query()
 * @method static Builder|JobHistory whereCreatedAt($value)
 * @method static Builder|JobHistory whereDeletedAt($value)
 * @method static Builder|JobHistory whereDepartmentId($value)
 * @method static Builder|JobHistory whereEmployeeId($value)
 * @method static Builder|JobHistory whereEndDate($value)
 * @method static Builder|JobHistory whereId($value)
 * @method static Builder|JobHistory wherePositionId($value)
 * @method static Builder|JobHistory whereStartDate($value)
 * @method static Builder|JobHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|JobHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|JobHistory withoutTrashed()
 * @mixin Eloquent
 */
class JobHistory extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'position_id',
        'department_id',
        'employee_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
