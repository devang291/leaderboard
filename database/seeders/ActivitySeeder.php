<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
    foreach ($users as $user) {
        for ($i = 0; $i < rand(5, 15); $i++) {
            Activity::create([
                'user_id' => $user->id,
                'activity_date' => Carbon::now()->subDays(rand(0, 30)),
                'points' => 20,
            ]);
        }
    }
    }
}
