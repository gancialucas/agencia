@include( 'layouts.header' )
@include( 'layouts.nav' )

    <main class="container py-4">
        <!-- Con "at_yield estaria declarando una directiva q' vuelve dinámica la sección -->
        @yield('contenido')

    </main>

@include( 'layouts.footer' )