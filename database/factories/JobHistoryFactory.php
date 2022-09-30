<?php

namespace Database\Factories;

use App\Models\JobHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class JobHistoryFactory extends Factory
{
    protected $model = JobHistory::class;

    public function definition(): array
    {
        return [
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addYears(2),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
