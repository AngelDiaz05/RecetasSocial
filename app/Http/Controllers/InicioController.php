<?php

namespace App\Http\Controllers;

use App\Receta;
use App\CategoriaReceta;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index(){
        //Mostrar recetas por cantidad de votos
        //has -> tiene
        // $votadas = Receta::has('likes','>',1)->get(); //-> sirve para un buscador y darle un valor especifico
        $votadas = Receta::withCount('likes')->orderBy('likes_count','desc')->take(3)->get();

        //Obtener las recetas mas nuevas
        //$nuevas = Receta::orderBy('created_at','ASC')->get();
        //latest(los mas nuevos) -> oldest(los mas viejos)
        //limit y take funcionan para decir cuentos elementos queremos traer
        $nuevas = Receta::latest()->limit(6)->get();

        //Obtener todas las categorias
        $categorias = CategoriaReceta::all();

        //Agrupar las recetas por categoria
        $recetas = [];
        foreach($categorias as $categoria){
            //slug -> quita los espacios y coloca un nombre con guiones  "comida-mexicana"
            //Va a traer todas las recetas de cada categoria que haya pero con un limite de 3 recetas por el take
            $recetas[Str::slug($categoria->nombre)][] = Receta::where('categoria_id',$categoria->id)->take(3)->get();
        }

        return view('inicio.index')->with('nuevas',$nuevas)->with('recetas',$recetas)->with('votadas',$votadas);
    }
}
