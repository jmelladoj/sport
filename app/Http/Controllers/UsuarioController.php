<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    //
    public function index()
    {
        //
        return response()->json(User::orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        //
        $request->password = bcrypt($request->password);
        return User::create($request->all());
    }

    public function show($id)
    {
        //
        return User::find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $usuario = User::find($id);
        $usuario->nombre = $request->nombre;

        
        if($request->password){
            $usuario->password = bcrypt($request->password);
        }

        $usuario->tipo_usuario = $request->tipo_usuario;
        
        $usuario->save();

    }

    public function destroy($id)
    {
        //
        $user = User::withTrashed()->whereId($id)->first();

        if($user->deleted_at){
            $user->restore();
        } else {
            $user->delete();
        }
    }

    public function unico($texto){
        $usuario = User::where('usuario', $texto)->first();
        return response()->json($usuario ? false : true);
    }

    public function logeado(){
        return ['usuario' => User::where('id', Auth::id())->first()];
    }
}
