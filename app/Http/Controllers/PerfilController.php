<?php

namespace App\Http\Controllers;

use App\Perfil;
use App\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct(){
        //Solo estara dispobible  perfil->show para usuarios no autorizados
        $this->middleware('auth',['except' => 'show']);//->Habilitar middleware de autentificacion
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {
        //Obtener las recetas con paginacion
        $recetas = Receta::where('user_id',$perfil->user_id)->paginate(6);

        return view('perfiles.show')->with('perfil',$perfil)->with('recetas',$recetas);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {

        //Ejecutar el Policy
        $this->authorize('view',$perfil);
        //
        return view('perfiles.edit')->with('perfil',$perfil);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {
        //Ejecutar el Policy
        $this->authorize('update',$perfil);

        //Validar
        $data = request()->validate([
            'nombre' => 'required',
            'url' => 'required',
            'biografia' => 'required'
        ]);

        //Si el usuario sube una imagen
        if($request['imagen']){
            //Obtener la ruta de la imagen
            $ruta_imagen = $request['imagen']->store('upload-perfiles','public');

            //Resize de la imagen
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(600,600);
            $img->save();

            //Crear un arreglo de imagen
            $array_imagen = ['imagen' => $ruta_imagen];
        }

        //Asignar nombre y URL
        auth()->user()->url = $data['url'];
        auth()->user()->name = $data['nombre'];
        auth()->user()->save();

        //Eliminar url y name de $data
        //Se eliminaron estos campos porque no existen en la tabla perfil y da un error , asi que despues de guardarlos hay que eliminarlos
        unset($data['url']);
        unset($data['nombre']);
        // return $data;

        //Guardar información
        //Asignar biografia e imagen
        auth()->user()->perfil()->update(array_merge(
            $data,
            $array_imagen ?? [] //-> crea un arreglo vacio si no se ha creado el $array_imagen cuando el usuario haya subido una imagen
        ));

        //Redireccionar
        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfil)
    {
        //
    }
}
