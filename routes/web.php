<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;   
use App\Http\Controllers\ControllerCrud;        
use App\Http\Controllers\AdminController;   
use App\Http\Controllers\CanchasController;
use App\Http\Controllers\GestionCancha;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PromocionController;
            


Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware('auth.admin');


Route::group(['middleware' => 'auth.admin'], function () {
	Route::get('/user-management', [ControllerCrud::class, 'index'])->name('crud.index');
	Route::get('/locales', [CanchasController::class, 'index'])->name('crud.index');
	Route::get('/canchas', [GestionCancha::class, 'index'])->name('crud.index');
	Route::get('/eliminarUsuario-{id}', [ControllerCrud::class, 'delete'])->name('crud.delete');
	Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
	Route::get('/contenido', [ContenidoController::class, 'index'])->name('admin.index');
});
Route::get('/mapa', [CanchasController::class, 'showMap'])->name('mapa.locales');
Route::get('/dashboard', [HomeController::class, 'index'])->name('welcome')->middleware('auth');
Route::get('/', [ReservaController::class, 'index'])->name('home.index');
Route::get('/reporteCanchas', [GestionCancha::class, 'pdf'])->name('reportesCanchas');
Route::post('/Cotizacion', [ReservaController::class, 'getReserva'])->name('Cotizacion');
Route::get('/comprobante-{id}', [ReservaController::class, 'reservaComprobante'])->name('comprobante');
Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificacionindex');
Route::get('/promociones', [PromocionController::class, 'index'])->name('promocionindex');
Route::post('/Crearpromociones', [PromocionController::class, 'crear'])->name('crearPromocion');
Route::post('/Editarpromociones', [PromocionController::class, 'editar'])->name('EditarPromocion');
Route::get('/Eliminarpromocion-{id}', [PromocionController::class, 'delete'])->name('EliminarPromocion');
Route::post('/enviar-notificacion', [NotificacionController::class, 'enviarNotificacion'])->name('notificacion.enviar');
Route::post('/notificar-promocion-{id}', [PromocionController::class, 'notificarPromocion'])->name('notificarPromocion');
Route::get('/reporteUtilidadCanchas', [GestionCancha::class, 'canchasUtilidad'])->name('reporteUtilidadCanchas');
Route::get('/reporteUsuarios', [ControllerCrud::class, 'reporteUsuarios'])->name('reporteUsuarios');
Route::get('/canchasLocales', [ReservaController::class, 'mostrarcanchas'])->name('mostrarcanchas');
Route::get('/reservas', [ReservaController::class, 'Reservas'])->name('Reservas');
Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
Route::post('/busqueda', [CanchasController::class, 'buscar'])->name('buscar');
Route::post('/asignarUsuarios', [CanchasController::class, 'asignacion'])->name('local.asignacion');
Route::post('/reservar', [ReservaController::class, 'reserva'])->name('reserva');
Route::put('/reservasCancel/{id}', [ReservaController::class, 'cancelar'])->name('reservaCancelar');
Route::post('/contenido', [ContenidoController::class, 'subir'])->name('cancha.contenido');
Route::post('/updateContenido-{id}', [ContenidoController::class, 'update'])->name('contenido.update');
Route::get('/eliminarImagen-{id}', [ContenidoController::class, 'eliminarImagen'])->name('eliminar.imagen');
Route::post('/agregarLocal', [CanchasController::class, 'create'])->name('local.create');
Route::post('/agregarCancha', [GestionCancha::class, 'agregar'])->name('cancha.create');
Route::post('/updateLocal', [CanchasController::class, 'update'])->name('local.update');
Route::post('/updateCancha', [GestionCancha::class, 'update'])->name('cancha.update');
Route::get('/eliminarLocal-{id}', [CanchasController::class, 'delete'])->name('local.delete');
Route::get('/eliminarCancha-{id}', [GestionCancha::class, 'delete'])->name('cancha.delete');
Route::post('/modificarUsuario', [ControllerCrud::class, 'update'])->name('crud.update');
Route::post('/agregarUsuario', [ControllerCrud::class, 'create'])->name('crud.create');
	Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
	Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
	Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	
Route::group(['middleware' => 'auth'], function () {
	Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
	Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static'); 
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static'); 
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});