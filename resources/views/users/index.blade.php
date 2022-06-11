<?php $user_paginator = $content->user_paginator ?>
@extends('layers\html')


@section('title', 'User index')

@section('main-content')
    <h1>Users</h1>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Position ID</th>
        </tr>
        </thead>
        <tbody>
        @foreach($content->users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td><a href="{{route('users.show', $user->id)}}">{{$user->name}}</a></td>
                <td>{{$user->email}}</td>
                <td>{{$user->phone}}</td>
                <td>{{$user->position_id}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            @if (1 != $user_paginator->current_page)
                <li class="page-item">
                    <a class="page-link" href="{{$user_paginator->first_page_url}}">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{$user_paginator->prev_page_url}}" tabindex="-1">Previous</a>
                </li>
            @endif

            @for($page=1; $page <= $user_paginator->last_page; $page++)
                <li class="page-item @if ($page == $user_paginator->current_page)active @endif">
                    <a class="page-link" href="{{route('users.index', ['page' => $page])}}">{{$page}}</a>
                </li>
            @endfor

            @if ($user_paginator->last_page != $user_paginator->current_page)
                <li class="page-item">
                    <a class="page-link" href="{{$user_paginator->next_page_url}}">Next</a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="{{$user_paginator->last_page_url}}">Last</a>
                </li>
            @endif
        </ul>
    </nav>
@endsection
