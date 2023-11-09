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

Route::get('/session', function(){dd(session()->all());});

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/', 'HomeController@index')->name('home');

Route::resource('user', UserController::class)->only(['show', 'edit']);
Route::put('/user/{user}', 'UserController@update_user');

Route::get('/workshop', 'WorkshopController@index');

Route::resource('service', ServiceController::class)->except(['show', 'store', 'update', 'destroy']);
Route::post('/service', 'ServiceController@add_service');
Route::put('/service/{service}', 'ServiceController@update_service');
Route::delete('/service/{service}', 'ServiceController@delete_service');

Route::resource('sparepart', SparepartController::class)->except(['create', 'store', 'update', 'destroy']);
Route::post('/sparepart/search', 'SparepartController@search');
Route::put('/sparepart/{sparepart}', 'SparepartController@update_sparepart');
Route::delete('/sparepart/{sparepart}', 'SparepartController@delete_sparepart');

Route::resource('report', ReportController::class)->only(['index']);
Route::post('/report', 'ReportController@add_report');
Route::put('/report/{report}', 'ReportController@update_report');
Route::delete('/report/{report}', 'ReportController@delete_report');

Route::resource('mechanic', MechanicController::class)->only(['index', 'create']);
Route::post('/mechanic', 'MechanicController@add_mechanic');
Route::delete('/mechanic/{user}', 'MechanicController@destroy');
Route::put('/mechanic/{user}', 'MechanicController@update');
Route::get('/mechanic/{user}', 'MechanicController@show');
Route::get('/mechanic/{user}/edit', 'MechanicController@edit');
Route::put('/mechanic/{user}', 'MechanicController@update_mechanic');
Route::delete('/mechanic/{user}', 'MechanicController@delete_mechanic');

Route::resource('salary', SalaryController::class)->except(['create', 'store', 'show', 'update', 'destroy']);
Route::post('/salary/filter', 'SalaryController@filter');
Route::post('/salary/untaken_salary', 'SalaryController@get_untaken_salary');
Route::post('/salary/is_taken', 'SalaryController@is_taken');
Route::post('/salary/id', 'SalaryController@get_salary_id');
Route::post('/salary/take', 'SalaryController@take');
Route::get('/salary/{transaction}', 'SalaryController@show');
Route::put('/salary/{salary}', 'SalaryController@update');
Route::post('/salary/cut', 'SalaryController@cut');
Route::get('/salary/{salary}/pdf', 'SalaryController@pdf_export');
Route::put('/salary/{salary}', 'SalaryController@update_salary');

Route::resource('loan', LoanController::class)->except(['show', 'store', 'update', 'destroy']);
Route::post('/loan/filter', 'LoanController@filter');
Route::get('/loan/pay/{loan}', 'LoanController@pay');
Route::post('/loan', 'LoanController@add_loan');
Route::put('/loan/{loan}', 'LoanController@update_loan');
Route::delete('/loan/{loan}', 'LoanController@delete_loan');

Route::get('/wholesale/get_item', 'WholesaleController@get_item');
Route::get('/wholesale/grand_total', 'WholesaleController@grand_total');
Route::post('/wholesale/add_item', 'WholesaleController@add_item');
Route::post('/wholesale/delete_item', 'WholesaleController@delete_item');
Route::post('/wholesale/upload', 'WholesaleController@upload');
Route::post('/wholesale/clear_items', 'WholesaleController@clear_items');
Route::post('/wholesale', 'WholesaleController@add_wholesale');
Route::resource('wholesale', WholesaleController::class)->except(['edit', 'store', 'update', 'destroy']);

Route::get('/transaction/grand_total', 'TransactionController@grand_total');
Route::get('/transaction/get_sparepart', 'TransactionController@get_sparepart');
Route::get('/transaction/sparepart_subtotal', 'TransactionController@sparepart_subtotal');
Route::post('/transaction/add_sparepart', 'TransactionController@add_sparepart');
Route::post('/transaction/inc_sparepart', 'TransactionController@inc_sparepart');
Route::post('/transaction/dec_sparepart', 'TransactionController@dec_sparepart');
Route::post('/transaction/delete_sparepart', 'TransactionController@delete_sparepart');
Route::post('/transaction/clear_spareparts', 'TransactionController@clear_spareparts');
Route::get('/transaction/get_service', 'TransactionController@get_service');
Route::get('/transaction/service_subtotal', 'TransactionController@service_subtotal');
Route::post('/transaction/add_service', 'TransactionController@add_service');
Route::post('/transaction/delete_service', 'TransactionController@delete_service');
Route::post('/transaction/clear_services', 'TransactionController@clear_services');
Route::get('/transaction/{transaction}/pdf', 'TransactionController@pdf_export');
Route::post('/transaction', 'TransactionController@add_transaction');
Route::put('/transaction/{transaction}', 'TransactionController@update_transaction');
Route::delete('/transaction/{transaction}', 'TransactionController@delete_transaction');
Route::resource('transaction', TransactionController::class)->except(['store', 'update', 'destroy']);

Route::resource('customer', CustomerController::class)->except(['show', 'store', 'update', 'destroy']);
Route::post('/customer', 'CustomerController@add_customer');
Route::put('/customer/{customer}', 'CustomerController@update_customer');
Route::delete('/customer/{customer}', 'CustomerController@delete_customer');

Route::get('/get_notification', 'NotificationController@show');
Route::get('/notification/{notification}/read', 'NotificationController@read');
Route::get('/notification/read_all', 'NotificationController@read_all');

Route::get('/test/add_report', 'PengujianController@add_report');
Route::get('/test/update_sparepart', 'PengujianController@update_sparepart');
Route::get('/test/update_mechanic', 'PengujianController@update_mechanic');
Route::get('/test/add_transaction', 'PengujianController@add_transaction');
Route::get('/test/cut_salary', 'PengujianController@cut_salary');
