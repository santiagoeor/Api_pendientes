<?php

namespace App\Http\Controllers\Earring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Categoria;

class CategoriasController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
          
    }

    public function search(Request $request)
    {
        $searchTerm = $request->search;
        // $searchTerm = $request->input('search');

        $categoria = Categoria::query()
                    ->where('categoria', 'like', '%'.$searchTerm.'%')
                    // ->orWhere('email', 'like', '%'.$searchTerm.'%')
                    ->get();

                    return response()->json($categoria);
    }

    public function create(Request $request)
    {
        try{
            $request->validate([
                'categoria' => 'required|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

         $categoria = new Categoria();
         $categoria->categoria = $request->categoria;
         $categoria->save();

        return response()->json([
            'ok'=> 'Categoría creada'
        ],201);
    }

    public function show($id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id de la categoria'
            ],404);
        }

        $categ = Categoria::find($id);

         if(is_null($categ)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }
        return $categ;
    }

    public function update(Request $request, $id = 0){
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del pendiente'
            ],404);
        }

        try{
            $request->validate([
                'categoria' => 'required|max:255'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $categoria = Categoria::find($id);
        if(is_null($categoria)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }

        $categoria->categoria = $request->categoria;
        $categoria->save();

        return response()->json([
            'ok'=> 'categoría actualizada'
        ],201);

    }

    public function destroy(int $id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del user'
            ],404);
        }
        // $user = User::find($id);
        $categoria = Categoria::find($id);
        if(is_null($categoria)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }

        $categoria->delete();
        return response()->json([
            'ok'=> 'registro eliminado'
        ],204);
    }
}
