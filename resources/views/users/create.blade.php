@extends('layers\html')

@section('title', 'User: new')

@section('main-content')
    <h1>Registration</h1>

    <form method="post" action="{{route('users.store')}}" enctype=multipart/form-data>
        @csrf
        {{--Email--}}
        <div class="form-group">
            <label for="email">Email address</label>
            <input aria-describedby="emailHelp" class="form-control" id="email" name="email" placeholder="Enter email"
                   type="email">
            <small class="form-text text-muted" id="emailHelp">We'll never share your email with anyone else.</small>
        </div>
        {{--Password--}}
        <div class="form-group">
            <label for="password">Password</label>
            <input class="form-control" id="password" name="password" placeholder="Password" type="password">
        </div>
        {{--Name--}}
        <div class="form-group">
            <label for="name">Name</label>
            <input aria-describedby="emailHelp" class="form-control" id="name" name="name" placeholder="Enter name"
                   type="text">
        </div>
        {{--Phone--}}
        <div class="form-group">
            <label for="phone">Phone</label>
            <input class="form-control" id="phone" name="phone" placeholder="Enter phone" type="text">
        </div>
        {{--Position todo: get id--}}
        <div class="form-group">
            <label for="position_id">Position ID</label>
            <select class="form-control" id="position_id" name="position_id">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
            </select>
        </div>
        {{--Photo--}}
        <div class="form-group">
            <label for="photo">Photo</label>
            <input class="form-control-file" id="photo" name="photo" type="file">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
