<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class TagDatabaseTest extends TestCase
{
    /** @test */
    public function it_checks_if_database_allows_other_languages()
    {
        // FORZAMOS LA CONEXIÓN MYSQL (Laragon)
        $connection = DB::connection('mysql');

        $idProduct = 9999;
        $idLangEsperado = 3;

        // Limpiar usando la conexión mysql
        $connection->table('soft_product_tag')->where('id_product', $idProduct)->delete();

        // 1. Insertar Tag
        $tagId = $connection->table('soft_tag')->insertGetId([
            'id_lang' => $idLangEsperado,
            'name' => 'Test_FR_' . time(),
        ]);

        // 2. Insertar Relación
        $connection->table('soft_product_tag')->insert([
            'id_product' => $idProduct,
            'id_tag' => $tagId,
            'id_lang' => $idLangEsperado,
        ]);

        // 3. Recuperar
        $registro = $connection->table('soft_product_tag')
            ->where('id_product', $idProduct)
            ->first();

        // Verificación
        $this->assertNotNull($registro, "El registro no se creó en MySQL");
        $this->assertEquals($idLangEsperado, (int) $registro->id_lang, "MySQL cambió el idioma al 1 automáticamente");

        // Limpiar
        $connection->table('soft_product_tag')->where('id_product', $idProduct)->delete();
        $connection->table('soft_tag')->where('id_tag', $tagId)->delete();
    }
}
