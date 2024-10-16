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
            $sql=DB::insert("INSERT INTO users(username,nombre,primerApellido,segundoApellido,email,rol,idUsuario,password,estado) VALUES(?,?,?,?,?,?,?,?,2)",[
                $username,
                strtoupper($request->nombre),
                strtoupper($request->primerApellido),
                strtoupper($request->segundoApellido),
                $request->email,
                $request->rol,
                $UserId,
                bcrypt($password)
                
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
            $sql = "UPDATE users SET username = ?, nombre = ?, primerApellido = ?, segundoApellido = ?, email = ?,fechaModificacion=CURRENT_TIMESTAMP()";
            $params = [
                $request->username,
                strtoupper($request->nombre),
                strtoupper($request->primerApellido),
                strtoupper($request->segundoApellido),
                $request->email
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
    public function reporteUsuarios(Request $request)
    {
        // Validamos que se reciban las fechas de inicio y fin
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Obtenemos las fechas de inicio y fin del request
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $idLocal = auth()->user()->local;
        // Consulta SQL con filtrado por rango de fechas
        $usuarios = DB::select("SELECT u.id, u.nombre AS nombre_usuario, u.email, COUNT(r.ID_Reserva) AS total_reservas
            FROM users u
            INNER JOIN reservas r ON u.id = r.id
            INNER JOIN detalle_reserva DR ON r.ID_Reserva=DR.ID_Reserva
            INNER JOIN canchas C ON C.ID_Cancha=DR.ID_Cancha
            INNER JOIN locales l ON C.ID_Local = l.ID_Local
            WHERE r.Estado_Reserva = 'Confirmada'
            AND r.fecha_reserva BETWEEN ? AND ? -- Filtrado por rango de fechas
            AND l.ID_Local = ?
            GROUP BY u.id, u.nombre, u.email, l.nombre
            ORDER BY total_reservas DESC
        ", [$fechaInicio, $fechaFin, $idLocal]);


        // Cargar la vista PDF con los usuarios filtrados
        $pdf = Pdf::loadView('pages.reportes.reporteUsuarios', compact('usuarios', 'fechaInicio', 'fechaFin'));

        // Retornar el PDF para ser visualizado
        return $pdf->stream();
    }
}
