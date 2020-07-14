@extends('layouts.app')

@section('botones')
    @include('ui.navegacion')
@endsection


@section('content')

<h2 class="text-center mb-5">Administra tus recetas</h2>

<div class="col-md-10 mx-auto bg-white p-3">
    <table class="table">
        <thead class="bg-primary text-light">
            <tr>
                <th scope="col">Titulo</th>
                <th scope="col">Categorias</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recetas as $receta)
                <tr>
                    <td>{{$receta->titulo}}</td>
                    <td>{{$receta->categoria->nombre}}</td>
                    <td>
                        <eliminar-receta receta-id="{{$receta->id}}"></eliminar-receta>
                        <a href="{{route('recetas.edit',['receta' => $receta->id])}}" class="btn btn-dark d-block mb-2">Editar</a>
                        <a href="{{route('recetas.show',['receta' => $receta->id])}}" class="btn btn-success d-block mb-2">Ver</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col-12 mt-4 justify-content-center d-flex">
        {{-- Paginacion --}}
        {{$recetas->links()}}
    </div>
    <h2 class="text-center my-5">Recetas que te Gustan</h2>
    <div class="col-md-10 mx-auto bg-white p-3">
        @if(count($usuario->meGusta)>0)
            <ul class="list-group">
                @foreach($usuario->meGusta as $receta)
                    <li class="list-group-item d-flex justify-content-between align-content-center">
                        <p>{{$receta->titulo}}</p>
                        <a class="btn btn-outline-success text-uppercase font-weight-bold" href="{{route('recetas.show',['receta' => $receta->id])}}">Ver</a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center">
                <p class="text-center">Aún no tienes recetas Guardadas <br><small>Dale me gusta a las recetas y aparecerán aquí</small> </p>
                
            </div>
        @endif
    </div>
</div>

@endsection