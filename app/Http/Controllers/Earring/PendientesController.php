<?php

namespace App\Http\Controllers\Earring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Pendiente;

class PendientesController extends Controller
{
    public function index()
    {
        $pendientes = Pendiente::with('categoria')->get();
        // $pendientes = Pendiente::all();
        return response()->json($pendientes);
          
    }

    public function search(Request $request)
    {
        $searchTerm = $request->search;
        // $searchTerm = $request->input('search');

        $pendiente = Pendiente::query()
                    ->with('categoria')
                    ->where('pendiente', 'like', '%'.$searchTerm.'%')
                    // ->orWhere('email', 'like', '%'.$searchTerm.'%')
                    ->get();

                    return response()->json($pendiente);
    }

    public function create(Request $request){
        try{
            $request->validate([
                'pendiente' => 'required|string|max:255',
                'categoria_id' => 'required|integer|max:10',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i:s',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $pendiente = new Pendiente();
        $pendiente->pendiente = $request->pendiente;
        $pendiente->categoria_id = $request->categoria_id;
        $pendiente->fecha = $request->fecha;
        $pendiente->hora = $request->hora;
        $pendiente->save();

        return response()->json([
            'ok'=> 'pendiente creado'
        ],201);
    }

    public function show($id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del pendiente'
            ],404);
        }

         $pend = Pendiente::with('categoria')->find($id);
         if(is_null($pend)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }
        return $pend;
    }

    public function update(Request $request, $id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del pendiente'
            ],404);
        }
        try{
            $request->validate([
                'pendiente' => 'required|string|max:255',
                'categoria_id' => 'required|integer|max:10',
                'fecha' => 'required|date',
                'hora' => 'required|date_format:H:i:s',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        
         $pendiente = Pendiente::find($id);
         if(is_null($pendiente)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }
        $pendiente->pendiente = $request->pendiente;
        $pendiente->categoria_id = $request->categoria_id;
        $pendiente->fecha = $request->fecha;
        $pendiente->hora = $request->hora;
        $pendiente->save();
         

        return response()->json([
            'ok'=> 'pendiente actualizado'
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
        $pendiente = Pendiente::find($id);
         if(is_null($pendiente)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }

        $pendiente->delete();
        return response()->json([
            'ok'=> 'registro eliminado'
        ],204);
    }
}
