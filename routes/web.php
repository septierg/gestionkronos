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

Route::get('/', function () {
    return view('index');
});
Route::get('/notre-entreprise.php', function () {
    return view('notre-entreprise');
});
Route::get('/services.php', function () {
    return view('services');
});
Route::get('/articles.php', function () {
    return view('article');
});
Route::get('/article1.php', function () {
    return view('article1');
});
Route::get('/article2.php', function () {
    return view('article2');
});
Route::get('/contact.php', function () {
    return view('contact');
});



Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');



Route::get('/home', 'HomeController@index');

//ADMIN SECTION
Route::group(['middleware' =>'admin'], function (){

    //ADMIN
    Route::get('admin/pdfConverter', ['uses'=> 'AdminController@Pdfdownload']);
    Route::get('admin/jsonConverter', ['uses'=> 'AdminController@jsonDownload']);


    Route::resource('admin', 'AdminController', ['except' => ['index']]);
    Route::resource('admin','AdminController');
    Route::get('admin/index', ['uses'=> 'AdminController@index']);
    Route::get('admin', ['as' => 'admin.index', 'uses' => 'AdminController@index']);


    //EMPLOYE SECTION

    //Route::group(['middleware' =>'admin_employe'], function (){


    //PRETABC
    Route::get('/pretabc/non-traité', ['uses'=> 'PretabcPretController@index']);
    Route::get('/pretabc/accepté', ['uses'=> 'PretabcPretController@accepte']);
    Route::get('/pretabc/refusé', ['uses'=> 'PretabcPretController@refuse']);
    Route::get('/pretabc/commencé', ['uses'=> 'PretabcPretController@commence']);
    Route::get('/pretabc/tout', ['uses'=> 'PretabcPretController@tout']);

    Route::get('/pretabc/pdfConverter', ['uses'=> 'PretabcPretController@Pdfdownload']);
    Route::get('pretabc/jsonConverter', ['uses'=> 'PretabcPretController@jsonDownload']);
    Route::get('pretabc/getResponsable', ['uses'=> 'PretabcPretController@getResponsable']);
    Route::get('pretabc/getEtat', ['uses'=> 'PretabcPretController@getEtat']);
    Route::get('pretabc/getStatut', ['uses'=> 'PretabcPretController@getStatut']);
    Route::get('pretabc/creditBook', ['uses'=> 'PretabcPretController@creditBook']);
    Route::get('pretabc/searchAjax','PretabcPretController@searchAjax');
    Route::get('pretabc/flinks', ['uses'=> 'PretabcPretController@flinksdownload']);

    //EMAIL SECTION
    Route::get('pretabc/email_Rappel', 'PretabcPretController@email_Rappel');
    Route::get('kreditpret/email_Rappel', 'KreditpretPretController@email_Rappel');

    Route::get('email/{id}',['uses'=> 'PretabcPretController@email']);

    Route::get('pret_email/{id}', ['as' => 'pret_email','uses'=> 'PretabcPretController@email']);
    Route::get('pret_contrat/{id}', ['as' => 'pret_contrat','uses'=> 'PretabcPretController@contrat']);

    Route::get('membre_email/{id}', ['as' => 'membre_email','uses'=> 'PretabcMembreController@email']);
    Route::get('membre_contrat/{id}', ['as' => 'membre_contrat','uses'=> 'PretabcMembreController@contrat']);

    Route::get('kp_membre_email/{id}', ['as' => 'kp_membre_email','uses'=> 'KreditpretMembreController@email']);
    Route::get('kp_membre_contrat/{id}', ['as' => 'kp_membre_contrat','uses'=> 'KreditpretMembreController@contrat']);

    Route::get('kp_pret_email/{id}', ['as' => 'kp_pret_email','uses'=> 'KreditpretPretController@email']);
    Route::get('kp_pret_contrat/{id}', ['as' => 'kp_pret_contrat','uses'=> 'KreditpretPretController@contrat']);
    //FIN EMAIL SECTION

    //RESOURCE CONTROLER
    Route::resource('employes', 'EmployesController');


    Route::get('pretabc/index', ['uses'=> 'PretabcPretController@index']);
    Route::get('pretabc', ['as' => 'pretabc.index', 'uses' => 'PretabcPretController@index']);

    Route::resource('demande', 'DemandeController');
    Route::resource('pretabc', 'PretabcPretController', ['except' => ['index']]);
    Route::resource('pretabc','PretabcPretController');

    Route::resource('membre','PretabcMembreController');

    Route::resource('membres','MembreController',['except' => ['edit']]);

    Route::resource('kreditpret-membre', 'KreditpretMembreController');


    //KREDITPRET
    Route::get('kreditpret/index',['uses'=> 'KreditpretPretController@index']);
    Route::get('kreditpret', ['as' => 'kreditpret.index', 'uses' => 'KreditpretPretController@index']);

    Route::get('kreditpret/jsonConverter', ['uses'=> 'KreditpretPretController@jsonDownload_kreditpret']);
    Route::get('kreditpret/pdfConverter', ['uses'=> 'KreditpretPretController@Pdfdownload_kreditpret']);
    Route::get('kreditpret/cb',['uses'=> 'KreditpretPretController@cb_Kreditpret']);
    Route::get('kreditpret/statut',['uses'=> 'KreditpretPretController@statut']);
    Route::get('kreditpret/responsable', ['uses'=> 'KreditpretPretController@responsable']);
    Route::get('kreditpret/etat', ['uses'=> 'KreditpretPretController@etat']);
    Route::get('kreditpret/flinks', ['uses'=> 'KreditpretPretController@flinksdownload']);


    Route::resource('kreditpret','KreditpretPretController');

    //});


    Route::resource('task', 'TaskAdminController');

    Route::resource('company', 'CompanyController');
});

