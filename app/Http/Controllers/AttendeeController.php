<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendeeController extends Controller
{

    public function index(Request $request)
    {
        $attendees = Attendee::paginate();
        return view('attendees', compact('attendees'));
    }

    public function addAttendeeView(Request $request)
    {
        return view('add-attendee');
    }

    public function addAttendee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150'
        ]);

        if ($validator->fails()) return [
            'status' => false,
            'message' => $validator->errors()
        ];

        $talk = Attendee::create($validator->validated());

        if (!$talk) return [
            'status' => false,
            'message' => "Failed to add attendee, please try again later."
        ];

        return [
            'status' => true,
            'message' => "Attendee added successfully"
        ];
    }
}
