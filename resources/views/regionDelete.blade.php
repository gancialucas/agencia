@extends('layouts.plantilla')
    @section('contenido')

        <h1>Baja de una región</h1>

        <div class="alert text-danger bg-light p-4 col-8 mx-auto shadow">
            <p>Se eliminará la región <span class="fs-4">{{ $region->regNombre }}</span>.</p>

            <form action="/region/destroy" method="post">

                {{-- Usamos directiva blade para delete --}}
                @method('delete')
                @csrf

                <input type="hidden" name="idRegion" value="{{ $region->idRegion }}">
                <input type="hidden" name="regNombre" value="{{ $region->regNombre }}">

                <button class="btn btn-danger btn-block my-3">Confirmar baja</button>
                <a href="/" class="btn btn-outline-secondary btn-block my-2">Volver a panel</a>
            </form>

        </div>


    @endsection
