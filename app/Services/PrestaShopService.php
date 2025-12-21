<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\Product;
use App\Models\PrestaCustomer; // Nuevo modelo que crearemos

class PrestaShopService
{
    /**
     * Obtener productos como colección Laravel
     */
    public function getProducts(): Collection
    {
        return Product::with(['lang', 'stock'])
            ->mainProducts() // excluye combinaciones
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id_product,
                    'name' => $p->lang->name ?? 'Sin nombre',
                    'reference' => $p->reference,
                    'price' => $p->price,
                    'stock' => $p->stock->quantity ?? 0,
                ];
            });
    }

    /**
     * Obtener clientes como colección Laravel
     */
    public function getCustomers()
    {
        return PrestaCustomer::select(
            'id_customer',
            'firstname',
            'lastname',
            'email'
        )
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id_customer,
                    'name' => trim("{$c->firstname} {$c->lastname}"),
                    'email' => $c->email,
                ];
            });
    }
}
