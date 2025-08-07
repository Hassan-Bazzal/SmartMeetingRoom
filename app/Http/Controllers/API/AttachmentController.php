<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttachmentController extends Controller
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
        return response()->json(['message' => 'Attachments retrieved successfully', 'attachments' => Attachment::all()], 200);
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
            'file_path' => 'required|string|max:255',
            'file_name' => 'required|string|max:255',
            'minute_id' => 'nullable|exists:minutes,id',
        ]);
         $user = $request->user();

    $minute = Minute::findOrFail($request->minute_id);

    // Check if the logged-in user is the one assigned to this minute
    if ($minute->assigned_to !== $user->id) {
        return response()->json(['message' => 'Unauthorized. You are not assigned to this minute.'], 403);
    }
        $attachment = Attachment::create([
            'file_name' => $request->file_name,
            'file_path' => $request->file_path,
            'uploaded_by' => $request->$user->id,
            'minute_id' => $request->minute_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attachment = Attachment::findOrFail($id);
        return response()->json(['message' => 'Attachment retrieved successfully', 'attachment' => $attachment], 200);
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
        $attachment = Attachment::findOrFail($id);
        $this->authorize('update', $attachment);

        $request->validate([
            'file_name' => 'sometimes|required|string|max:255',
            'file_path' => 'sometimes|required|string|max:255',
            'minute_id' => 'nullable|exists:minutes,id',
           
        ]);
        

       
        $attachment->update($request->only(['file_name', 'file_path', 'minute_id']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attachment = Attachment::findOrFail($id);
        $this->authorize('delete', $attachment);
        $attachment->delete();
        return response()->json(['message' => 'Attachment deleted successfully'], 200);
    }
}
