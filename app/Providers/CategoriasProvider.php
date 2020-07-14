<?php

namespace App\Providers;

use View;
use App\CategoriaReceta;
use Illuminate\Support\ServiceProvider;

class CategoriasProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Le pasamos a todas las vistas las categorias
        View::composer('*',function($view){
            $cateforias = CategoriaReceta::all();
            $view->with('categorias',$cateforias);
        });
    }
}
