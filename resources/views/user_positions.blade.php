@extends('layers\html')


@section('title', 'User positions')

@section('main-content')
    <h1>User positions</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($positions as $position)
            <tr>
                <td>{{$position->id}}</td>
                <td>{{$position->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
