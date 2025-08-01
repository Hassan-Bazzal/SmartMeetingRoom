<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
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
            'file-path' => 'required|string|max:255',
            'file_name' => 'required|string|max:255',
            'uploaded_by' => 'required|exists:users,id',
            'minute_id' => 'nullable|exists:minutes,id',
        ]);
        $attachment = Attachment::create([
            'file_name' => $request->file_name,
            'file_path' => $request->file_path,
            'uploaded_by' => $request->uploaded_by,
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
        $request->validate([
            'file_name' => 'sometimes|required|string|max:255',
            'file_path' => 'sometimes|required|string|max:255',
            'minute_id' => 'nullable|exists:minutes,id',
           
        ]);
        $attachment = Attachment::findOrFail($id);
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
        $attachment->delete();
        return response()->json(['message' => 'Attachment deleted successfully'], 200);
    }
}
