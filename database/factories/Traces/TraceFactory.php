<?php

namespace Database\Factories\Traces;

use App\Models\Services\Service;
use App\Models\Traces\Trace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TraceFactory extends Factory
{
    protected $model = Trace::class;

    public function definition(): array
    {
        return [
            'sid'  => Service::factory(),
            'tid'  => $this->faker->uuid(),
            'ptid' => null,
            'tp'   => 'fake',
            'tgs'  => [],
            'dt'   => [],
            'lat'  => Carbon::now(),
            'cat'  => Carbon::now(),
        ];
    }
}
