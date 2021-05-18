<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas de autenticacion got it?
Auth::routes();
Route::get('/migrate', function () {
	Artisan::call('migrate');
	// Artisan::call('db:seed');
	return "Migration";
});


// Ruta para el Landing page
// Route::get('/', 'WelcomeController@welcome');

// Ruta para el Landing page 2
Route::get('/', 'WelcomeController@welcome2');

// Ruta para el contacto del footer
Route::post('contacto', 'ContactoController@store');

// Rutas para las encomiendas
// Route::resource('encomientas', 'EncomiendasController');

// Middleware de autenticación para todos los user
// ***********************************************

Route::group(['middleware' => ['auth']], function () {
	// Rutas para las cartas
	Route::resource('cartas', 'CartaController');
	//tipos de mascotas
	Route::resource('tipos_mascotas', 'TipoMascotasController');
	// Usuarios
	Route::resource('usuarios', 'UsuariosController');
	// Route::get('api.usuarios', 'UsuariosController@reload')->name('api.usuarios');
	// Noticias
	Route::resource('noticias', 'NoticiasController');
	// Quejas y reclamos
	Route::resource('quejas_reclamos', 'QuejasReclamosController');
	Route::post('quejas_estado/{id}', 'QuejasReclamosController@cambiar_estado');
	Route::get('respuesta/{id}', 'QuejasReclamosController@respuesta');
	// Mascotas Ruta
	Route::resource('mascotas', 'MascotasController');
	// Proveedores
	Route::resource('proveedores', 'ProveedorController');
	// Novedades para unidad
	Route::resource('novedades', 'NovedadController');
	// Cuotas administrativas
	// ************************************************************
	// Cuotas extraodinarias
	Route::resource('cuota_ext_ord', 'CuotaExtOrdinariaController');
	// Tabla de intereses
	Route::resource('tabla_intereses', 'TablaInteresesController');
	// Multas
	Route::resource('multas', 'MultasController');
	// Otros cobros
	Route::resource('otros_cobros', 'OtrosCobrosController');
	// Ejecucion presupuestal total
	Route::resource('ejecucion_pre_total', 'EjecucionPreTotalController');
	// Tipos de ejecucion presupuestal
	Route::resource('tipo_ejecucion_pre', 'TipoEjecucionPreController');
	// Ejecucion presupuestal individual
	Route::resource('ejecucion_pre_individual', 'EjecucionPreIndividual');
	// Terminos y condiciones
	Route::post('terminos', 'UsuariosController@terminos');
	// Activos Fijos
	Route::resource('inventario', 'InventarioController');
	// Consecutivos
	Route::resource('consecutivos', 'ConsecutivosController');
	// Cuotas Admon
	Route::resource('cuota_admon', 'CuotaAdmonController');
	// Route::post('verCuotas/{idUnidad}', 'CuotaAdmonController@verCuentas');
	// Residentes
	Route::resource('residentes', 'ResidentesController');
	// Vehículos
	Route::resource('vehiculos', 'VehiculoController');
	// visitantes
	Route::resource('visitantes', 'VisitanteController');
	// Empleados
	Route::resource('empleados', 'EmpleadoController');
	//empleados por conjunto
	Route::resource('empleados_conjunto', 'EmpleadosConjuntoController');
	// Conjuntos a Usuarios
	Route::resource('conjuntos_usuarios', 'ConjuntosUsuariosController');
	Route::get('conjuntos_usuarios_all/{id}', 'ConjuntosUsuariosController@mostrar_todos');

	//Módulo documental
	Route::resource('documentos', 'DocumentosController');

	// Zonas comunes
	Route::resource('zonas_comunes', 'ZonasComunesController');
	// Reservas
	Route::resource('reservas', 'ReservasController');
	Route::get('allReservas/{zona_comun}', 'ReservasController@all');
	Route::get('reservasZonaComun/{zona_comun}', 'ReservasController@index');

	//evidencias
	Route::resource('evidencias', 'EvidenciaController');
});

// Middleware de autenticación para el dueño
// *****************************************

