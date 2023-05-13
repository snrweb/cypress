<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(["user"])->get();
        return response()->json(
            [
                'activities' => $activities,
                'status' => true,
            ]
        );
    }

    public function getActivitiesByDate(Request $request, string $from, string $to)
    {
        $activities = Activity::with(["user"])
            ->whereBetween('created_at', [$from, $to])
            ->get();

            return response()->json(
                [
                    'activities' => $activities,
                    'status' => true,
                ]
            );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $totalActivity = Activity::whereDate("created_at", "<", Carbon::now()->tomorrow())
        ->whereDate("created_at", ">", Carbon::now()->yesterday())
        ->count();
        

        if ($totalActivity > 3) {
            throw new Exception("You've reached your limit for the day", 429);
        }

        if (!$request->hasFile('image')) {
            throw new Exception("Image is required", 429);
        }

        $activity = new Activity;

        $activity->user_id = $validated['user_id'] ?? null;
        $activity->title = $validated['title'];
        $activity->description = $validated['description'];
        $activity->image = $request->file('image')->store('images');

        if ($activity->save()) {
            $activities = Activity::with(["user"])->get();
            return response()->json(
                [
                    'activities' => $activities,
                    'status' => true,
                ]
            );
        }

        throw new Exception("An error occured", 500);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'user_id' => 'nullable|integer|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string'
        ]);

        $activity = Activity::where("id", $validated["id"])->first();

        if ($activity) {
            if ($request->hasFile('image')) {
                $activity->image = $request->file('image')->store('images');
            }

            $activity->user_id = $validated['user_id'] ?? null;
            $activity->title = $validated['title'];
            $activity->description = $validated['description'];

            if ($activity->save()) {
                $activities = Activity::with(["user"])->get();
                return response()->json(
                    [
                        'activities' => $activities,
                        'status' => true,
                    ]
                );

                throw new Exception("An error occured", 500);
            }
        }

        throw new Exception("Activity not found", 404);
    }

    public function destroy(Activity $activity)
    {
        if ($activity->delete()) {        
            $activities = Activity::with(["user"])->get();
            return response()->json(
                [
                    'activities' => $activities,
                    'status' => true,
                ]
            );
        }

        throw new Exception("An error occured", 500);
    }
}
