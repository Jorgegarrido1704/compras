<?php

use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\ContabilidadController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RequisicionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'mostrarLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.attempt');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Inicio
    |--------------------------------------------------------------------------
    */

    Route::get('/', [AuthController::class, 'home'])
        ->name('home');

    /*
    |--------------------------------------------------------------------------
    | Perfil / Cambiar contraseña
    |--------------------------------------------------------------------------
    */

    Route::get('/perfil/password', [PasswordController::class, 'edit'])
        ->name('perfil.password.edit');

    Route::put('/perfil/password', [PasswordController::class, 'update'])
        ->name('perfil.password.update');

    /*
    |--------------------------------------------------------------------------
    | Solicitante
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:solicitante|admin')
        ->prefix('solicitante')
        ->name('solicitante.')
        ->group(function () {

            Route::get('/', [RequisicionController::class, 'index'])
                ->name('index');

            Route::get('/nueva', [RequisicionController::class, 'nueva'])
                ->name('nueva');

            Route::post('/', [RequisicionController::class, 'store'])
                ->name('store');

            Route::post('/servicio', [RequisicionController::class, 'store_servicio'])
                ->name('store_servicio');
        });

    /*
    |--------------------------------------------------------------------------
    | Compras
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:compras|admin')
        ->prefix('compras')
        ->name('compras.')
        ->group(function () {

            // Lista de requisiciones
            Route::get('/', [ComprasController::class, 'index'])
                ->name('index');

            // Órdenes de compra autorizadas
            // IMPORTANTE:
            // El grupo ya agrega "compras.", por eso aquí solo va "autorizadas"
            Route::get('/autorizadas', [ComprasController::class, 'autorizadas'])
                ->name('autorizadas');

            // Cargar factura de una orden autorizada
            Route::post('/ordenes/{ordenCompra}/factura', [ComprasController::class, 'subirFactura'])
                ->name('ordenes.factura');

            // Detalle de una requisición
            // Debe ir después de /autorizadas
            Route::get('/{requisicion}', [ComprasController::class, 'show'])
                ->name('show');

            // Guardar cotización
            Route::post('/{requisicion}/cotizaciones', [ComprasController::class, 'storeCotizacion'])
                ->name('cotizaciones.store');
        });

    /*
    |--------------------------------------------------------------------------
    | Proveedores
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:compras|admin')
        ->prefix('proveedores')
        ->name('proveedores.')
        ->group(function () {

            Route::get('/', [ProveedorController::class, 'index'])
                ->name('index');

            Route::get('/nuevo', [ProveedorController::class, 'create'])
                ->name('create');

            Route::post('/', [ProveedorController::class, 'store'])
                ->name('store');
        });

    /*
    |--------------------------------------------------------------------------
    | Cotizaciones
    |--------------------------------------------------------------------------
    |
    | Esta ruta queda fuera del grupo "compras." para conservar
    | el nombre:
    |
    | route('cotizaciones.enviar-autorizacion', $cotizacion)
    |
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/compras/cotizaciones/{cotizacion}/enviar-autorizacion',
        [ComprasController::class, 'enviarAutorizacion']
    )
        ->middleware('role:compras|admin')
        ->name('cotizaciones.enviar-autorizacion');

    /*
    |--------------------------------------------------------------------------
    | Gerencia
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:gerencia|admin')
        ->prefix('gerencia')
        ->name('gerencia.')
        ->group(function () {

            Route::get('/', [GerenciaController::class, 'index'])
                ->name('index');

            Route::get('/exportar-excel', [GerenciaController::class, 'exportarExcel'])
                ->name('exportar-excel');

            Route::post('/{ordenCompra}/autorizar', [GerenciaController::class, 'autorizar'])
                ->name('autorizar');

            Route::post('/{ordenCompra}/rechazar', [GerenciaController::class, 'rechazar'])
                ->name('rechazar');

            Route::post('/servicios/{requisicion}/autorizar', [GerenciaController::class, 'autorizarServicio'])
                ->name('servicios.autorizar');

            Route::post('/servicios/{requisicion}/rechazar', [GerenciaController::class, 'rechazarServicio'])
                ->name('servicios.rechazar');
        });

    /*
    |--------------------------------------------------------------------------
    | Contabilidad
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:contabilidad|admin')
        ->prefix('contabilidad')
        ->name('contabilidad.')
        ->group(function () {

            Route::get('/', [ContabilidadController::class, 'index'])
                ->name('index');

            Route::get('/exportar-excel', [ContabilidadController::class, 'exportarExcel'])
                ->name('exportar-excel');

            Route::post('/{ordenCompra}/marcar-pagada', [ContabilidadController::class, 'marcarPagada'])
                ->name('marcar-pagada');

            Route::post('/servicios/{requisicion}/marcar-pagada', [ContabilidadController::class, 'marcarServicioPagado'])
                ->name('servicios.marcar-pagada');
        });

    /*
    |--------------------------------------------------------------------------
    | Administración de usuarios
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')
        ->prefix('admin/usuarios')
        ->name('admin.usuarios.')
        ->group(function () {

            Route::get('/', [UsuarioController::class, 'index'])
                ->name('index');

            Route::get('/nuevo', [UsuarioController::class, 'create'])
                ->name('create');

            Route::post('/', [UsuarioController::class, 'store'])
                ->name('store');

            Route::post('/{usuario}/toggle-activo', [UsuarioController::class, 'toggleActivo'])
                ->name('toggle-activo');
        });
});
