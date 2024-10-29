<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withSum('activities', 'points');

        if ($request->filter === 'day') {
            $query->whereHas('activities', function ($query) {
                $query->whereDate('activity_date', Carbon::today());
            });
        } elseif ($request->filter === 'month') {
            $query->whereHas('activities', function ($query) {
                $query->whereMonth('activity_date', Carbon::now()->month);
            });
        } elseif ($request->filter === 'year') {
            $query->whereHas('activities', function ($query) {
                $query->whereYear('activity_date', Carbon::now()->year);
            });
        }

        if ($request->has('search')) {
            $query->where('id', $request->search);
        }

        $users = $query->orderByDesc('activities_sum_points')->get();

        return view('leaderboard.index', compact('users'));
    }

    public function recalculate()
    {
        // Start a transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Step 1: Retrieve users with the sum of points from activities
            $users = User::withSum('activities', 'points')
                ->orderByDesc('activities_sum_points') // Sort by total points in descending order
                ->get();

            // Step 2: Initialize variables for rank calculation
            $rank = 1; // The actual rank to assign
            $previousPoints = null; // Holds the points of the previous user

            foreach ($users as $user) {
                // Step 3: Check if the user's points differ from the previous user
                if ($user->activities_sum_points !== $previousPoints) {
                    // If points differ, assign the current rank
                    $user->rank = $rank;
                    $previousPoints = $user->activities_sum_points; // Update previous points
                    $rank++; // Increment rank for the next user
                } else {
                    // If points are the same, assign the same rank
                    $user->rank = $rank - 1; // Maintain the previous rank
                }

                // Save the user's rank
                $user->save();
            }

            // Commit the transaction after successful update
            DB::commit();
            return redirect()->route('leaderboard.index');
        } catch (\Exception $e) {
            // Rollback the transaction if there's an error
            DB::rollBack();
            // Log the error for further debugging
            \Log::error('Ranking recalculation failed: ' . $e->getMessage());
            return redirect()->route('leaderboard.index')->with('error', 'Error recalculating ranks. Please try again.');
        }
    }
}
