@extends( 'layouts.plantilla' )

    @section('contenido')

        <h1>Testeo de conexión a la base de datos</h1>

        @php
            if( DB::connection()->getDatabaseName() ) {
                echo "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
            }
        @endphp


    @endsection
