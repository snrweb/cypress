<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use App\Http\Resources\UserResource;
use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        return response()->json(
            [
                'users' => UserResource::collection($users),
                'status' => true,
            ]
        );
    }

    public function getUserActivities(User $user)
    {
        $activities = $user->activities;
        return response()->json(
            [
                'activities' => ActivityResource::collection($activities),
                'status' => true,
            ]
        );
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'is_admin' => 'required|integer',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = new User;
        $user->name = $validated["name"];
        $user->is_admin = $validated["is_admin"];
        $user->email = $validated["email"];
        $user->password = Hash::make($validated["password"]);

        if ($user->save()) {
            return response()->json(['message' => 'User registered successfully.'], 200);
        }

        return response()->json(['message' => 'An error occured.'], 500);
    }

    public function storeUserActivity(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $activity = new Activity;

        $activity->user_id = $validated['user_id'];
        $activity->title = $validated['title'];
        $activity->description = $validated['description'];
        $activity->date = $validated['date'];
        if ($request->hasFile('file')) {
            $activity->image = $request->file('image')->store('images');
        }

        if ($activity->save()) {
            return response()->json(
                [
                    'activities' => $this->index(),
                    'status' => true,
                ]
            );
        }

        return response()->json(['message' => 'An error occured.'], 500);
    }

    public function updateUserActivity(Request $request, User $user, Activity $activity)
    {
        $activity->update($request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|string',
        ]));

        return new ActivityResource($activity);
    }

    public function destroyUserActivity(User $user, Activity $activity)
    {
        $activity->delete();
        return response()->json(['message' => 'Activity deleted successfully.'], 200);
    }
}
