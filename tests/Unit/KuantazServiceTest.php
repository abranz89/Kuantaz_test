<?php

use Tests\TestCase;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use App\Services\KuantazService;

class KuantazServiceTest extends TestCase
{
    public function testItCanGetBeneficios(): void
    {

        $http = $this->getMockBuilder(PendingRequest::class)
                     ->disableOriginalConstructor()
                     ->getMock();

        //respuesta simulada de la API
        $http->method('get')
             ->willReturn(Http::response([
                 'data' => [
                     // Datos de beneficios simulados
                     ['id_programas' => 1, 'monto' => 10000, 'fecha_recepcion' => '29/04/2024', 'fecha' => '2024-04-29'],
                     ['id_programa' => 2, 'monto' => 20000, 'fecha_recepcion' => '30/03/2024', 'fecha' => '2024-03-30'],
                 ]
             ]));

        //instancia del servicio KuantazService y pasarle la instancia simulada de PendingRequest
        $kuantazService = new KuantazService($http);

        // Se Llama al método getBeneficios para obtener los beneficios
        $beneficios = $kuantazService->getBeneficios();

        // Se Verifica si los beneficios son una instancia de Collection
        $this->assertInstanceOf(Collection::class, $beneficios);

        // Se Verifica si se obtuvieron algunos beneficios
        $this->assertGreaterThan(0, $beneficios->count());

        // Se Verifica si los beneficios tienen la estructura esperada
        $this->assertArrayHasKey('id_programa', $beneficios->first());
        $this->assertArrayHasKey('monto', $beneficios->first());
        $this->assertArrayHasKey('fecha_recepcion', $beneficios->first());
        $this->assertArrayHasKey('fecha', $beneficios->first());
    }

    public function testItCanGetFiltros(): void
    {

        $http = $this->getMockBuilder(PendingRequest::class)
                     ->disableOriginalConstructor()
                     ->getMock();

        //respuesta simulada de la API
        $http->method('get')
             ->willReturn(Http::response([
                 'data' => [
                     // Datos de filtros simulados
                     ['id_programas' => 1, 'tramite' => 'Emprende', 'min' => 0, 'max' => 50000, 'ficha_id' => 903]
                 ]
             ]));

        //instancia del servicio KuantazService y pasarle la instancia simulada de PendingRequest
        $kuantazService = new KuantazService($http);

        // Se Llama al método getBeneficios para obtener los filtros
        $filtros = $kuantazService->getFiltros();

        // Se Verifica si los filtros son una instancia de Collection
        $this->assertInstanceOf(Collection::class, $filtros);

        // Se Verifica si se obtuvieron algunos filtros
        $this->assertGreaterThan(0, $filtros->count());

        // Se Verifica si los filtros tienen la estructura esperada
        $this->assertArrayHasKey('id_programa', $filtros->first());
        $this->assertArrayHasKey('tramite', $filtros->first());
        $this->assertArrayHasKey('min', $filtros->first());
        $this->assertArrayHasKey('max', $filtros->first());
        $this->assertArrayHasKey('ficha_id', $filtros->first());
    }

    public function testItCanGetFichas(): void
    {

        $http = $this->getMockBuilder(PendingRequest::class)
                     ->disableOriginalConstructor()
                     ->getMock();

        //respuesta simulada de la API
        $http->method('get')
             ->willReturn(Http::response([
                 'data' => [
                     // Datos de fichas simuladas
                     ['id' => 1, 'nombre' => 'Crece', 'id_programa' => 146, 'url' => 'crece', 'categoria' => 'trabajo',  'descripcion' => 'Subsidio para implementar plan de trabajo en empresas']
                 ]
             ]));

        //instancia del servicio KuantazService y pasarle la instancia simulada de PendingRequest
        $kuantazService = new KuantazService($http);

        // Se Llama al método getBeneficios para obtener las fichas
        $fichas = $kuantazService->getFichas();

        // Se Verifican si las fichas son una instancia de Collection
        $this->assertInstanceOf(Collection::class, $fichas);

        // Se Verifica si se obtuvieron algunas fichas
        $this->assertGreaterThan(0, $fichas->count());

        // Se Verifica si las fichas tienen la estructura esperada
        $this->assertArrayHasKey('id', $fichas->first());
        $this->assertArrayHasKey('nombre', $fichas->first());
        $this->assertArrayHasKey('id_programa', $fichas->first());
        $this->assertArrayHasKey('url', $fichas->first());
        $this->assertArrayHasKey('categoria', $fichas->first());
        $this->assertArrayHasKey('descripcion', $fichas->first());

    }
}
