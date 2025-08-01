<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Employee;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['message' => 'Attendees retrieved successfully', 'attendees' => Attendee::with(['employee', 'booking'])->get()], 200);
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
            'booking_id' => 'required|exists:bookings,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'nullable|string|in:confirmed,cancelled,invited',
        ]);
        $attendee = Attendee::create([
            'booking_id' => $request->booking_id,
            'user_id' => $request->user_id,
            'status' => $request->status ?? 'invited',
        ]);
        
        return response()->json(['message' => 'Attendee created successfully', 'attendee' => $attendee], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendee = Attendee::with(['employee', 'booking'])->findOrFail($id);
        return response()->json(['message' => 'Attendee retrieved successfully', 'attendee' => $attendee], 200);
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
        $request->validate([
            'booking_id' => 'sometimes|exists:bookings,id',
            'user_id' => 'sometimes|exists:employees,id',
            'status' => 'sometimes|string|in:confirmed,cancelled,invited',
        ]);
        
        $attendee = Attendee::findOrFail($id);
        $attendee->update($request->only(['booking_id', 'user_id', 'status']));
        
        return response()->json(['message' => 'Attendee updated successfully', 'attendee' => $attendee], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->delete();
        
        return response()->json(['message' => 'Attendee deleted successfully'], 200);
    }
}
