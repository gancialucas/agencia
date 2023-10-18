<!-- Para heredar plantilla debemos usar: -->
@extends( 'layouts.plantilla' )

    <!-- Para trabajar de forma dinámica debo hacerlo dentro del "section" -->
    @section( 'contenido' )

        <h1>Formulario de envío</h1>

        <div class="alert bg-light border p-4 col-8 mx-auto shadow">

            <!-- Usamos "paradigma de peticiones" - TODO PASA POR EL ENRUTADOR -->
            <form action="/procesa" method="post">
                @csrf <!-- Cross Site Request Forgery ~ Genera un token único para forms tipo POST -->
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" aria-describedby="nombreHelp">
                <div id="nombreHelp" class="form-text">Ingrese su nombre, por favor! Muchas gracias 😎.</div>
                <button class="btn btn-dark mt-2">Enviar</button>
            </form>
        </div>

    @endsection