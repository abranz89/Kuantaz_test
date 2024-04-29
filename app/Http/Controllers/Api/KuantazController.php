<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class KuantazController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('https://run.mocky.io/v3/399b4ce1-5f6e-4983-a9e8-e3fa39e1ea71');
        $beneficios = collect($response['data']);

        $response = Http::get('https://run.mocky.io/v3/06b8dd68-7d6d-4857-85ff-b58e204acbf4');
        $filtros = collect($response['data']);

        $response = Http::get('https://run.mocky.io/v3/c7a4777f-e383-4122-8a89-70f29a6830c0');
        $fichas = collect($response['data']);

        $beneficiosConRelacion = $beneficios->filter(function ($beneficio) use ($filtros) {
            $filtro = $filtros->firstWhere('id_programa', $beneficio['id_programa']);
            return $beneficio['monto'] >= $filtro['min'] && $beneficio['monto'] <= $filtro['max'];
        })
        ->map(function ($beneficio) use ($filtros, $fichas) {
            $filtro = $filtros->firstWhere('id_programa', $beneficio['id_programa']);
            $ficha = $fichas->firstWhere('id_programa', $beneficio['id_programa']);
            $anio = Carbon::parse($beneficio['fecha_recepcion'])->format('Y');
            return array_merge($beneficio, ['ano' => $anio, 'view' => true, 'ficha' => $ficha]);
        })
        ->groupBy('ano')
        ->map(function ($beneficiosPorAnio) {
            return [
                'year' => $beneficiosPorAnio->first()['ano'],
                'num' => $beneficiosPorAnio->count(),
                'total' => $beneficiosPorAnio->sum('monto'),
                'beneficios' => $beneficiosPorAnio
            ];
        })
        ->sortKeysDesc()
        ->values();

        return $beneficiosConRelacion;

    }

}