Route::group(['middleware' => ['owner']], function () {
	Route::get('owner', 'HomeController@owner');
	// Conjuntos
	Route::resource('conjuntos', 'ConjuntosController');
	// Tipos de documentos
	Route::resource('tipo_documentos', 'TipoDocumentoController');
	// Desactivar usuarios (Su estado)
	Route::post('disusuarios/{id}', 'UsuariosController@disabled');
	// Contactos por el index
	Route::get('contactos', 'ContactoController@index');
	Route::post('contacto/{id}', 'ContactoController@destroy');
	Route::get('contactos/{id}', 'ContactoController@show');
	// Tipos de conjuntos
	Route::resource('tipo_conjunto', 'TipoConjuntoController');
	Route::get('api.tipo_conjunto', 'TipoConjuntoController@reload')->name('api.tipo_conjunto');

	//Rutas para las datatables
	Route::get('api.conjuntos', 'ConjuntosController@datatables');
	Route::get('api.usuarios_owner', 'UsuariosController@datatables');
	Route::get('api.tabla_interes_owner', 'TablaInteresesController@datatables');
	Route::get('api.contactos', 'ContactoController@datatables');

	//reglamento
	Route::post('reglamento.owner.show', 'ReglamentoController@show');
	Route::post('reglamento.owner.add', 'ReglamentoController@store');
	Route::post('reglamento.owner.edit/{reglamento}', 'ReglamentoController@update');

	//Liquidador
	Route::post('editarVariable', 'LiquidadorController@editarVariable');
});

// Middleware de autenticación para el admin del conjunto
// ******************************************************

