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
    <h1 class="text-center">Editar Mi Perfil</h1>
    <div class="row justify-content-center mt-5">
        <div class="col-md-10 bg-white p-3">
            <form method="POST" action="{{route('perfiles.update',['perfil' => $perfil->id])}}" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input value="{{$perfil->usuario->name}}" type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" placeholder="Tu Nombre">
                    @error('nombre')
                        <span class="invalid-feedback d-block" role="alert">
                            {{-- Este mensaje lo genera laravel --}}
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="url">Sitio Web</label>
                    <input value="{{$perfil->usuario->url}}" type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" placeholder="Tu Sitio Web">
                    @error('url')
                        <span class="invalid-feedback d-block" role="alert">
                            {{-- Este mensaje lo genera laravel --}}
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="biografia">Biografia</label>
                    <input type="hidden" name="biografia" id="biografia" value="{{$perfil->biografia}}">
                    <trix-editor class="form-control @error('biografia') is-invalid @enderror" input="biografia"></trix-editor>
                    @error('biografia')
                        <span class="invalid-feedback d-block" role="alert">
                            {{-- Este mensaje lo genera laravel --}}
                            <strong>{{$message}}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <label for="imagen">Tu Imagen</label>
                    <input id="imagen" type="file" class="form-control @error('imagen') is-invalid @enderror" name="imagen">
                    <div class="mt-4">
                    @if($perfil->imagen)
                        <p>Imagen Actual:</p>
                        <img src="/storage/{{$perfil->imagen}}" style="width: 300px">
                    </div>
                        @error('imagen')
                        <span class="invalid-feedback d-block" role="alert">
                            {{-- Este mensaje lo genera laravel --}}
                            <strong>{{$message}}</strong>
                        </span>
                        @enderror
                    @endif
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Actualizar Perfil">
                </div>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.3/trix.js" integrity="sha256-b2QKiCv0BXIIuoHBOxol1XbUcNjWqOcZixymQn9CQDE=" crossorigin="anonymous" defer></script>
@endsection