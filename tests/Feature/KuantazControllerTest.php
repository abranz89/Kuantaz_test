<?php

namespace Tests\Feature;
use Tests\TestCase;

class KuantazControllerTest extends TestCase
{
    public function test_index(): void
    {
        // Aquí simulamos una solicitud GET a la ruta del controlador que estás probando
        $response = $this->get('/api/information');

         // Verificamos si la respuesta tiene el código de estado HTTP 200 (OK)
         $response->assertStatus(200);

        // se verifica el contenido de la respuesta
        $response->assertJsonStructure([
            '*' => [
                'year',
                'num',
                'total',
                'beneficios' => [
                    '*' => [
                        'id_programa',
                        'monto',
                        'fecha_recepcion',
                        'fecha',
                        'ano',
                        'view',
                        'ficha' => [
                            'id',
                            'nombre',
                            'id_programa',
                            'url',
                            'categoria',
                            'descripcion'
                        ]
                    ]
                ]
            ]
        ]);
    }
}
