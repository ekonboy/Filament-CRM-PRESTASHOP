<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TagDatabaseTest extends TestCase
{
    public function test_verificar_si_la_db_fuerza_el_idioma_uno()
    {
        $idProduct = 9999; // ID ficticio para el test
        $idLangEsperado = 3; // Vamos a intentar forzar el idioma 3 (Francés)

        // 1. Limpiar restos
        DB::table('soft_product_tag')->where('id_product', $idProduct)->delete();

        // 2. Insertar un tag nuevo directamente con SQL
        $tagId = DB::table('soft_tag')->insertGetId([
            'id_lang' => $idLangEsperado,
            'name' => 'TagTest' . rand(1,1000),
        ]);

        // 3. Insertar la relación
        DB::table('soft_product_tag')->insert([
            'id_product' => $idProduct,
            'id_tag' => $tagId,
            'id_lang' => $idLangEsperado,
        ]);

        // 4. Recuperar y comprobar
        $registro = DB::table('soft_product_tag')
            ->where('id_product', $idProduct)
            ->first();

        // Si esto falla, es que la base de datos tiene un Trigger o Default que cambia el 3 por el 1
        $this->assertEquals(
            $idLangEsperado,
            $registro->id_lang,
            "ERROR: La base de datos guardó id_lang {$registro->id_lang} pero le pedimos {$idLangEsperado}"
        );

        // Limpiar después del test
        DB::table('soft_product_tag')->where('id_product', $idProduct)->delete();
        DB::table('soft_tag')->where('id_tag', $tagId)->delete();
    }
}
