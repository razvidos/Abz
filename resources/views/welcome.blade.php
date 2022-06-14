@extends('layers.html')


@section('title', 'Welcome')

@section('main-content')

    <h1>Abz.agency testing task</h1>

    <ul>
        <li><span>
        <a href="https://github.com/razvidos/Abz">github.com/razvidos/Abz</a>
    </span></li>
    </ul>

    <div class="row mt-5">
        <div class="col-lg-3">
            <img class="rounded-circle img-fluid" src="{{asset('storage/author.jpg')}}" alt="">
        </div>
        <div class="col-lg-4">
            <h4 class="mt-2">Author:</h4>
            <span>Solomianiy Ihor</span>

            <h4 class="mt-2">Position:</h4>
            <span>Junior Laravel Developer</span>
            <h4 class="mt-2">CV:</h4>
            <div class="row">
                <div class="ml-3 col-sm-3">
                    <a href="{{asset('storage/Ihor Solom.docx')}}">
                        <img height="60"
                             src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8d/Microsoft_Word_2013-2019_logo.svg/244px-Microsoft_Word_2013-2019_logo.svg.png"
                             alt="">
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{asset('storage/Ihor Solom.pdf')}}">
                        <img height="60" src="https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg"
                             alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <h4 class="mt-2">Contacts:</h4>
            <div class="row">
                <span>Telegram: <a class="link-primary" href="https://t.me/ingvar_soloma">@ingvar_soloma</a></span>
            </div>
            <div class="row">
                <span> Phone: <a class="link-info" href="tel:+38(097)-224-08-64">+38(097)-224-08-64</a></span>
            </div>
            <div class="row">
                <span>Email: <a class="link-danger"
                                href="mailto:ingvar.soloma@gmail.com">ingvar.soloma@gmail.com</a></span>
            </div>
        </div>
    </div>




@endsection
