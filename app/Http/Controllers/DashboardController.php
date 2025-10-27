<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\PagosEmpleados;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal con datos dinámicos.
     */
    public function index()
    {
        // --- 1. Sección de Resumen (Cards) ---
        // Asumimos que un estatus 'Completado' significa que el proyecto se cobró.
        // DEBERÁS AJUSTAR 'Completado' al nombre exacto de tu estatus en la BD.
        $ingresosCobrados = Proyecto::whereHas('estatusProyecto', function ($query) {
            $query->where('nombre', 'Completado');
        })->sum('precio');

        $gastosPagados = PagosEmpleados::sum('monto');
        $gananciaNeta = $ingresosCobrados - $gastosPagados;

        // --- 2. Gráfico Circular (Cuentas por Cobrar) ---
        $proyectosPendientes = Proyecto::whereHas('estatusProyecto', function ($query) {
            $query->where('nombre', '!=', 'Completado');
        })->sum('precio');

        $totalProyectos = $ingresosCobrados + $proyectosPendientes;
        $porcentajeCobrado = $totalProyectos > 0 ? ($ingresosCobrados / $totalProyectos) * 100 : 0;
        $porcentajePendiente = $totalProyectos > 0 ? ($proyectosPendientes / $totalProyectos) * 100 : 100;
        
        // --- 3. Calendario de Vencimientos y Actividades ---
        $eventos = [];

        // Eventos de Proyectos (Inicio y Fin)
        $proyectos = Proyecto::with('estatusProyecto')->get();
        foreach ($proyectos as $proyecto) {
            $eventos[] = [
                'title' => 'Inicio: ' . $proyecto->descripcion,
                'start' => $proyecto->fecha_inicio_date,
                'color' => '#0052cc', // Azul
            ];
            if ($proyecto->fecha_fin_date) {
                $eventos[] = [
                    'title' => 'Vence: ' . $proyecto->descripcion,
                    'start' => $proyecto->fecha_fin_date,
                    // Rojo si no está completado, Verde si sí lo está
                    'color' => optional($proyecto->estatusProyecto)->nombre == 'Completado' ? '#28a745' : '#ea5455',
                ];
            }
        }

        // Eventos de Pagos a Empleados
        // Asegúrate de tener la relación ->empleado() en tu modelo PagosEmpleados
        $pagos = PagosEmpleados::with('empleado')->get(); 
        foreach ($pagos as $pago) {
            $eventos[] = [
                'title' => 'Pago a: ' . (optional($pago->empleado)->nombre ?? 'Empleado'),
                'start' => $pago->fecha_pago,
                'color' => '#ff9f40', // Naranja
            ];
        }

        // --- 4. Alertas y Acciones Urgentes (Tareas Próximas a Vencer) ---
        $tareasUrgentes = Tarea::with('estatusTarea')
            ->whereHas('estatusTarea', function ($query) {
                $query->where('nombre', '!=', 'Completada');
            })
            ->where('fecha_fin', '>=', Carbon::now()) // Que no estén vencidas
            ->where('fecha_fin', '<=', Carbon::now()->addDays(3)) // Próximos 3 días
            ->orderBy('fecha_fin', 'asc')
            ->take(3)
            ->get();

        // --- 5. Pendientes y Actividades Próximas ---
        $proximasActividades = Tarea::with('estatusTarea')
            ->whereHas('estatusTarea', function ($query) {
                $query->where('nombre', '!=', 'Completada');
            })
            // Que no sean las urgentes, sino las que siguen
            ->where('fecha_fin', '>', Carbon::now()->addDays(3)) 
            ->orderBy('fecha_fin', 'asc')
            ->take(5)
            ->get();

        // --- 6. Salud de Proyectos Activos ---
        $proyectosActivos = Proyecto::with('estatusProyecto')
            ->whereHas('estatusProyecto', function ($query) {
                $query->where('nombre', '!=', 'Completado');
            })
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // --- Enviar todo a la vista ---
        return view('dashboard', compact(
            'ingresosCobrados',
            'gastosPagados',
            'gananciaNeta',
            'porcentajeCobrado',
            'porcentajePendiente',
            'eventos',
            'tareasUrgentes',
            'proximasActividades',
            'proyectosActivos'
        ));
    }
}