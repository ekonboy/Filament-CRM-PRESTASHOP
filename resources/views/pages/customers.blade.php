@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<h1 class="mb-4">Clientes</h1>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            {{-- <th>Teléfono</th> --}}
            <th>Última compra</th>
        </tr>
    </thead>
    <tbody>
        {{-- Aquí se llenará con datos dinámicos de PrestaShop --}}
        @foreach($customers ?? [] as $customer)
            <tr>
                <td>{{ $customer['id'] }}</td>
                <td>{{ $customer['name'] }}</td>
                <td>{{ $customer['email'] }}</td>
                {{-- <td>{{ $customer['phone'] }}</td> --}}
                <td>{{ $customer['last_order'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
