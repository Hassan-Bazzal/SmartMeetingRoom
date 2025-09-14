<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Minute;
use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Notification;

class MinuteController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Minutes retrieved successfully',
            'minutes' => Minute::all()
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
            'booking_id'   => 'required|exists:bookings,id',
            'assigned_to'  => 'required|exists:employees,id',
            'content'      => 'required|string|max:10000',
            'status'       => 'required|string|in:pending,approved,rejected',
            'note'         => 'nullable|string|max:255',
            'due_date'     => 'nullable|date',
        ]);

        $user = $request->user();
        $bookingId = $request->booking_id;

        // Ensure user is attendee of the booking
        $isAttendee = Attendee::where('booking_id', $bookingId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isAttendee) {
            return response()->json([
                'message' => 'Only attendees can create minutes.'
            ], 403);
        }
        $booking = Booking::findOrFail($request->booking_id);

        $minute = Minute::create([
            'booking_id' => $bookingId,
            'assigned_to' => $request->assigned_to,
            'created_by' => $user->id,
            'content' => $request->content,
            'status' => $request->status,
            'note' => $request->note,
            'due_date' => $request->due_date,
        ]);
        Notification::create([
            'user_id' => $minute->assigned_to,
            'message' => "You have been assigned a new action item in meeting '{$booking->title}'.",
            'type'    => 'mom_assigned'
        ]);

        return response()->json([
            'message' => 'Minute created successfully',
            'minute'  => $minute
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $minute = Minute::findOrFail($id);
        $this->authorize('view', $minute);

        return response()->json([
            'message' => 'Minute retrieved successfully',
            'minute'  => $minute
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
         $minute = Minute::findOrFail($id);
        $this->authorize('update', $minute);

        $request->validate([
            'content' => 'sometimes|required|string|max:10000',
            'status'  => 'sometimes|required|string|in:pending,approved,rejected',
            'note'    => 'nullable|string|max:255',
            'due_date'=> 'nullable|date',
        ]);

        $minute->update($request->only(['content', 'status', 'note', 'due_date']));
         if ($request->has('status')) {
            Notification::create([
                'user_id' => $minute->assigned_to,
                'message' => "Your assigned action item status has been updated to '{$minute->status}'.",
                'type'    => 'mom_status_update'
            ]);
        }

        return response()->json([
            'message' => 'Minute updated successfully',
            'minute'  => $minute
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $minute = Minute::findOrFail($id);
        $this->authorize('delete', $minute);

        $minute->delete();
         Notification::create([
            'user_id' => $minute->assigned_to,
            'message' => "An assigned action item '{$minute->id}...' was removed from the meeting.",
            'type'    => 'mom_deleted'
        ]);

        return response()->json([
            'message' => 'Minute deleted successfully'
        ], 200);
    }
}
