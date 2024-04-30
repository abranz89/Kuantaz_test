<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\KuantazService;
use Carbon\Carbon;

/**
 * @OA\Info(
 *     title="API de Beneficios",
 *     version="1.0.0",
 *     description="API que proporciona información sobre beneficios ordenados por año.",
 *     @OA\Contact(
 *         email="abran_contreras@hotmail.com",
 *         name="Abraham Contreras"
 *     )
 * )
 */

class KuantazController extends Controller
{
    /**
     * @OA\Get(
     *     path="/information",
     *     summary="Obtiene la información de los beneficios",
     *     tags={"Information"},
     *     @OA\Response(response="200", description="Información de beneficios obtenida exitosamente")
     * )
     */
    public function index()
    {
        try {

            // Se Crea una instancia del servicio KuantazService para obtener los datos de beneficios, filtros y fichas.
            $kuantazService = new KuantazService();

             // Se Obtienen los datos de beneficios, filtros y fichas del servicio Kuantaz.
            $beneficios = $kuantazService->getBeneficios();
            $filtros = $kuantazService->getFiltros();
            $fichas = $kuantazService->getFichas();

            // Filtrando los beneficios para garantizar que el monto esté dentro del rango especificado en los filtros.
            $beneficiosConRelacion = $beneficios->filter(function ($beneficio) use ($filtros) {
                $filtro = $filtros->firstWhere('id_programa', $beneficio['id_programa']);
                return $beneficio['monto'] >= $filtro['min'] && $beneficio['monto'] <= $filtro['max'];
            })
            // Mapeando los beneficios para agregar información adicional, como el año de recepción, la ficha correspondiente y la vista (que se establece como `true`).
            ->map(function ($beneficio) use ($filtros, $fichas) {
                $filtro = $filtros->firstWhere('id_programa', $beneficio['id_programa']);
                $ficha = $fichas->firstWhere('id_programa', $beneficio['id_programa']);
                $anio = Carbon::parse($beneficio['fecha_recepcion'])->format('Y');
                return array_merge($beneficio, ['ano' => $anio, 'view' => true, 'ficha' => $ficha]);
            })
            // Agrupando los beneficios por año
            ->groupBy('ano')
            // Mapeando y resumiendo los beneficios para cada año.
            ->map(function ($beneficiosPorAnio) {
                return [
                    'year' => $beneficiosPorAnio->first()['ano'],
                    'num' => $beneficiosPorAnio->count(),
                    'total' => $beneficiosPorAnio->sum('monto'),
                    'beneficios' => $beneficiosPorAnio
                ];
            })
            // Ordenando los resúmenes de los beneficios por año en orden descendente
            ->sortKeysDesc()
            ->values();

            //Devolviendo los resúmenes de los beneficios por año como resultado final
            return $beneficiosConRelacion;

        } catch(\Exception $e){
            return response()->json(['error' => 'No se pudieron obtener los datos.', 'mensaje' => $e->getMessage()], 500);
        }

    }

}
