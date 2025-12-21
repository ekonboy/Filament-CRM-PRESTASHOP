@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<h1 class="mb-4">Productos</h1>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Referencia</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        {{-- Aquí se llenará con datos dinámicos de PrestaShop --}}
        @foreach($products ?? [] as $product)
            <tr>
                <td>{{ $product['id'] }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['reference'] }}</td>
                <td>{{ $product['price'] }} €</td>
                <td>{{ $product['stock'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
