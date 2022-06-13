<?php $user_paginator = $content->user_paginator; ?>
@extends('layers.html')


@section('title', 'Users ')

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
        <tbody id="users">
        </tbody>
    </table>
    <div class="text-center m-3">
        <button class="btn btn-primary" id="load-more" data-paginate="2">Load more...</button>
        <p class="invisible">No more users...</p>
    </div>

@endsection

@push('end_scripts')
    <script type="text/javascript">
        const paginate = 1;
        loadMoreData(paginate);

        $('#load-more').click(function () {
            const page = $(this).data('paginate');
            loadMoreData(page);
            $(this).data('paginate', page + 1);
        });

        function loadMoreData(paginate) {
            $.ajax({
                url: '?page=' + paginate,
                type: 'get',
                datatype: 'html',
                beforeSend: function () {
                    $('#load-more').text('Loading...');
                }
            })
                .done(function (data) {
                    if (data.length === 0) {
                        $('.invisible').removeClass('invisible');
                        $('#load-more').hide();
                    } else {
                        $('#load-more').text('Load more...');
                        $('#users').append(data);
                    }
                })
                .fail(function () {
                    alert('Something went wrong.');
                });
        }
    </script>
@endpush
