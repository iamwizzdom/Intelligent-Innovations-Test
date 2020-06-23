<?php

namespace App\Http\Controllers;

use App\Models\Talk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TalkController extends Controller
{
    public function index(Request $request)
    {
        $talks = Talk::paginate();
        return view('home', compact('talks'));
    }

    public function addTalkView(Request $request)
    {
        return view('add-talk');
    }

    public function addTalk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:50|unique:talks,topic',
            'description'  => 'nullable|string|max:200',
            'date'  => 'required|date|after_or_equal:today',
            'time'  => 'required|date_format:H:i'
        ]);

        if ($validator->fails()) return [
            'status' => false,
            'message' => $validator->errors()
        ];

        $talk = Talk::create(array_merge($validator->validated(), ['uuid' => Str::uuid()->toString()]));

        if (!$talk) return [
            'status' => false,
            'message' => "Failed to create talk, please try again later."
        ];

        return [
            'status' => true,
            'message' => "Talk created successfully"
        ];
    }

    public function removeTalk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|uuid|exists:talks,uuid',
        ]);

        if ($validator->fails()) return [
            'status' => false,
            'message' => $validator->errors()
        ];

        $talk = Talk::where('uuid', $request->uuid)->first();

        if (!$talk->delete()) return [
            'status' => false,
            'message' => "Failed remove talk at this time"
        ];

        return [
            'status' => true,
            'message' => "Talk removed successfully"
        ];
    }
}
