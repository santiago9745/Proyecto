<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class CanchasController extends Controller
{
    public function index(){
        $sql=DB::select("SELECT * FROM locales WHERE estado=1");
        $usuarios = DB::select("SELECT id,nombre FROM users WHERE estado=1 OR estado=2");;
        return view(".pages.locales")->with(['sql' => $sql, 'usuarios' => $usuarios]);   
    }
    
    public function create(Request $request){
        try {
            $sql=DB::insert("INSERT INTO locales(nombre,direccion) VALUES(?,?)",[
                strtoupper($request->nombre),
                strtoupper($request->direccion)
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","usuarios registrado correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al registrar el usuario");
        }
    }
    public function update(Request $request){
        try {
            $sql=DB::insert("UPDATE locales SET nombre=?, direccion=? WHERE ID_Local=?",[
                strtoupper($request->nombre),
                strtoupper($request->direccion),
                $request->id
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","usuarios registrado correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al registrar el usuario");
        }
    }
    public function asignacion(Request $request)
    {
        try {
            $sql=DB::insert("UPDATE users SET local=? WHERE id=?",[
                $request->id,
                $request->local
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","usuarios registrado correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al registrar el usuario");
        }
    }
    public function delete($id){
        try {
            $sql=DB::insert("UPDATE locales SET estado=0 WHERE ID_Local=$id");
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql==true)
        {
            return back()->with("correcto","usuarios eliminado correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al eliminar un usuario");
        }
    }
}
