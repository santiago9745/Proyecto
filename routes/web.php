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
            


Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware('auth.admin');


Route::group(['middleware' => 'auth.admin'], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('welcome')->middleware('auth');
	Route::get('/user-management', [ControllerCrud::class, 'index'])->name('crud.index');
	Route::get('/locales', [CanchasController::class, 'index'])->name('crud.index');
	Route::get('/canchas', [GestionCancha::class, 'index'])->name('crud.index');
	Route::get('/eliminarUsuario-{id}', [ControllerCrud::class, 'delete'])->name('crud.delete');
	Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
	Route::get('/contenido', [ContenidoController::class, 'index'])->name('admin.index');
});
Route::post('/asignarUsuarios', [CanchasController::class, 'asignacion'])->name('cancha.asignacion');
Route::post('/contenido', [ContenidoController::class, 'subir'])->name('cancha.contenido');
Route::post('/agregarLocal', [CanchasController::class, 'create'])->name('cancha.create');
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