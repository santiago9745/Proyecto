<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Storage;

class ContenidoController extends Controller
{
    public function index() {
        $idlocal = auth()->user()->local;
    
        // 1. Consulta para obtener toda la información del local
        $locales = DB::select("SELECT L.ID_Local, L.nombre, L.direccion, L.latitud, L.longitud, 
                   U.telefono, CONCAT(U.nombre, ' ', U.primerApellido, ' ', U.segundoApellido) AS nombreCompleto
                                FROM locales L
                                INNER JOIN users U ON L.ID_Local = U.local
                                WHERE L.estado = 1 AND L.ID_Local = ?
                                ORDER BY L.ID_Local, L.nombre, L.direccion DESC 
                                LIMIT 1", 
                                [$idlocal]);
    
        // 2. Consulta para obtener todas las imágenes asociadas al local
        $imagenes_local = DB::select("SELECT M.ID_Multimedia, M.URL, M.Tipo
                                        FROM multimedia M
                                        WHERE M.ID_Local = ? AND M.ID_Cancha IS NULL
                                        ORDER BY M.URL DESC", 
                                        [$idlocal]);
        return view(".pages.contenido")->with([
            'locales' => $locales, 
            'imagenes_local' => $imagenes_local
        ]);
    }
    public function indexCanchas(){
        $idlocal = auth()->user()->local;
        $canchas = DB::select("SELECT C.ID_Cancha, C.nombre AS nombreCancha, C.estado_cancha
                                FROM canchas C
                                WHERE C.ID_Local = ? AND estado=1
                                ORDER BY C.ID_Cancha", 
                                [$idlocal]);
    
        // 4. Consulta para obtener todas las imágenes relacionadas con las canchas del local
        $imagenes_canchas = DB::select("SELECT M.ID_Multimedia, M.URL, M.Tipo, C.ID_Cancha
                                    FROM multimedia M
                                    INNER JOIN canchas C ON M.ID_Cancha = C.ID_Cancha
                                    WHERE C.ID_Local = ?
                                    ORDER BY M.URL DESC", 
                                    [$idlocal]);
        return view(".pages.contenidoCanchas")->with([
            'canchas' => $canchas, 
            'imagenes_canchas' => $imagenes_canchas
        ]);    
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
    public function updateCanchas(Request $request) {
        try {
            $idlocal = auth()->user()->local;
            // Obtener el ID de la cancha a actualizar
            $idCancha = $request->id;
    
            // Actualizar la información de la cancha en la base de datos
            $sql = DB::update("UPDATE canchas SET nombre=?, estado_cancha=?, fechaModificacion=CURRENT_TIMESTAMP WHERE ID_Cancha=?", [
                strtoupper($request->nombre), // Nombre de la cancha
                strtoupper($request->estado), // Estado de la cancha
                $idCancha
            ]);
    
            // Manejar la subida de imágenes
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $file) {
                    $url = 'img/uploads/'; // Directorio donde se guardarán las imágenes
                    $fileName = time() . '-' . $file->getClientOriginalName(); // Nombre único para la imagen
                    $file->move($url, $fileName); // Mover la imagen al directorio
                    $imageUrl = $url . $fileName; // URL de la imagen
    
                    // Insertar la nueva imagen en la tabla multimedia
                    DB::insert("INSERT INTO multimedia(ID_Cancha, Tipo, URL,ID_Local) VALUES(?, 'Imagen', ?, ?)", [$idCancha, $imageUrl,$idlocal]);
                }
            }
        } catch (\Throwable $th) {
            $sql = 0; // Manejar cualquier excepción
        }
    
        // Verificar el resultado de la actualización
        if ($sql == true) {
            return redirect()->route('contenido.index', ['id' => $idCancha])->with('correcto', 'Cancha actualizada correctamente');
        } else {
            return back()->with("incorrecto", "Error al actualizar los datos");
        }
    }
    
}
