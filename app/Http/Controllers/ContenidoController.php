<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Storage;

class ContenidoController extends Controller
{
    public function index(){
        $idlocal=auth()->user()->local;
        $locales = DB::select("SELECT L.ID_Local, L.nombre, L.direccion, M.URL
                           FROM locales L
                           LEFT JOIN multimedia M ON M.ID_Local=L.ID_Local
                           WHERE L.estado=1 AND L.ID_Local=$idlocal 
                           ORDER BY L.ID_Local, L.nombre, L.direccion, M.URL DESC LIMIT 1");
        return view(".pages.contenido")->with('locales', $locales);
    }
    public function subir(Request $request){
        if($request->hasFile('imagen')){
            $file = $request->file('imagen');
            $url = 'img/uploads/';
            $file = time() . '-' . $file->getClientOriginalName();
            $uplods = $request->file('imagen')->move($url,$file);
            $imageUrl = $url . $file;
        }
            DB::insert("INSERT INTO multimedia(Tipo,URL) VALUES('Imagen',?)", [$imageUrl]);
        
    }
    public function update(Request $request){
        try {
            $idUsuario = auth()->user()->id;
            $idlocal = auth()->user()->local;
            $sql=DB::insert("UPDATE locales SET nombre=?, direccion=?, fechaModificacion=CURRENT_TIMESTAMP, idUsuario=$idUsuario WHERE ID_Local=?",[
                strtoupper($request->nombre),
                strtoupper($request->direccion),
                $request->id
            ]);
            if($request->hasFile('imagen')){
                $file = $request->file('imagen');
                $url = 'img/uploads/';
                $file = time() . '-' . $file->getClientOriginalName();
                $uplods = $request->file('imagen')->move($url,$file);
                $imageUrl = $url . $file;
            }
                DB::insert("INSERT INTO multimedia(ID_Local,Tipo,URL) VALUES(?,'Imagen',?)", [$idlocal, $imageUrl]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","Local actualizado correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al actualizar los datos");
        }
    }
}
