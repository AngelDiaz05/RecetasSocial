<?php

namespace App\Http\Controllers;

use App\Receta;
use App\CategoriaReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    //Creamos el constructo para una vez creada una instancia de este controlador 
    //los metodos esten protegidos por autenticacion
    public function __construct(){
        $this->middleware('auth',['except' => ['show','search']]); //Except hace que show este publico y no requiera autentificacion , si queremos mas de uno publico usamos un arreglo ['show','create']
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Obtenemos todas las recetas que tenga el usuario
        // Auth::user()->recetas->dd();
        //$recetas = auth()->user()->recetas->paginate(2);

        $usuario = auth()->user();

        //Recetas con paginacion
        $recetas = Receta::where('user_id',$usuario->id)->paginate(6);

        ///Mostramos la vista de recetas index
        return view('recetas.index')->with('recetas',$recetas)->with('usuario',$usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Obtenemos todos los valores de nuestra tabla categoria_receta
        //pluck -> es para traer solo los valores que vas a utilizar
        //dd -> sirve para detener la ejecucion y trabaja como un var_dump
        //DB::table('categoria_receta')->get()->pluck('nombre','id')->dd();

        //categorias va a contener el id y nombre --> sin modelo
        //$categorias = DB::table('categoria_recetas')->get()->pluck('nombre','id');

        //Con modelo
        //$categorias = CategoriaReceta::all(['id','nombre'])->dd();
        $categorias = CategoriaReceta::all(['id','nombre']);

        //Mostramos la vista de recetas create
        //1 .-  es el nombre que vas a usar para llamarla en otro archivo , 2 .- es la variable que contiene los datos
        return view('recetas.create')->with('categorias',$categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //va a guardar la imagen en la carpeta
        //dd($request['imagen']->store('upload-recetas','public'));

        //Data va a obtener lo que tenga request -> usamos validate para validar el campo de crear receta
        $data = $request->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image'
        ]);

        //Obtener la ruta de la imagen
        //va a guardar la imagen en la carpeta
        $ruta_imagen = $request['imagen']->store('upload-recetas','public');

        //Rezise de la imagen
        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
        //para que lo guarde en el disco duro del servidor
        $img->save();

        //Indicamos que va a incertar en la tabla recetas (sin modelo)
        // DB::table('recetas')->insert([
        //     'titulo' => $data['titulo'],
        //     'preparacion' => $data['preparacion'],
        //     'ingredientes' => $data['ingredientes'],
        //     'imagen' => $ruta_imagen,
        //     'user_id' => Auth::user()->id, //Quien es el usuario que esta autenticado
        //     'categoria_id' => $data['categoria']
        // ]);
        //Trabaja como var_dump
        //dd($request->all());


        //Almacenar en la BD con MODELO
        //Accedemos al usuario autenticado , despues a la inf del usuaruo y recetas() es la relacion que creamos en la clase User
        //create para crear la tabla
        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'],
            'preparacion' => $data['preparacion'],
            'ingredientes' => $data['ingredientes'],
            'imagen' => $ruta_imagen,
            'categoria_id' => $data['categoria']
        ]);


        //Redireccionar
        return redirect()->action('RecetaController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        //Muestra el contenido de la receta -> tenemos que mardar el id por url
        //return $receta;

        //Obtener si el usuario actual le gusta la receta y esta autenticado
        $like = (auth()->user()) ? auth()->user()->meGusta->contains($receta->id) :false;

        //Pasa la cantidad de likes a la vista
        $likes = $receta->likes->count();//<- nos dice cuantos elementos hay en el arreglo

        return view('recetas.show')->with('receta',$receta)->with('like',$like)->with('likes',$likes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        //Revisar con Policy
        $this->authorize('view',$receta);

        //Con modelo
        //$categorias = CategoriaReceta::all(['id','nombre'])->dd();
        $categorias = CategoriaReceta::all(['id','nombre']);

        return view('recetas.edit')->with('categorias',$categorias)->with('receta',$receta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        //Revisar el Policy
        //Se realizara el update si el usuario creo la reseta 
        $this->authorize('update',$receta);//-> le pasamos la receta actual que se actualizara

        //Data va a obtener lo que tenga request -> usamos validate para validar el campo de crear receta
        $data = $request->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required'
        ]);

        //Asignar los valores
        $receta->titulo = $data['titulo'];
        $receta->categoria_id = $data['categoria'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];

        //Si el usuario sube una nueva imagen
        if(request('imagen')){
            //Obtener la ruta de la imagen
            //va a guardar la imagen en la carpeta
            $ruta_imagen = $request['imagen']->store('upload-recetas','public');

            //Rezise de la imagen
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1000,550);
            //para que lo guarde en el disco duro del servidor
            $img->save();

            //Asignar al objeto
            $receta->imagen = $ruta_imagen;
        }

        //Guardamos
        $receta->save();

        //Redireccionar
        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //Ejecutar el Policy
        $this->authorize('delete',$receta);

        //Eliminar la receta
        $receta->delete();

        return redirect()->action('RecetaController@index');
        
    }

    public function search(Request $request){
        // $busqueda = $request['buscar'];
        $busqueda = $request->get('buscar');

        // "%" -> hace la busqueda en ambas direcciones de la palabra ->  Deliciosa % Pizza 
        $recetas = Receta::where('titulo','like','%' . $busqueda . '%')->paginate(6);
        $recetas->appends(['buscar' => $busqueda]);

        return view('busquedas.show')->with('recetas',$recetas)->with('busqueda',$busqueda);
    }
}
