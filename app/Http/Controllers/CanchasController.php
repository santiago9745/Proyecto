<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class CanchasController extends Controller
{
    public function index(){
        $sql=DB::select("SELECT C.nombre,C.direccion,C.estado_cancha, T.tipo
                        FROM canchas C
                        INNER JOIN tipos_canchas T ON T.ID_Cancha=C.ID_Cancha");
        return view(".pages.locales")->with("sql",$sql);
    }
    public function create(Request $request){
        try {
            $sql=DB::insert("INSERT INTO canchas(nombre,direccion,estado_cancha) VALUES(?,?,?)",[
                strtoupper($request->nombre),
                strtoupper($request->direccion),
                strtoupper($request->estado)
            ]);
            $canchaId = DB::getPdo()->lastInsertId();

            DB::insert("INSERT INTO tipos_canchas(Tipo, ID_Cancha) VALUES(?, ?)", [
                strtoupper($request->tipo),
                $canchaId
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
}