Route::group(['middleware' => ['admin']], function () {

	//reglamento
	Route::post('reglamento.show', 'ReglamentoController@show');
	Route::post('reglamento.add', 'ReglamentoController@store');
	Route::post('reglamento.edit/{reglamento}', 'ReglamentoController@update');

	//contraseña de envio de email
	Route::get('passwordEmail', 'ConjuntosController@passwordConjunto');
	Route::post('chagePasswordEmail', 'ConjuntosController@changePassword');
	Route::post('sendEmailPrueba', 'ConjuntosController@sendEmailPrueba');

	// unidades
	Route::resource('unidades', 'UnidadController');
	Route::get('unidadestipo/{tipo}', 'UnidadController@indexTipo');
	Route::get('addUnidad/{tipo}', 'UnidadController@loadAddForTipo');
	Route::get('asignarPropietario/{unidad}/{id}', 'UnidadController@setPropietario');

	//carga masiva unidades

	//Ir ala vista de carga masiva
	Route::get('cargaMasivaUnidad/{tipo}', 'ArchivoCargaMasivaController@index');
	//ruta para generar la plantilla 
	Route::get('generarPlantillaMasivoUnidades/{tipoUnidad}', 'ArchivoCargaMasivaController@downloadExcel');
	//ruta para comenzar la carga masiva
	Route::post('unidades_csv_post', 'ArchivoCargaMasivaController@unidades_csv_post');	
	// ruta para ver el modal con la tabla de errores
	Route::get('errores/{archivo}', 'ArchivoCargaMasivaController@showErrors');

	// Ruta para el dashboard
	Route::get('admin', 'HomeController@admin');
	// Cambio de conjunto
	Route::post('selecciono/{id}', 'SeleccionController@seleccion');
	//tipos de divisiones
	Route::resource('tipo_divisiones', 'TipoDivisionController');
	// Bloques controller
	Route::resource('divisiones', 'DivisionesController');
	// Apartamentos Controller
	Route::resource('tipo_unidad', 'Tipo_unidadController');
	// Prueba de 22/01/2019
	Route::get('tipo_unidad_residentes/{id}', 'Tipo_unidadController@showFormResidentes');

	// Descargar el excel base
	Route::get('download_users', 'UsuariosController@download');
	// Descargar el excel base
	Route::get('download_presupuesto', 'EjecucionPreTotalController@download');
	// Cargue de usuarios masivo
	Route::get('users_csv', 'UsuariosController@usuarios_csv');
	Route::post('users_csv_post', 'UsuariosController@usuarios_csv_post');
	// Cambio de logo
	Route::post('logo_conjunto_store', 'ConjuntosController@LogoConjuntoStore');
	// Eliminar el logo
	Route::post('logo_conjunto_delete', 'ConjuntosController@LogoConjuntoDelete');

	//Mantenimientos
	Route::resource('mantenimientos', 'MantenimientoController');
	Route::post('mantenimientoRealizado/{mantenimiento}', 'MantenimientoController@realizado');

	//novedades conjunto
	Route::resource('novedadesConjunto', 'NovedadesConjuntoController');


	//Carga masiva de unidades
	Route::post('archivos', 'ArchivoCargaMasivaController@store');
	Route::post('estadoProcesoCargaArchivo/{archivoCargaMasiva}', 'ArchivoCargaMasivaController@show');
	Route::delete('archivos/{archivoCargaMasiva}', 'ArchivoCargaMasivaController@destroy');


	/**************************************** */
	//Módulo financiero
	/**************************************** */
	Route::post('calcularCuotaAdministracion', 'EjecucionPreIndividual@calcularCuotaAdministracion');
	Route::post('detallePresupuestoTotal/{id}', 'EjecucionPreTotalController@detalle');
	Route::post('unidadesExcluidasPresupuesto/{id}', 'EjecucionPreIndividual@excluidas');
	Route::post('cargarPresupuestoCSV', 'EjecucionPreIndividual@cargarCSV');

	Route::post('detalleCuotaAdministracion/{id}', 'CuotaAdmonController@verDetalle');
	Route::post('detalleCuotaExtraordinaria/{id}', 'CuotaExtOrdinariaController@verDetalle');
	Route::get('detalleCuotaExtraordinariaPdf/{cuota}', 'CuotaExtOrdinariaController@pdfNoPago');

	Route::resource('cuentasBancarias', 'CuentaBancariaController');
	Route::resource('descuentos', 'DescuentoController');
	Route::post('unidadesPropietario/{propietario}', 'DescuentoController@unidades');
	Route::post('calcularValorDescuento', 'DescuentoController@calcularValor');
	Route::post('interesUnidad/{unidad}', 'UnidadController@interes');

	Route::resource('egresos', 'EgresoController');
	Route::post('agregarEgresos', 'EgresoController@agregar');
	Route::post('buscarEgreso', 'EgresoController@buscar');
	Route::post('listarEgresos', 'EgresoController@listar');
	Route::get('egresosPdf/{egreso}', 'EgresoController@pdf');
	Route::post('anularEgreso/{egreso}', 'EgresoController@anular');
	Route::get('descargarEgresos', 'EgresoController@downloadZip');

	//Cuentas de cobro
	Route::get('listarCuentaCobro', 'CuentasCobroController@index');
	Route::get('generarCuentaCobro', 'CuentasCobroController@indexGenerar');
	Route::post('visualizarCuentasCobro', 'CuentasCobroController@visualizar');
	Route::post('eliminarCuota', 'CuentasCobroController@eliminarCuota');
	Route::post('editarCuota', 'CuentasCobroController@editarCuota');
	Route::post('guardarCuentasCobro', 'CuentasCobroController@guardar');
	Route::get('pdfCuentasCobros/{cuenta}', 'CuentasCobroController@pdf');
	Route::get('guardarDescargarCuentasCobro', 'CuentasCobroController@guardarDescargar');
	Route::post('ultimaCuentaCobro/{propietario}', 'CuentasCobroController@ultima');
	Route::post('previsualizarCuenta/{consecutivo}/{propietario}', 'CuentasCobroController@cuentaPropietario');
	Route::post('detalleCuenta/{detalle}', 'CuentasCobroController@verDetalle');
	Route::post('detallesCuenta/{cuenta}', 'CuentasCobroController@detalles');
	Route::post('searchCuentaCobro', 'CuentasCobroController@buscarPorConsecutivo');
	Route::post('listarCuentasPropietario/{propietario}', 'CuentasCobroController@buscarPorPropietario');
	Route::get('descargarCuentas', 'CuentasCobroController@descargar');
	// Route::post('pagarCuenta','RecaudoController')

	Route::get('paz_salvo', 'PazSalvoController@pazSalvo');
	Route::post('pdfPazSalvo', 'PazSalvoController@pazSalvoPdf');
	Route::post('cuerpoPazSalvo/{propietario}', 'PazSalvoController@cuerpoCarta');
	Route::get('paz_salvoDownload', 'PazSalvoController@pdfPazSalvo');
	Route::get('en_mora', 'CertificadoMoraController@enMora');
	Route::get('en_moraDownload', 'CertificadoMoraController@pdfEnMora');
	Route::post('cuotasMora/{propietario}', 'CertificadoMoraController@cuotas');
	Route::post('certificadoMora', 'CertificadoMoraController@certificado');
	Route::get('certificadoMora', 'CuentasCobroController@certificadoMora');

	//gestion de recaudos
	Route::resource('recaudos', 'RecaudoController');
	Route::get('pdfPago/{recaudo}', 'RecaudoController@pdf');
	Route::get('pdfPagoC/{consecutivo}', 'RecaudoController@pdfC');
	Route::get('saldosFavor', 'RecaudoController@saldosFavor');
	Route::get('saldosFavorPdf', 'RecaudoController@saldosFavorPdf');
	Route::get('listarRecaudo', 'RecaudoController@listarRecaudos');
	Route::post('consultarRecaudos', 'RecaudoController@consultarRecaudos');
	Route::get('descargarRecaudos', 'RecaudoController@descargar');
	Route::post('recaudosProntoPago', 'RecaudoController@saveProntoPago');


	//carteras
	Route::get('carteras', 'CarteraController@index');
	Route::post('consultarCartera', 'CarteraController@consultarCartera');


	//flujo de efectivo
	Route::get('flujo_efectivo', 'FlujoEfectivoController@index');
	Route::post('addFlujo', 'FlujoEfectivoController@add');
	Route::post('deleteFlujo/{id}', 'FlujoEfectivoController@delete');
	Route::post('showFlujo/{id}', 'FlujoEfectivoController@show');
	Route::post('editFlujo', 'FlujoEfectivoController@edit');
	Route::get('descargarIngresoEfectivo/{flujo}', 'FlujoEfectivoController@dowload');


	//anulación
	Route::get('anularCuentaCobro/{cuenta}', 'anulacionController@anularCuentaCobro');
	Route::get('anularRecaudo/{recaudo}', 'anulacionController@anularRecaudo');
	Route::post('loadProceso/{cuenta}', 'anulacionController@loadProceso');
	Route::post('anularModificarCuenta/{cuenta}', 'anulacionController@newCuenta');
	Route::post('anularModificarRecaudo/{recaudo}', 'anulacionController@newRecaudo');
	Route::post('anularAgregarRecaudo/{cuenta}', 'anulacionController@addRecaudo');
	Route::post('anularLoadCuenta', 'anulacionController@cuenta');
	Route::post('anularLoadRecaudo/{recaudo}', 'anulacionController@recaudo');
	Route::post('anularLoadRecaudoAdd/{cuenta}', 'anulacionController@recaudoAdd');
	Route::post('reemplazarCuenta/{cuentaA}', 'anulacionController@reemplazarCuenta');
	Route::post('reemplazarRecaudo/{recaudoA}', 'anulacionController@reemplazarRecaudo');
	Route::post('addRecaudo', 'anulacionController@add_Recaudo');
	Route::post('anularPagosRecaudo/{recaudo}', 'anulacionController@anularPagosRecaudo');
	Route::post('restablecerPagosRecaudo/{recaudo}', 'anulacionController@restablecerPagosRecaudo');
	// Route::post('deshacerAnulacionCuentaCobro/{cuenta}','anulacionController@deshacerAnulacionCuentaCobro');
	// Route::post('deshacerAnulacionRecaudo/{recaudo}','anulacionController@deshacerAnulacionRecaudo');
	//anulación pronto pago
	Route::post('addProntoPago', 'anulacionController@addProntoPago');
	Route::post('reemplazarProntoPago/{recaudoA}', 'anulacionController@reemplazarProntoPago');

	//saldos iniciales
	Route::resource('saldos_iniciales', 'SaldoInicialController');
	Route::get('masivo_saldos', 'SaldoInicialController@viewMasivo');
	Route::get('download_base_saldos', 'SaldoInicialController@download');
	Route::post('masivo_saldos', 'SaldoInicialController@masivo');

	//Unidades
	/*************************************/

	Route::post('exportarResidentes', 'ResidentesController@download');

	//inactivar
	Route::post('empleados/inactivar/{empleado}', 'EmpleadoController@inactivar');
	Route::post('retiroEmpleadoConjunto/{empleado}', 'EmpleadosConjuntoController@inactivar');
	Route::post('visitantes/inactivar/{visitante}', 'VisitanteController@inactivar');
	Route::post('residentes/inactivar/{residente}', 'ResidentesController@inactivar');
	Route::post('mascotas/inactivar/{mascota}', 'MascotasController@inactivar');
	Route::post('vehiculos/inactivar/{vehiculo}', 'VehiculoController@inactivar');

	//generar pdf
	Route::post('unidades/pdf/{tipo}/{unidad}', 'UnidadController@datosPdf');


	//aceptar y rechazar reservas
	Route::post('aceptarReserva/{reserva}', 'ReservasController@aceptar');
	Route::post('rechazarReserva/{reserva}', 'ReservasController@rechazar');
	Route::get('listaReservas', 'ReservasController@lista');

	//multas
	Route::get('downloadMultaFile/{multa}', 'MultasController@download');

	//mantenimientos
	Route::get('downloadMantenimiento/{mantenimiento}', 'MantenimientoController@download');


	//Rutas para datatables
	//módulo administrativo
	Route::get('api.noticias_admin', 'NoticiasController@datatables');
	Route::get('api.reclamos', 'QuejasReclamosController@datatables');
	Route::get('api.novedades_conjunto', 'NovedadesConjuntoController@datatables');
	Route::get('api.evidencias', 'EvidenciaController@datatables');
	Route::get('api.divisiones', 'DivisionesController@datatables');
	Route::get('api.tipos_unidad', 'Tipo_unidadController@datatables');
	Route::get('api.usuarios', 'UsuariosController@datatables');
	Route::get('api.unidades.admin/{tipo}', 'UnidadController@datatables');
	Route::get('api.cartas.admin', 'CartaController@datatables');
	Route::get('api.residentes.admin', 'ResidentesController@datatables');
	Route::get('api.mascotas.admin', 'MascotasController@datatables');
	Route::get('api.vehiculos.admin', 'VehiculoController@datatables');
	Route::get('api.empleados_unidad.admin', 'EmpleadoController@datatables');
	Route::get('api.empleados_conjunto.admin', 'EmpleadosConjuntoController@datatables');
	Route::get('api.visitantes.admin', 'VisitanteController@datatables');
	Route::get('api.proveedores', 'ProveedorController@datatables');
	Route::get('api.reservas', 'ReservasController@datatables');
	Route::get('api.mantenimientos', 'MantenimientoController@datatables');
	Route::get('api.zonas_comunes.admin', 'ZonasComunesController@datatables');
	Route::get('api.inventarios', 'InventarioController@datatables');
	Route::get('api.archivos_masivos/{tipo}', 'ArchivoCargaMasivaController@datatables');
	//módulo financiero
	Route::get('api.presupuesto_total.admin', 'EjecucionPreTotalController@datatables');
	Route::get('api.tipo_presupuesto.admin', 'TipoEjecucionPreController@datatables');
	Route::get('api.presupuesto_individual.admin', 'EjecucionPreIndividual@datatables');
	Route::get('api.consecutivos.admin', 'ConsecutivosController@datatables');
	Route::get('api.administrativas.admin', 'CuotaAdmonController@datatables');
	Route::get('api.extraordinarias.admin', 'CuotaExtOrdinariaController@datatables');
	Route::get('api.otros.admin', 'OtrosCobrosController@datatables');
	Route::get('api.multas.admin', 'MultasController@datatables');
	Route::get('api.flujos.admin', 'FlujoEfectivoController@datatables');
	Route::post('api.saldo_actual.admin', 'FlujoEfectivoController@saldoActual');
	Route::get('api.descuentos.admin', 'DescuentoController@datatables');
	Route::get('api.saldos.admin', 'SaldoInicialController@datatables');
	Route::get('api.liquidaciones.admin/{empleado}', 'LiquidacionController@listar');


	Route::get('api.intereses.admin', 'TablaInteresesController@datatables');

	//módulo documental
	Route::get('api.documentos.admin', 'DocumentosController@datatables');

	//Exportar
	/*******************************************/
	Route::get('exportar', 'exportarController@index');
	Route::post('downloadSeveral', 'exportarController@downloadSeveral');
	Route::get('pruebaExportar', 'exportarController@probarPDF');


	//Liquidador de Nómina
	/*****************************************/
	Route::get('informacionLiquidador/{empleado}', 'LiquidadorController@informacion');
	Route::get('liquidador/{empleado}', 'LiquidadorController@index');
	Route::post('liquidadorJornadas', 'LiquidadorController@getJornadas');
	Route::post('cargarLiquidacion', 'LiquidadorController@liquidacion');
	// Route::post('cargarPrima','LiquidadorController@prima');
	// Route::post('cargarCesantia','LiquidadorController@cesantia');
	Route::post('cargarPrestaciones', 'LiquidadorController@prestaciones');
	Route::post('guardarPrestaciones', 'LiquidacionController@prestaciones');
	Route::get('generarLiquidacion/{empleado}', 'LiquidadorController@vistaGenerar');
	Route::get('generarLiquidacionPrestaciones/{empleado}', 'LiquidadorController@vistaPrestaciones');
	// Route::get('generarLiquidacionCesantia/{empleado}','LiquidadorController@vistaCesantia');

	//Liquidacion
	Route::resource('liquidacion', 'LiquidacionController');
	Route::get('listaLiquidaciones/{empleado}', 'LiquidacionController@vistaListar');
	Route::get('liquidacionesDownload/{empleado}', 'LiquidacionController@download');


	Route::get('jornadas/{empleado}', 'JornadasController@index');
	Route::post('jornadasStore', 'JornadasController@store');
	Route::post('deleteJornada', 'JornadasController@delete');
	Route::get('jornada/{jornada}', 'JornadasController@show');
	Route::post('updateJornada', 'JornadasController@update');
	Route::post('pdfJornadas', 'JornadasController@pdf');
	Route::get('jornadasZip/{empleado}', 'JornadasController@downloadZip');
});

