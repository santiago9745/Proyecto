<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NotificarPromocion;
use App\Models\User; 

class PromocionController extends Controller
{
    public function index()
    {
        $local = auth()->user()->local;
        $sql = DB::select("SELECT P.ID_Precio, P.descuento, P.Fecha_Inicio, P.Fecha_Fin,C.nombre
                            FROM precios P
                            LEFT JOIN canchas C ON P.ID_Cancha=C.ID_Cancha
                            WHERE P.ID_Local = $local AND P.estado=1;");
        $canchas = DB::select("SELECT nombre,ID_Cancha
                             FROM canchas
                             WHERE ID_Local=$local ");
        return view(".pages.promociones")->with('sql', $sql)->with('canchas', $canchas);
    }
    public function crear(Request $request)
    {
        $local = auth()->user()->local;
        // Validación de los datos recibidos
        $validatedData = $request->validate([
            'descuento' => 'required|numeric|min:0',  // Descuento debe ser un número positivo
            'fecha_inicio' => 'required|date',        // Fecha de inicio debe ser una fecha válida
            'fecha_fin' => 'required|date|after:fecha_inicio', // Fecha fin debe ser después de la fecha de inicio
        ]);
        try {
            $sql=DB::insert("INSERT INTO precios(descuento,Fecha_Inicio,Fecha_Fin, ID_Local) VALUES (?,?,?,?)",[
                $request->descuento,
                $request->fecha_inicio,
                $request->fecha_fin,
                $local
            ]);
        } catch (\Throwable $th) {
            $sql=0;
        }
        if($sql == true){
            return back()->with("correcto","Promocion creada correctamente");
        }
        else
        {
            return back()->with("incorrecto","Error al crear la promocion");
        }
        // Insertar los datos validados en la base de datos
        

        // Redirigir con un mensaje de éxito
        return redirect()->route('promociones.index')->with('success', 'Promoción creada exitosamente.');
    }
    public function editar(Request $request)
{
    // Validación de los datos recibidos
    $validatedData = $request->validate([
        'descuento' => 'required|numeric|min:0',  // Descuento debe ser un número positivo
        'fecha_inicio' => 'required|date',        // Fecha de inicio debe ser una fecha válida
        'fecha_fin' => 'required|date|after:fecha_inicio', // Fecha fin debe ser después de la fecha de inicio
    ]);

    try {
        // Actualizar la promoción en la base de datos
        $sql = DB::update("UPDATE precios SET descuento = ?, Fecha_Inicio = ?, Fecha_Fin = ? WHERE ID_Precio = ?",
            [
                $request->descuento,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->id
            ]);
    } catch (\Throwable $th) {
        $sql = 0;
    }

    if ($sql) {
        return back()->with("correcto", "Promoción editada correctamente");
    } else {
        return back()->with("incorrecto", "Error al editar la promoción");
    }
}
public function delete($id)
{
    try {
        // Cambiamos el estado a 0 para realizar la eliminación lógica
        $sql = DB::update("UPDATE precios SET estado = 0 WHERE ID_Precio = ?", [$id]);
    } catch (\Throwable $th) {
        // En caso de un error, puedes manejarlo como desees
        $sql = 0;
    }

    if ($sql) {
        return back()->with("correcto", "Precio eliminado correctamente");
    } else {
        return back()->with("incorrecto", "Error al eliminar el precio");
    }
}
public function notificarPromocion($id)
{
    $local = auth()->user()->local;
    // Obtener la promoción y los usuarios que reservaron
        $sql = DB::select("SELECT P.ID_Precio, P.descuento, P.Fecha_Inicio, P.Fecha_Fin, l.nombre AS nombre_local,c.nombre AS nombre_cancha
                            JOIN locales l ON P.ID_Local = l.ID_Local
                            JOIN canchas c ON c.ID_Local = l.ID_Local
                            FROM precios P
                            WHERE P.ID_Local = ? AND P.estado = 1", [$local]);

    // Obtener los correos de los usuarios que han reservado en ese local
    $reservas = DB::select("SELECT DISTINCT u.id, u.email, CONCAT(u.nombre,' ',u.primerApellido,' ',u.segundoApellido) AS nombre_usuario,l.ID_Local
                                FROM users u
                                JOIN reservas r ON u.id = r.id
                                JOIN detalle_reserva dr ON r.ID_Reserva = dr.ID_Reserva
                                JOIN canchas c ON dr.ID_Cancha = c.ID_Cancha
                                JOIN locales l ON c.ID_Local = l.ID_Local
                                WHERE l.ID_Local = ?", [$local]);


    foreach ($reservas as $reserva) {
        // Suponiendo que la promoción que deseas enviar es la primera en la lista
        if (!empty($sql)) {
            $promocion = $sql[0];
            $user = User::find($reserva->id);
            //Mail::to($reserva->email)->send(new NotificacionPromocion($promocion, $reserva->nombre_usuario));
            $user->notify(new NotificarPromocion($promocion, $reserva->nombre_usuario,$reserva->ID_Local));
        }
    }

    return back()->with('success', 'Notificaciones enviadas correctamente.');
}
public function asiganacionPromo(Request $request)
{
    try {
        // Cambiamos el estado a 0 para realizar la eliminación lógica
        $sql= DB::update("UPDATE precios SET ID_Cancha = ? WHERE ID_Precio = ?",[$request->cancha,$request->id]);
    } catch (\Throwable $th) {
        // En caso de un error, puedes manejarlo como desees
        $sql = 0;
    }
    if ($sql) {
        return back()->with("correcto", "desceunto asiganado a cancha correctamente");
    } else {
        return back()->with("incorrecto", "Error al eliminar el precio");
    }
}

}
