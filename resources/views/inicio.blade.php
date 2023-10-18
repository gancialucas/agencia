<!-- Para heredar plantilla debemos usar: -->
@extends( 'layouts.plantilla' )

    <!-- Para trabajar de forma dinámica debo hacerlo dentro del "section" -->
    @section( 'contenido' )

        <div class="mt-5 d-flex flex-column align-items-center">
            <h1><u>Desarrollo</u></h1>

            <h1>SOY UNA VISTA</h1>
            
            <!-- MOUSTAGE, es sintaxis de blade (es solo para imprimir una particuala y listo)-->
            <p>¡Hola {{ $nombre }} {{ $apellido }}! Accediste a una vista de ejemplo el día {{ date('d/m/Y') }}</p>
            
            <ul>
                <li>Tu dni es: {{ $dni }}</li>
            </ul>
            
            <h3 class="pt-5">Cantidad de ingresos</h3>
            
            <ul>
                <!-- Con blade se utilizan 'directivas blade' usando "@" -->
                @for ($i = 1; $i <= $numero; $i++)
                <li>Numero de ingresos a la cuenta: {{ $i }}</li>
                @endfor
            </ul>
            
            <h1 class="pt-5" style="color: red;">VER VIDEO 4 DESDE 1:05:00</h1>
            <p>git: exegeses/laravel-60323</p>
        </div>
    
    @endsection