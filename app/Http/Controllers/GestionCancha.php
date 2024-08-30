<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class GestionCancha extends Controller
{
    public function index(){
        $sql=DB::select("SELECT C.ID_Cancha,C.nombre, C.estado_cancha,T.nombre_deporte
                        FROM canchas C
                        INNER JOIN locales L ON L.ID_Local=C.ID_Local
                        INNER JOIN canchatipo Ct ON C.ID_Cancha=Ct.ID_Cancha
                        INNER JOIN tipo T ON T.ID_Tipo=Ct.ID_Tipo
                        WHERE C.estado=1");
        return view(".pages.canchas")->with('sql', $sql);
    }
    public function agregar(Request $request){
        try {
            $local = auth()->user()->local;
            $sql=DB::insert("INSERT INTO canchas(nombre,estado_cancha,ID_Local) VALUES(?,?,?)",[
                strtoupper($request->nombre),
                strtoupper($request->disponibilidad),
                $local
            ]);
            $canchaId = DB::getPdo()->lastInsertId();
            $sql=DB::insert("INSERT INTO tipo(nombre_deporte) VALUES(?)",[
                strtoupper($request->tipo)
            ]);
            $tipoId = DB::getPdo()->lastInsertId();
            $sql=DB::insert("INSERT INTO canchatipo(ID_Cancha,ID_Tipo) VALUES(?,?)",[
                $canchaId,
                $tipoId
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
            $sql=DB::insert("UPDATE canchas SET nombre=?, estado_cancha=? WHERE ID_Cancha=?",[
                strtoupper($request->nombre),
                strtoupper($request->disponibilidad),
                $request->id
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
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
            $sql=DB::insert("UPDATE canchas SET estado=0 WHERE ID_Cancha=$id");
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
