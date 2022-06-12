@extends('layers\html')

@section('title', 'User ' . $user['id'])

@section('main-content')
    <h1>User {{$user['id']}}</h1>
    {{--Photo--}}
    <img src="{{asset($user->photo)}}" alt="" width="70px">
    <form>
        {{--ID--}}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" value="{{$user['id']}}" readonly>
        </div>
        {{--Email--}}
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" value="{{$user['email']}}" readonly>
        </div>
        {{--Phone--}}
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" value="{{$user['phone']}}" readonly>
        </div>
        {{--Name--}}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" value="{{$user['name']}}" readonly>
        </div>
        {{--Position--}}
        <div class="form-group">
            <label for="position_id">Position</label>
            <input type="text" class="form-control" id="position_id" value="{{$user['position_id']}}" readonly>
        </div>
    </form>
@endsection
