<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Storage;

class ContenidoController extends Controller
{
    public function index(){
        $sql=DB::select("SELECT * FROM multimedia");
        return view(".pages.contenido")->with('sql', $sql);
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
}
