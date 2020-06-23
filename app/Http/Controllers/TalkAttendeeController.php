<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Talk;
use App\Models\TalkAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TalkAttendeeController extends Controller
{
    public function index(Request $request)
    {
        $attendees = TalkAttendee::with('talk', 'attendee')->paginate();
        return view('talk-attendees', compact('attendees'));
    }

    public function addTalkAttendeeView(Request $request)
    {
        $talks = Talk::all();
        $attendees = Attendee::all();
        return view('add-talk-attendee', compact('talks', 'attendees'));
    }

    public function addTalkAttendee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'talk' => 'required|numeric|exists:talks,id',
            'attendee'  => 'required|numeric|exists:attendees,id'
        ]);

        if ($validator->fails()) return [
            'status' => false,
            'message' => $validator->errors()
        ];

        $validated = $validator->validated();

        $check_attendance = TalkAttendee::where(['talk_id' => $validated['talk'], 'attendee_id' => $validated['attendee']])->first();

        if (!empty($check_attendance)) return [
            'status' => false,
            'message' => "This person is already attending this talk"
        ];

        $attendance = TalkAttendee::create([
            'talk_id' => $validated['talk'],
            'attendee_id' => $validated['attendee']
        ]);

        if (!$attendance) return [
            'status' => false,
            'message' => "Failed to create talk attendance, please try again later."
        ];

        return [
            'status' => true,
            'message' => "Talk attendance created successfully"
        ];
    }
}