// Middleware de autenticación para el dueño de apto del conjunto
// **************************************************************

Route::group(['middleware' => ['dueno']], function () {
	Route::get('dueno', 'HomeController@dueno');
	// Cambio de conjunto
	Route::post('selecciono_user/{id}', 'SeleccionController@seleccion_user');

	//Cartera
	Route::get('miCartera', 'CarteraController@verMiCartera');
	Route::get('pdfRecaudo/{recaudo}', 'RecaudoController@pdf');
	Route::get('pdfRecaudoC/{consecutivo}', 'RecaudoController@pdfC');


	//listas cuentas de cobro
	Route::get('listaCuentasCobroDueno', 'CuentasCobroController@index');
	// Route::get('pdfCuentaCobroDueno/{id}', 'CuentasCobroController@generarPDF');
	// Route::post('consultarCobroDueno/{cobroId}', 'CuentasCobroController@verDetalle');
	Route::get('pdfCuentasCobro/{cuenta}', 'CuentasCobroController@pdf');


	//unidades mostrar
	Route::get('misUnidades', 'UnidadController@index');
	Route::get('unidadesPropietario/{unidade}', 'UnidadController@show');

	//recibos de pago
	Route::get('misRecaudos', 'RecaudoController@index');

	//eliminar y rechazar reservas
	Route::post('eliminarReserva/{reserva}', 'ReservasController@destroy');
	Route::post('rechazarReservaPropietario/{reserva}', 'ReservasController@rechazar');

	//multas
	Route::get('downloadMultaFilePropietario/{multa}', 'MultasController@download');

	//Rutas para las datatables
	Route::get('api.quejas.dueno', 'QuejasReclamosController@datatables');
	Route::get('api.unidades.dueno', 'UnidadController@datatables');
	Route::get('api.zonas_comunes.dueno', 'ZonasComunesController@datatables');
	Route::get('api.multas.dueno', 'MultasController@datatables');
	Route::get('api.intereses.dueno', 'TablaInteresesController@datatables');
	Route::get('api.micartera.dueno', 'CarteraController@miCarteraDatatable');
	Route::get('api.cuentas.dueno', 'CuentasCobroController@datatablesDueno');
	Route::get('api.recibos.dueno', 'RecaudoController@datatablesDueno');
	Route::get('api.cartas.dueno', 'CartaController@datatables');
	Route::get('api.evidencias.dueno', 'EvidenciaController@datatables');
	Route::get('api.documentos.dueno', 'DocumentosController@datatables');
});


