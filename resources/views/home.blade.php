@extends('layouts.app')

@section('content')

    <div class="card mt-5">
        <div class="card-body">
            <h5>Talks</h5>
            <p>Here are a list of talks</p>
            <div class="table-responsive mt-3 pb-3">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Topic</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Date Added</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($talks as $key => $talk)
                        <tr>
                            <td>{{ ($key + 1) }}</td>
                            <td>{{ $talk->topic }}</td>
                            <td>{{ $talk->description ?? 'No description' }}</td>
                            <td>{{ DateTime::createFromFormat("Y-m-d", $talk->date)->format("F jS, Y") }}</td>
                            <td>{{ DateTime::createFromFormat("H:i:s", $talk->time)->format("h:i a") }}</td>
                            <td>{{ DateTime::createFromFormat("Y-m-d H:i:s", $talk->created_at)->format("F jS, Y") }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($talks->count() <= 0)
                    <div class="text-center mt-5">
                        <h1>No talk yet</h1>
                        <a href="{{ route('talk-add') }}" class="btn btn-outline-primary mt-2">Add Talk</a>
                    </div>
                @endif
            </div>
            <div class="table-responsive mt-3">
                {{ $talks->links() }}
            </div>
        </div>
    </div>

@endsection()
