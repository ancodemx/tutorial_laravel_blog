<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        return "Hola desde el listado de posts";
    }
    
    public function create() {
        return "Formulario para crear un nuevo post";
    }

    public function store() {
        return "Almacenando un nuevo post";
    }

    public function show($post) {
        return "Mostrando el post: " . $post;
    }

    public function edit($post) {
        return "Formulario para editar el post: " . $post;
    }

    public function update($post) {
        return "Actualizando el post: " . $post;
    }

    public function destroy($post) {
        return "Eliminando el post: " . $post;
    }
}
