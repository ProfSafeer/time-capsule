<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Capsule;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TimeCapsuleController extends Controller
{
    public function getAllCapsules(Request $request)
    {
        $user = Auth::user();
        $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
    
        // Retrieve capsules where opening_time is smaller than currentDateTime
        $capsules = Capsule::where('user_id', $user->id)
            // ->where('opening_time', '<', $currentDateTime)
            ->get();
    
        return response()->json([
            'status' => 'success',
            'data' => $capsules,
            'time' => $currentDateTime
        ]);
    }

    public function createTimeCapsule(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Validate the incoming request data (you can customize validation rules as needed)
            $validatedData = $request->validate([
                'title' => 'required|string',
                'message' => 'required|string',
                'opening_time' => 'required|date',
            ]);
            $carbonDatetime = Carbon::parse($validatedData['opening_time']);
            $formattedDatetime = $carbonDatetime->toIso8601String();


            // Create a new capsule with the validated data and user_id set to the current user's ID
            $capsule = Capsule::create([
                'title' => $validatedData['title'],
                'message' => $validatedData['message'],
                'opening_time' => $formattedDatetime,
                'user_id' => $user->id,
            ]);

            // Return a response indicating success
            return response()->json([
                'status' => 'success',
                'message' => 'Capsule created successfully.',
                'capsule' => $capsule,
            ]);
        } catch (QueryException $e) {
            // Handle database query exception
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateCapsule(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = Auth::user();

            // Validate the incoming request data (you can customize validation rules as needed)
            $validatedData = $request->validate([
                'id' => 'required',
                'title' => 'required|string',
                'message' => 'required|string',
                'opening_time' => 'required|date',
                'is_opened' => 'required|boolean',
            ]);

            Capsule::where('id', $validatedData['id'])
                ->where('user_id', $user->id)
                ->update([
                    'title' => $validatedData['title'],
                    'message' => $validatedData['message'],
                    'opening_time' => $validatedData['opening_time'],
                    'is_opened' => $validatedData['is_opened'],
                ]);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Capsule updated successfully.',
            ]);
        } catch (QueryException $e) {
            // Handle database query exception
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function updateIsOpened(Request $request,)
    {
        try {
            // Retrieve the authenticated user
            $user = Auth::user();

            $validatedData = $request->validate([
                'id' => 'required',
                'is_opened' => 'required|boolean',
            ]);
            // Find the capsule by ID and ensure it belongs to the authenticated user
            Capsule::where('id', $validatedData['id'])
                ->where('user_id', $user->id)
                ->update([
                    'is_opened' => $validatedData['is_opened'],
                ]);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Capsule is_opened updated successfully.',
            ]);
        } catch (QueryException $e) {
            // Handle database query exception
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
