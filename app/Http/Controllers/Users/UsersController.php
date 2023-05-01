<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    function savePdfImage($pdfImageFolderUrl, $pdfImage){
         if ($pdfImage->isValid()) {
            // Guarda la pdfImage en el directorio especificado
            //php artisan storage:link comando para habilitar para mostrar las imágenes
            
            $currentDate = date('Y-m-d_H-i-s');
            $pdfImageName = $currentDate . '_' . $pdfImage->getClientOriginalName();

           $savedImagePdf = $pdfImage->storeAs($pdfImageFolderUrl, $pdfImageName);

           $pathSavedImagePdf = $pdfImageFolderUrl;
           $prefix = 'public/';
           $pdfImageFolder = substr($pathSavedImagePdf, strlen($prefix)); // obtiene "imágenes"
         
            $pathFiles = env('RUTA_SERVER_FILES');
            return $savedImagePdf ? $pdfImageUrl = $pathFiles.$pdfImageFolder.'/'.$pdfImageName : response()->json(['mensaje' => 'Error al guardar la imagen'], 400);      

        } else {
            return response()->json(['mensaje' => 'Error al guardar la imagen'], 400);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index($page = 1)
    {
        // $user = User::all();
        // return response()->json($user);

          $users = User::paginate(10, ['*'], 'page', $page); // obtenemos los usuarios de la página solicitada
          return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email|min:6',
                'password' => 'required|min:6',
                'fotoUser' => 'required|image|max:10240',
                'rol' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $url = 'public/images';
        $image = $request->file('fotoUser');

         $imageUrl = $this->savePdfImage($url, $image);
       

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'fotoUser' => $imageUrl,
            'rol' => $request->input('rol'),
        ]);

        return response()->json([
            'ok'=>'usuario creado'
        ],201);
    }

    


    /**
     * Display the specified resource.
     */
    public function show($id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del user'
            ],404);
        }

         $user = User::find($id);
         if(is_null($user)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }
        return $user;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del user'
            ],404);
        }
        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|min:6|unique:users,email,'.$id,
                'password' => 'required|min:6',
                'fotoUser' => 'required|image|max:10240'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        $url = 'public/images';
        $image = $request->file('fotoUser');

         $imageUrl = $this->savePdfImage($url, $image);
        
         $user = User::find($id);
         if(is_null($user)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }
         $user->name = $request->input('name');
         $user->email = $request->input('email');
         $user->password = Hash::make($request->input('password'));
         $user->fotoUser = $imageUrl;
         $user->save();
         

        return response()->json([
            'ok'=> 'usuario actualizado'
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id = 0)
    {
        if($id === 0){
            return response()->json([
                'error'=> 'debe enviar el id del user'
            ],404);
        }
        // $user = User::find($id);
        $user = User::find($id);
        if(is_null($user)){
            return response()->json([
                'error'=> 'No se pudo realizar correctamente'
            ],404);
        }

        $user->delete();
        return response()->json([
            'ok'=> 'registro eliminado'
        ],204);
    }
}
