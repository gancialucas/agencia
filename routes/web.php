<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

/*
    Route es una clase y los dos puntos es llamar a un método estático
        Route::get('petición', acción);
*/

/*Route::get('/', function () { // "function()" es una función anonima declarada como parámetro, se lo denomina -clojure-
    return view('welcome');
});*/

// Retornar una vista
Route::get('/inicio', function () {
    $nombre = "Lucas";
    $apellido = "Gancia";
    $dni = 1212;
    $numero = 7;

    // Pasamos datos por medio de arrays asociativos, llave contiene 1er parametro key = 'nombre',  2do parametro el valor
    return view('inicio',
        [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'numero' => $numero
        ]
    );
});

// Retornar SOLO vista "formulario". Se trabaja con ~petición y vista~
Route::view('/formulario', 'formulario');

// Para trabajar con este mismo form debemos usar Route::post()
Route::post('/procesa', function() {
    // Con PHP vanilla
        // $nombre = $_POST['nombre'];

    // Con framework Laravel
    $nombre = request()->nombre; // request() es un clase/se convierte en función de Laravel para capturar datos ~ se puede trabajar GET, POST, COOKIES, SESSION, FILES
    return $nombre;
});

// Prueba de conexión con la base de datos
Route::view('/testConnection', 'testConnection');

/****** CRUD DE REGIONES ******/
// Mostramos los registros de la base de datos

/*
    métodos de petición:
        head - (sin cuerpo - no crea, ni modifica, ni elimina)
        get - (reporte - no crea, ni modifica, ni elimina)
        post - (crear un recurso)
        put - (modifica un recurso - todo)
        patch - (modifica un recurso - parcial)
        delete - (elimina un recurso)
*/

Route::get('/', function() {
    $regiones = DB::select('SELECT idRegion, regNombre FROM regiones');

    // "Dump and Dye", muestra el crudo y mata el resto de los procesos ~como print_r()~
        // dd($regiones);

    // Obtenemos listado de regiones
    return view('regiones', [
        'regiones' => $regiones
    ]);
});

Route::get('/region/create', function () {
    return view('regionCreate');
});

/****** INSERT ******/
Route::post('/region/store', function(){
    $regNombre = request()->regNombre;

    // Insertar datos en tabla "regiones"
    try {
        /*
            Forma para hacer un inster sin tener que hacer el bind
            DB::insert (
                'INSERT INTO
                    regiones (regNombres)
                VALUES
                    (:a, :b, :c)',
                    [$a, $b, $c]
            );
        */

        DB::insert (
            'INSERT INTO
                regiones (regNombre)
            VALUES
                (:regNombre)',
                [$regNombre]
        );

        /*
            Hacemos ~Flashing~
                Ver manual de Laravel oficial.
            POR OTRO LADO:
                Metodo "with" genera una/varias variables de sesion que se van a mostrar 1 vez.
        */
        return redirect('/')->with
                ([
                    'mensaje' => 'Región: "'.$regNombre.'" agregada correctamente! 👌🏻',
                    'css' => 'success'
                ]);
    } catch (\Throwable $th) {
        return redirect('/')->with
                ([
                    'mensaje' => 'No se pudo agregar la región: '.$regNombre.' 👎🏻',
                    'css' => 'danger'
                ]);
    }
});

/******* UPDATE *******/
/*
    Cuando tenemos q pasarnos, de forma dinámica,
    diferentes variables usamos Routing ~{}~

    No importa el nombre, importa el ORDEN:
        '/region/edit/{a}/{v}', function( $z, $d )
*/

Route::get('/region/edit/{id}', function($id){
    /*
        Obtener datos de la región ~con RAW SQL~
            $region = DB::select(
                'SELECT
                    idRegion, regNombre
                FROM
                    regiones
                WHERE
                    idRegion = :id',
                    [ $id ]
                );
    */

    /*
        Obtener datos de la región ~con Fluent Query Builder~
            - Siempre el metodo que se usa es "table".

            - Si queremos usar un where, lo colocamos primero que el
            metodo que estamos usando y dentro de los parentesis colocamos
            el valor de filtrado de la tabla ~en este caso idRegion~.

            - Pero hace un array de array
                fetch() = first() - traigo uno solo
                fetchAll() = get() - traigo todos
    */

    $region = DB::table('regiones')->where('idRegion', $id)->first();

    // Retornar vista del form para modificar
    return view('regionEdit', [
        'region' => $region
    ]);
});

Route::put('/region/update', function () {
    // Capturamos datos enviados en el form
    $regNombre = request()->regNombre;
    $idRegion = request('idRegion'); // También funciona

    try {

        /*
            Usando rawSQL:
            DB::update(
                'UPDATE
                    regiones
                SET
                    regNombre = :regNombre
                WHERE
                    idRegion = :idRegion',
                    [ $regNombre, $idRegion ]
                );
        */

        /*
            Usando Fluent Query Builder y
            para UPDATE se usa array asociativo CAMPO - VALOR
        */
        DB::table('regiones')->where('idRegion', $idRegion)->update(['regNombre' => $regNombre]);

        return redirect('/')->with
                ([
                    'mensaje' => 'Nombre región: "'.$regNombre.'" modificada correctamente! 👌🏻',
                    'css' => 'success'
                ]);
    } catch (\Throwable $th) {
        return redirect('/')->with
                ([
                    'mensaje' => 'No se pudo actualizar la región: '.$regNombre.' 👎🏻',
                    'css' => 'danger'
                ]);
    }
});

