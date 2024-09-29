<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Storage;

class ContenidoController extends Controller
{
    public function index(){
        $idlocal=auth()->user()->local;
        $locales = DB::select("SELECT L.ID_Local, L.nombre, L.direccion, M.URL, L.latitud, L.longitud
                           FROM locales L
                           LEFT JOIN multimedia M ON M.ID_Local=L.ID_Local
                           WHERE L.estado=1 AND L.ID_Local=$idlocal 
                           ORDER BY L.ID_Local, L.nombre, L.direccion, M.URL DESC LIMIT 1");
        $imagenes = DB::select("SELECT M.ID_Multimedia, M.URL
        FROM multimedia M
        WHERE M.ID_Local = ?", 
        [$idlocal]);
        return view(".pages.contenido")->with(['locales' => $locales, 'imagenes' => $imagenes]);
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
    public function update(Request $request) {
        try {
            $idUsuario = auth()->user()->id;
            $idlocal = auth()->user()->local;
    
            // Obtener el local actual de la base de datos
            $localActual = DB::table('locales')->where('ID_Local', $request->id)->first();
    
            // Si la latitud y longitud no están presentes en el request, usar las que ya existen en la base de datos
            $latitud = $request->latitud ?: $localActual->latitud;
            $longitud = $request->longitud ?: $localActual->longitud;
    
            $sql = DB::update("UPDATE locales SET nombre=?, direccion=?, latitud=?, longitud=?, fechaModificacion=CURRENT_TIMESTAMP, idUsuario=$idUsuario WHERE ID_Local=?", [
                strtoupper($request->nombre),
                strtoupper($request->direccion),
                $latitud,
                $longitud,
                $request->id
            ]);
    
            // Manejar la subida de imágenes
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $file) {
                    $url = 'img/uploads/';
                    $fileName = time() . '-' . $file->getClientOriginalName();
                    $file->move($url, $fileName);
                    $imageUrl = $url . $fileName;
    
                    DB::insert("INSERT INTO multimedia(ID_Local, Tipo, URL) VALUES(?, 'Imagen', ?)", [$idlocal, $imageUrl]);
                }
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
    
        if ($sql == true) {
            return back()->with("correcto", "Local actualizado correctamente");
        } else {
            return back()->with("incorrecto", "Error al actualizar los datos");
        }
    }
    public function eliminarImagen($id)
    {
       $sql= DB::delete("DELETE FROM multimedia 
                        WHERE ID_Multimedia = $id");
        if ($sql == true) {
            return back()->with("correcto", "Local actualizado correctamente");
        } else {
            return back()->with("incorrecto", "Error al actualizar los datos");
        }
    }
    
}
