<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrudController extends Controller
{
    public function index(){
        $sql=DB::select(" select * from usuarios ");
        return view("welcome")->with("sql",$sql);
    }
    public function create(Request $request){
        try {
            $sql=DB::insert(" insert into usuarios(Nombre,Apellido,Correo_Electronico,Contraseña,Rol) values(?,?,?,?,?)",[
                $request->nombre,
                $request->apellido,
                $request->correo_electronico,
                $request->contrasena,
                $request->rol
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        
        if($sql == true) {
            return back()->with("correcto","producto usuarios registrado");
        }
        else{
            return back()->with("incorrecto","Error de resgistro");
        } 

    }
    public function update(Request $request){
        try {
            $sql=DB::update("update usuarios set Nombre=?, Apellido=?, Correo_Electronico=?, Contraseña=?,Rol=? where ID_Usuario=? ",[
                $request->nombre,
                $request->apellido,
                $request->correo_electronico,
                $request->contrasena,
                $request->rol,
                $request->idusuario
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        
        if($sql == true) {
            return back()->with("correcto","producto usuario modificado");
        }
        else{
            return back()->with("incorrecto","Error de modificacion");
        } 

    }
    public function delete($id){
        try {
            $sql=DB::insert("DELETE FROM usuarios WHERE ID_Usuario=$id");
        } catch (\Throwable $th) {
            $sql=0;
        }
        
        if($sql == true) {
            return back()->with("correcto","producto usuarios registrado");
        }
        else{
            return back()->with("incorrecto","Error de resgistro");
        } 
    }
    public function login(Request $request)
    {
        
        try {
            $sql=DB::select(" select Correo_Electronico,Contraseña from usuarios where Correo_Electronico='?'",[
                $request->correo
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true) {
            return back()->with("correcto","producto usuarios registrado");
        }
        else{
            return back()->with("incorrecto","Error de resgistro");
        } 
    }
}