/******* DELETE *******/
Route::get('/region/delete/{id}', function($id){
    // Obtener datos de la region
    $region = DB::table('regiones')->where('idRegion', $id)->first();

    // Saber si hay destinos x region
    $coincidencias = DB::table('destinos')->where('idRegion', $id)->count();

    if ($coincidencias) {
        return redirect('/')->with
                ([
                    'mensaje' => 'No es posible borrar la región: "'.$region->regNombre.'" porque tiene destinos relacionados 👎🏻',
                    'css' => 'warning'
                ]);
    }

    return view('regionDelete', ['region' => $region]);
});

Route::delete('/region/destroy', function () {
    $idRegion = request()->idRegion;
    $regNombre = request()->regNombre;

    try {
        /*
            Con RawSQL
            DB::delete('DELETE FROM regiones WHERE idRegion = :idRegion', [ $idRegion ]);
        */

        // Con Query Builder
        DB::table('regiones')->where('idRegion', $idRegion)->delete();
        return redirect('/')->with
                ([
                    'mensaje' => 'La región: "'.$regNombre.'" fue borrada correctamente! 👌🏻',
                    'css' => 'success'
                ]);
    } catch (\Throwable $th) {
        return redirect('/')->with
                ([
                    'mensaje' => 'No se pudo borrar la región: '.$regNombre.' 👎🏻',
                    'css' => 'danger'
                ]);
    }
});

/******* CRUD DE DESTINOS *******/
Route::get('/destinos', function(){
    /*
        Con RawSQL
        $destinos = DB::select(
            'SELECT
                idDestino,
                destNombre,
                destPrecio,
                regNombre
            FROM
                destinos
            JOIN
                regiones
            ON
                regiones.idRegion = destinos.idRegion
        ');
    */

    // Con Fluent Query Builder || con JOIN || sacando la peticion final se puede usar ->toSQL() para ver la sintaxis en SQL
    $destinos = DB::table('destinos AS d')->select('idDestino', 'destNombre', 'destPrecio', 'regNombre')->join('regiones AS r', 'r.idRegion', '=', 'd.idRegion')->get();

    return view('destinos', ['destinos' => $destinos]);
});

Route::get('/destino/create', function () {
    // Obtenemos listado de regiones
    $regiones = DB::table('regiones')->get();

    return view('destinoCreate', ['regiones'=>$regiones]);
});

Route::post('/destino/store', function(){
    // Capturo los datos del formulario
    $destNombre = request()->destNombre;
    $idRegion = request()->idRegion;
    $destPrecio = request()->destPrecio;
    $destAsientos = request()->destAsientos;
    $destDisponibles = request()->destDisponibles;

    try {
        DB::table('destinos')->insert([
            'destNombre' => $destNombre,
            'idRegion' => $idRegion,
            'destPrecio' => $destPrecio,
            'destAsientos' => $destAsientos,
            'destDisponibles' => $destDisponibles
        ]);

        return redirect('/destinos')->with
                ([
                    'mensaje' => 'Destino: "'.$destNombre.'" agregada correctamente! 👌🏻',
                    'css' => 'success'
                ]);
    } catch (\Throwable $th) {
        return redirect('/destinos')->with
                ([
                    'mensaje' => 'No se pudo agregar el destino: "'.$destNombre.'" 👎🏻',
                    'css' => 'danger'
                ]);
    }
});

Route::get('/destino/edit/{id}', function($id){
    $destino = DB::table('destinos')->where('idDestino', $id)->first();
    $regiones = DB::table('regiones')->get();

    return view('destinoEdit', [
        'destino'=>$destino,
        'regiones'=>$regiones
    ]);
});

Route::put('/destino/update', function(){
    $idDestino = request()->idDestino;
    $destNombre = request()->destNombre;
    $idRegion = request()->idRegion;
    $destPrecio = request()->destPrecio;
    $destAsientos = request()->destAsientos;
    $destDisponibles = request()->destDisponibles;

    try {
        DB::table('destinos')->where('idDestino', $idDestino)->update([
            'destNombre'=>$destNombre,
            'idRegion'=>$idRegion,
            'destPrecio'=>$destPrecio,
            'destAsientos'=>$destAsientos,
            'destDisponibles'=>$destDisponibles
        ]);

        return redirect('/destinos')->with
                ([
                    'mensaje' => 'Destino: "'.$destNombre.'" actualizado correctamente! 👌🏻',
                    'css' => 'success'
                ]);
    } catch (\Throwable $th) {
        return redirect('/destinos')->with
                ([
                    'mensaje' => 'No se pudo actualizar el destino: "'.$destNombre.'" 👎🏻',
                    'css' => 'danger'
                ]);
    }
});

Route::get('/destino/delete/{id}', function($id){
    $destino = DB::table('destinos')->where('idDestino', $id)->first();

    return view('destinoDelete', ['destino'=>$destino]);
});

Route::delete('/destino/destroy', function(){
    $idDestino = request('idDestino');
    $destNombre = request('destNombre');

    try {
        DB::table('destinos')->where('idDestino',$idDestino)->delete();

        return redirect('/destinos')->with
                ([
                    'mensaje' => 'Destino: "'.$destNombre.'" eliminado correctamente! 👌🏻',
                    'css' => 'success'
                ]);
    } catch (\Throwable $th) {
        return redirect('/destinos')->with
                ([
                    'mensaje' => 'No se pudo eliminar el destino: "'.$destNombre.'" 👎🏻',
                    'css' => 'danger'
                ]);
    }
});
