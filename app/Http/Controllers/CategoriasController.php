<?php

namespace App\Http\Controllers;

use App\Receta;
use App\CategoriaReceta;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    public function show(CategoriaReceta $categoriaReceta){
        $recetas = Receta::where('categoria_id',$categoriaReceta->id)->paginate(6);
        return view('categorias.show')->with('recetas',$recetas)->with('categoriaReceta',$categoriaReceta);
    }
}
