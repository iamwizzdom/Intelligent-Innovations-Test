@extends('layouts.app')

@section('content')

    <div class="card mt-5">
        <div class="card-body">
            <h5>Attendees</h5>
            <p>Here are a list of attendees</p>
            <div class="table-responsive mt-3 pb-3">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Added</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($attendees as $key => $attendee)
                        <tr>
                            <td>{{ ($key + 1) }}</td>
                            <td>{{ $attendee->name }}</td>
                            <td>{{ DateTime::createFromFormat("Y-m-d H:i:s", $attendee->created_at)->format("F jS, Y") }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($attendees->count() <= 0)
                    <div class="text-center mt-5">
                        <h1>No attendee yet</h1>
                        <a href="{{ route('attendee-add') }}" class="btn btn-outline-primary mt-2">Add Attendee</a>
                    </div>
                @endif
            </div>
            <div class="table-responsive mt-3">
                {{ $attendees->links() }}
            </div>
        </div>
    </div>

@endsection()
