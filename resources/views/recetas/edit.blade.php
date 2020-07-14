@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.css" integrity="sha256-scOSmTNhvwKJmV7JQCuR7e6SQ3U9PcJ5rM/OMgL78X8=" crossorigin="anonymous" />
@endsection

@section('botones')
<a href="{{route('recetas.index')}}" class="btn btn-outline-primary mr-2 text-uppercase font-weight-bold">
    <svg class="icono" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"></path></svg>
    Volver</a>
@endsection


@section('content')

<h2 class="text-center mb-5">Editar Receta</h2>

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
    {{-- enctype tenemos que tener esto para poder subir archivos sino marca error --}}
    <form method="POST" action="{{route('recetas.update',['receta' => $receta->id])}}" enctype="multipart/form-data" novalidate>
            @csrf{{--Siempre hay que colocarlo para que el usuario autenticado pueda realizar peticiones de formulario--}}
            {{-- Tenemos que realizar esto para que podamos hacer la peticion PUT --}}
            @method('put')
            <div class="form-group">
                <label for="titulo">Titulo Receta</label>
                {{-- En class se agrega un error para que si ingresan mal algo resalte el input con una clase de bootstrap y usamos value para que no se limpie el campo si hay un error y conserve el valor --}}
                <input value="{{$receta->titulo}}" type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" placeholder="Titulo Receta">
                {{-- Si hay un error en el titulo , muestralo --}}
                @error('titulo')
                    <span class="invalid-feedback d-block" role="alert">
                        {{-- Este mensaje lo genera laravel --}}
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="from-group">
                <label for="categoria">Categoria</label>
                <select name="categoria" class="form-control @error('categoria') is-invalid @enderror" id="categoria">
                    <option value="">--Seleccione--</option>
                    @foreach($categorias as $categoria)
                        {{-- Si la categoria con valor anterior es igual al id del option , entonces seleccionalo --}}
                        <option value="{{$categoria->id}}" {{$receta->categoria_id == $categoria->id ? 'selected' : ''}}>{{$categoria->nombre}}</option>
                    @endforeach
                </select>
                @error('categoria')
                    <span class="invalid-feedback d-block" role="alert">
                        {{-- Este mensaje lo genera laravel --}}
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="preparacion">Preparación</label>
                <input type="hidden" name="preparacion" id="preparacion" value="{{$receta->preparacion}}">
                <trix-editor class="form-control @error('preparacion') is-invalid @enderror" input="preparacion"></trix-editor>
                @error('preparacion')
                    <span class="invalid-feedback d-block" role="alert">
                        {{-- Este mensaje lo genera laravel --}}
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="ingredientes">Ingredientes</label>
                <input type="hidden" name="ingredientes" id="ingredientes" value="{{$receta->ingredientes}}">
                <trix-editor class="form-control @error('ingredientes') is-invalid @enderror" input="ingredientes"></trix-editor>
                @error('ingredientes')
                    <span class="invalid-feedback d-block" role="alert">
                        {{-- Este mensaje lo genera laravel --}}
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="imagen">Elige la imagen</label>
                <input id="imagen" type="file" class="form-control @error('imagen') is-invalid @enderror" name="imagen">
                <div class="mt-4">
                    <p>Imagen Actual:</p>
                    <img src="/storage/{{$receta->imagen}}" style="width: 300px">
                </div>
                @error('imagen')
                    <span class="invalid-feedback d-block" role="alert">
                        {{-- Este mensaje lo genera laravel --}}
                        <strong>{{$message}}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Actualizar Receta">
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.js" integrity="sha256-b2QKiCv0BXIIuoHBOxol1XbUcNjWqOcZixymQn9CQDE=" crossorigin="anonymous" defer></script>
@endsection