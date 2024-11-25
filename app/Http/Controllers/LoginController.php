<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Events\RecordatorioReservaEvent;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            if(auth()->user()->rol == 'admin'){
                return redirect()->intended('user-management');
            }
            else{
                if (auth()->user()->rol== 'personal') {
                    $idlocal=auth()->user()->local;
                    Log::info('Evento de recordatorio de reserva está a punto de dispararse.');
                    $reservas = DB::select("SELECT R.ID_Reserva, R.Fecha_Reserva, R.Hora_Inicio, R.Hora_Fin, 
                                            CONCAT(U.nombre,' ',U.primerApellido,' ',U.segundoApellido) AS nombre_completo, 
                                            U.email, C.nombre AS nombre_cancha
                                            FROM reservas R
                                            INNER JOIN users U ON U.id = R.id
                                            INNER JOIN detalle_reserva D ON D.ID_Reserva = R.ID_Reserva
                                            INNER JOIN canchas C ON C.ID_Cancha = D.ID_Cancha
                                            WHERE DATEDIFF(Fecha_Reserva, CURDATE()) = 3
                                            AND R.Estado_Reserva = 1
                                            AND C.ID_Local = ?;",[$idlocal]);  
                    foreach ($reservas as $reserva) {
                        DB::table('reservas')
                            ->where('ID_Reserva', $reserva->ID_Reserva)
                            ->update(['Estado_Reserva' => 3]);
                    }  
                    event(new RecordatorioReservaEvent($reservas));

                    if (auth()->user()->estado == 2) {
                        return redirect('change-password');
                    }
                    else{
                        return redirect()->intended('canchas');
                    }
                }
                else{
                    return redirect()->intended('/');
                }
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
