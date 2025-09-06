<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
    'message' => 'Notifications retrieved successfully',
    'notifications' => auth()->user()->notifications()->latest()->get()
], 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'is_read' => 'boolean',
            'user_id' => 'required|exists:employees,id',
        ]);

        $notification = auth()->user()->notifications()->create([
            'type' => $request->type,
            'message' => $request->message,
            'is_read' => $request->is_read ?? false,
            'user_id' => $request->user_id, 
        ]);

        return response()->json(['message' => 'Notification created successfully', 'notification' => $notification], 201);
    }
   public function markAllAsRead()
{
    \Log::info('Mark all as read endpoint hit', ['user_id' => auth()->id()]);

    $user = auth()->user();

    \DB::table('notifications')
        ->where('user_id', $user->id)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json(['message' => 'All notifications marked as read']);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        return response()->json(['message' => 'Notification retrieved successfully', 'notification' => $notification], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        return response()->json(['message' => 'Notification retrieved successfully', 'notification' => $notification], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $request->validate([
            'type' => 'sometimes|required|string|max:255',
            'message' => 'sometimes|required|string|max:1000',
            'is_read' => 'sometimes|required|boolean',
        ]);

        $notification->update($request->only(['type', 'message', 'is_read']));

        return response()->json(['message' => 'Notification updated successfully', 'notification' => $notification], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully'], 200);
    }
}
