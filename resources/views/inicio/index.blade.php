@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha256-UhQQ4fxEeABh4JrcmAJ1+16id/1dnlOEVCFOxDef9Lw=" crossorigin="anonymous" /> 
@endsection

@section('hero')
    <div class="hero-categorias">
        <form class="container h-100" action="{{route('buscar.show')}}">
            <div class="row h-100 align-items-center">
               <div class="col-md-4 texto-buscar">
                    <p class="display-4">Encuentra una receta para tu próxima comida</p>
                    <input type="search" name="buscar" class="form-control" placeholder="Buscar Receta">
               </div>
            </div>
        </form>
    </div>
@endsection

@section('content')
    {{-- Mostrar imagenes estaticas --> primero en la carpeta public se tiene que crear una carpeta images donde hay vamos a poder tomar las imagenes que queramos colocar --}}
    {{-- <img src="{{asset('/images/tuimagen.jpg')}}" alt="imagen de prueba"> --}}
    <div class="container nuevas-recetas">
        <h2 class="titulo-categoria text-uppercase mb-4">Últimas Recetas</h2>
        <div class="owl-carousel owl-theme">
            @foreach ($nuevas as $nueva)
                <div class="card">
                    <img src="/storage/{{$nueva->imagen}}" alt="Imagen Receta" class="card-img-top">
                    <div class="card-body">
                        {{-- Helper que coloca la primera letra de la palabra en mayuscula --}}
                        <h3>{{Str::title($nueva->titulo)}}</h3>
                        {{-- cuenta palabras y limit cuenta letras --}}
                        <p>{{Str::words(strip_tags($nueva->preparacion),20)}}</p>
                        <a class="btn btn-primary d-block font-weight-bold text-uppercase" href="{{route('recetas.show',['receta' => $nueva->id])}}">Ver Receta</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        {{-- Buscara los guiones medios de las palabras para reemplazarlo por un espacio --}}
        <h2 class="titulo-categoria text-uppercase mt-5 mb-4">Recetas Más Votadas</h2>
        <div class="row">
                @foreach($votadas as $receta)
                    @include('ui.receta')
                @endforeach
        </div>
    </div>

    @foreach($recetas as $key => $grupo)
        <div class="container">
            {{-- Buscara los guiones medios de las palabras para reemplazarlo por un espacio --}}
            <h2 class="titulo-categoria text-uppercase mt-5 mb-4">{{str_replace('-',' ',$key)}}</h2>
            <div class="row">
                @foreach($grupo as $recetas)
                    @foreach($recetas as $receta)
                        @include('ui.receta')
                    @endforeach
                @endforeach
            </div>
        </div>
    @endforeach
@endsection