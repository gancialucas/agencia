@extends('layouts.plantilla')
    @section('contenido')

        <h1>Baja de un destino</h1>

        <div class="alert text-danger bg-light p-4 col-8 mx-auto shadow">
            <p>Se eliminará el destino <span class="fs-4">{{ $destino->destNombre }}</span>.</p>
            
            <form action="/destino/destroy" method="post">
                
                {{-- Usamos directiva blade para delete --}}
                @method('delete')
                @csrf
                
                <input type="hidden" name="idDestino" value="{{ $destino->idDestino }}">
                <input type="hidden" name="destNombre" value="{{ $destino->destNombre }}">
                
                <button class="btn btn-danger btn-block my-3">Confirmar baja</button>
                <a href="/destinos" class="btn btn-outline-secondary btn-block my-2">Volver a panel</a>
            </form>

        </div>


    @endsection
