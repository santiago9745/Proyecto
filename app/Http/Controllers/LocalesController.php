<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;

class LocalesController extends Controller
{
    public function index(){
        $sql=DB::select("SELECT * FROM locales WHERE estado=1");
        $usuarios = DB::select("SELECT id,nombre FROM users WHERE estado=1 OR estado=2");;
        return view(".pages.locales")->with(['sql' => $sql, 'usuarios' => $usuarios]);   
    }
    
    public function create(Request $request){
        try {
            $idUsuario = auth()->user()->id;
            $sql=DB::insert("INSERT INTO locales(nombre,direccion,idUsuario,latitud,longitud) VALUES(?,?,$idUsuario,?,?)",[
                strtoupper($request->nombre),
                strtoupper($request->direccion),
                $request->latitud,
                $request->longitud
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
            $idUsuario = auth()->user()->id;
            $sql=DB::insert("UPDATE locales SET nombre=?, direccion=?, fechaModificacion=CURRENT_TIMESTAMP, idUsuario=$idUsuario,latitud=?, longitud=? WHERE ID_Local=?",[
                strtoupper($request->nombre),
                strtoupper($request->direccion),
                $request->latitud,
                $request->longitud,
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
    public function buscar(Request $request){
        $nombre = strtoupper($request->nombre);
        $sql=DB::select("SELECT L.ID_Local,L.nombre,L.direccion,T.nombre_deporte
                        FROM locales L
                        INNER JOIN canchas C ON L.ID_Local = C.ID_Local
                        INNER JOIN canchatipo CT ON C.ID_Cancha=CT.ID_Cancha
                        INNER JOIN tipo T ON T.ID_Tipo = CT.ID_Tipo
                        WHERE L.estado=1 AND T.nombre_deporte LIKE '%$nombre%' OR L.nombre LIKE '%$nombre%'",[
                        ]);
        return view(".pages.busqueda")->with('sql', $sql);
    }
    public function showMap()
    {
        // Obtén la lista de locales con sus coordenadas
        $locales = DB::select("SELECT L.ID_Local,L.nombre AS nombreLocal, L.direccion, U.nombre AS nombreUsuario, U.email, U.telefono,L.latitud,L.longitud
                                FROM locales L
                                INNER JOIN users U ON L.ID_Local = U.local;");

        // Pasa los datos de los locales a la vista
        return view('.pages.mapa', ['locales' => $locales]);
    }
}
