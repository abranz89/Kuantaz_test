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

            $beneficiosConRelacion = $beneficios->filter(function ($beneficio) use ($filtros, $fichas) {
                  // Filtra los beneficios para garantizar que el monto esté dentro del rango especificado en los filtros.
                $filtro = $filtros->firstWhere('id_programa', $beneficio['id_programa']);
                return $beneficio['monto'] >= $filtro['min'] && $beneficio['monto'] <= $filtro['max'];
            })->transform(function ($beneficio) use ($filtros, $fichas) {
                $filtro = $filtros->firstWhere('id_programa', $beneficio['id_programa']);
                $ficha = $fichas->firstWhere('id_programa', $beneficio['id_programa']);
                $anio = Carbon::parse($beneficio['fecha_recepcion'])->format('Y');
                $beneficio['ano'] = $anio;
                $beneficio['view'] = true;
                $beneficio['ficha'] = $ficha;
                return $beneficio;
            })->groupBy(function ($beneficio) {
                // Agrupa los beneficios por año.
                return Carbon::parse($beneficio['fecha_recepcion'])->format('Y');
            })->map(function ($beneficiosPorAnio, $anio) {
                 // Resumen de los beneficios para cada año.
                return [
                    'year' => $anio,
                    'num' => $beneficiosPorAnio->count(),
                    'total' => $beneficiosPorAnio->sum('monto'),
                    'beneficios' => $beneficiosPorAnio
                ];
            })->sortKeysDesc()->values();

            //Devolviendo los resúmenes de los beneficios por año como resultado final
            return $beneficiosConRelacion;

        } catch(\Exception $e){
            return response()->json(['error' => 'No se pudieron obtener los datos.', 'mensaje' => $e->getMessage()], 500);
        }

    }

}
