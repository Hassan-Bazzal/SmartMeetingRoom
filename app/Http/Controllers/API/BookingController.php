<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Employee;
use App\Models\Booking;
use App\Http\Traits\AuthorizesEmployee;

class BookingController extends Controller
{
    use AuthorizesEmployee;

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['only' => ['store', 'update', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return response()->json(['message' => 'Bookings retrieved successfully', 'bookings' => Booking::all()], 200);
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
        'room_id' => 'required|exists:rooms,id',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time',
        'status' => 'nullable|string|in:pending,confirmed,cancelled',
        'agenda' => 'nullable|string|max:255',
        'title' => 'nullable|string|max:255',
    ]);

    $roomId = $request->room_id;
    $startTime = $request->start_time;
    $endTime = $request->end_time;

    
    $conflictingBooking = Booking::where('room_id', $roomId)
        ->where('start_time', '<', $endTime)
        ->where('end_time', '>', $startTime)
        ->exists();

    if ($conflictingBooking) {
        return response()->json([
            'message' => 'This room is already booked at the selected time.'
        ], 422);
    }

    $booking = Booking::create([
        'room_id' => $roomId,
        'user_id' => $request->user()->id,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'status' => $request->status ?? 'pending',
        'agenda' => $request->agenda,
        'title' => $request->title,
    ]);

    return response()->json([
        'message' => 'Booking created successfully', 
        'booking' => $booking
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
        $booking = Booking::findOrFail($id);
        return response()->json(['message' => 'Booking retrieved successfully', 'booking' => $booking], 200);
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
    { $booking = Booking::findOrFail($id);
        
           if (auth()->id() !== $booking->user_id) {
        return response()->json(['message' => 'Forbidden: you did not create this booking'], 403);
    }
        $request->validate([
            'room_id' => 'sometimes|required|exists:rooms,id',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'status' => 'nullable|string|in:pending,confirmed,cancelled',
            'agenda' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);
          $roomId = $request->room_id;
    $startTime = $request->start_time;
    $endTime = $request->end_time;

    $conflictingBooking = Booking::where('room_id', $roomId)
    ->where('id', '!=', $booking->id) // exclude current booking
    ->where(function ($query) use ($startTime, $endTime) {
        $query->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime])
              ->orWhere(function ($q) use ($startTime, $endTime) {
                  $q->where('start_time', '<=', $startTime)
                    ->where('end_time', '>=', $endTime);
              });
    })
    ->exists();
 if ($conflictingBooking) {
        return response()->json([
            'message' => 'This room is already booked at the selected time.'
        ], 422);
    }
        
        
       
       
        $booking->update($request->only(['room_id', 'booked_by', 'start_time', 'end_time', 'status', 'agenda', 'title']));
        
        return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
           if (auth()->id() !== $booking->user_id) {
        return response()->json(['message' => 'Forbidden: you did not create this booking'], 403);
    }
        $booking->delete();
        
        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }
}
