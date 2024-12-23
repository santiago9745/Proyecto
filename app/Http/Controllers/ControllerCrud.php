<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ControllerCrud extends Controller
{
    
    public function index(){
        $sql=DB::select("SELECT * FROM users WHERE estado=1 OR estado=2");
        return view(".pages.user-management")->with("sql",$sql);
    }
    public function delete($id){
        try {
            $sql=DB::insert("UPDATE users SET estado=0 WHERE id=$id");
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
    public function create(Request $request){
        try {
            $UserId = auth()->user()->id;
            $username = strtolower($request->nombre) . '.' . substr($request->primerApellido, 0, 1) . substr($request->segundoApellido, 0, 1);
            $password = Str::random(10);
            $sql=DB::insert("INSERT INTO users(username,nombre,primerApellido,segundoApellido,email,rol,idUsuario,password,estado,telefono) VALUES(?,?,?,?,?,?,?,?,2,?)",[
                $username,
                strtoupper($request->nombre),
                strtoupper($request->primerApellido),
                strtoupper($request->segundoApellido),
                $request->email,
                $request->rol,
                $UserId,
                bcrypt($password),
                $request->numeroCelular
                
            ]);
            $data = array(
                'username' => $username,
                'password' => $password,
            );
            Mail::to($request->email)->send(new SendMail($data));
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
            // Obtenemos el ID del usuario desde el request
            $id = $request->id;
    
            // Construimos la consulta SQL base
            $sql = "UPDATE users SET username = ?, nombre = ?, primerApellido = ?, segundoApellido = ?, email = ?, telefono=?, rol=?, fechaModificacion=CURRENT_TIMESTAMP()";
            $params = [
                $request->username,
                strtoupper($request->nombre),
                strtoupper($request->primerApellido),
                strtoupper($request->segundoApellido),
                $request->email,
                $request->numeroCelular,
                $request->rol
            ];
    
            // Si la contraseña no está vacía, la agregamos a la consulta y a los parámetros
            if (!empty($request->password)) {
                $sql .= ", password = ?";
                $params[] = bcrypt($request->password);
            }
    
            // Añadimos la condición para el id del usuario
            $sql .= " WHERE id = ?";
            $params[] = $id;
    
            // Ejecutamos la consulta con los parámetros
            $result = DB::update($sql, $params);
        } catch (\Throwable $th) {
            $result = false;
        }
    
        if($result){
            return back()->with("correcto", "Usuario actualizado correctamente");
        } else {
            return back()->with("incorrecto", "Error al actualizar el usuario");
        }
    }
    
}
