<?php

namespace App\Http\Controllers;

use App\Models\EstatusProyecto;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $proyectos = Proyecto::all();
        $estatusProyectos = EstatusProyecto::all();

        $query = Proyecto::query();

        if ($request->filled('project_id')) {
            $query->where('id', $request->project_id);
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        $proyectosFiltrados = $query->get();

        $reportData = [];
        foreach ($proyectosFiltrados as $proyecto) {
            $gastos = 0;
            $ganancias = 0;

            // Gastos por pagos a empleados
            $pagosEmpleados = $proyecto->pagosEmpleados();
            if ($request->filled('start_date')) {
                $pagosEmpleados->whereDate('fecha_pago', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $pagosEmpleados->whereDate('fecha_pago', '<=', $request->end_date);
            }
            $gastos += $pagosEmpleados->sum('monto');

            // Aquí puedes añadir la lógica para calcular las ganancias del proyecto
            // Por ahora, asumiremos que las ganancias son 0 para el ejemplo
            $ganancias = $proyecto->precio; // Asumiendo que el precio del proyecto es la ganancia

            $perdidas = $ganancias - $gastos;

            $reportData[] = [
                'proyecto' => $proyecto->nombre,
                'gastos' => $gastos,
                'ganancias' => $ganancias,
                'perdidas' => $perdidas,
            ];
        }

        $totalGastos = array_sum(array_column($reportData, 'gastos'));
        $totalGanancias = array_sum(array_column($reportData, 'ganancias'));
        $totalPerdidas = array_sum(array_column($reportData, 'perdidas'));

        $finalReportData = [
            'proyectos' => $reportData,
            'total_gastos' => $totalGastos,
            'total_ganancias' => $totalGanancias,
            'total_perdidas' => $totalPerdidas,
        ];

        return view('reports.reportes', compact('finalReportData', 'proyectos', 'estatusProyectos'));
    }

    public function generate(Request $request)
    {
        // This method is no longer needed as its logic is merged into index
        // You can remove this method or keep it as a placeholder if routes depend on it.
        // For now, I\'ll leave it as a placeholder.
        return $this->index($request);
    }
}