// Middleware de autenticación para el portero
// ********************************************
Route::group(['middleware' => ['porteria']], function () {
	Route::get('porteria', 'HomeController@porteria');
	//unidades mostrar
	Route::get('unidades_porteria', 'UnidadController@index');
	Route::get('unidadesPorteria/{unidade}', 'UnidadController@show');
	Route::get('unidadesTipoPorteria/{tipo}', 'UnidadController@indexTipo');

	//Rutas para las datatables
	Route::get('api.cartas.porteria', 'CartaController@datatables');
	Route::get('api.zonas.porteria', 'ZonasComunesController@datatables');
	Route::get('api.unidades.porteria/{tipo}', 'UnidadController@datatables');
	Route::get('api.residentes.porteria', 'ResidentesController@datatables');
	Route::get('api.mascotas.porteria', 'MascotasController@datatables');
	Route::get('api.vehiculos.porteria', 'VehiculoController@datatables');
	Route::get('api.empleados_unidad.porteria', 'EmpleadoController@datatables');
	Route::get('api.empleados_conjunto.porteria', 'EmpleadosConjuntoController@datatables');
	Route::get('api.visitantes.porteria', 'VisitanteController@datatables');
	Route::get('api.evidencias.porteria', 'EvidenciaController@datatables');
	Route::get('api.documentos.porteria', 'DocumentosController@datatables');
});

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('tipo_unidad_get', 'WelcomeController@fetch');
