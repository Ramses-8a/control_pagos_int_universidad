<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\PagosEmpleados;
use App\Models\Empleado;
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
                'id' => 'proyecto-' . $proyecto->id,
                'title' => 'Inicio: ' . $proyecto->descripcion,
                'start' => $proyecto->fecha_inicio_date,
                'color' => '#0052cc', // Azul
                'type' => 'proyecto',
                'description' => $proyecto->descripcion,
            ];
            if ($proyecto->fecha_fin_date) {
                $eventos[] = [
                    'id' => 'proyecto-fin-' . $proyecto->id,
                    'title' => 'Vence: ' . $proyecto->descripcion,
                    'start' => $proyecto->fecha_fin_date,
                    // Rojo si no está completado, Verde si sí lo está
                    'color' => optional($proyecto->estatusProyecto)->nombre == 'Completado' ? '#28a745' : '#ea5455',
                    'type' => 'proyecto',
                    'description' => $proyecto->descripcion,
                ];
            }
        }

        // Eventos de Pagos a Empleados
        // 1. Pagos realizados (verde)
        $pagosCompletados = PagosEmpleados::with('empleado')->get();
        foreach ($pagosCompletados as $pago) {
            $eventos[] = [
                'id' => 'pago-completado-' . $pago->id,
                'title' => 'Pago realizado a: ' . (optional($pago->empleado)->nombre ?? 'Empleado'),
                'start' => $pago->fecha_pago,
                'color' => '#28a745', // Verde para pagos completados
                'type' => 'pago',
                'description' => 'Pago completado a: ' . (optional($pago->empleado)->nombre ?? 'Empleado') . ' por $' . number_format($pago->monto, 2),
            ];
        }

        // Pagos próximos (calculados dinámicamente)
        $proximosPagos = collect();
        $empleados = Empleado::activos()->with(['periodoPago', 'pagosEmpleados' => function($query) {
            // Asegurarse de que la relación pagosEmpleados en el modelo Empleado
            // esté definida con la clave foránea 'fk_empleados'.
            // Ejemplo: public function pagosEmpleados() { return $this->hasMany(PagosEmpleados::class, 'fk_empleados'); }
            $query->orderBy('fecha_pago', 'desc');
        }])->get();

        foreach ($empleados as $empleado) {
            $nextPendingPayment = null;
            $earliestFutureDate = null;

            foreach ($empleado->pagosEmpleados as $pagoEmpleado) {
                $periodoNombre = optional($empleado->periodoPago)->nombre;
                $fechaProximoPago = null;

                if ($periodoNombre === 'Quincenal') {
                    $fechaProximoPago = Carbon::parse($pagoEmpleado->fecha_pago)->addDays(15);
                } elseif ($periodoNombre === 'Mensual') {
                    $fechaProximoPago = Carbon::parse($pagoEmpleado->fecha_pago)->addMonth();
                }

                if ($fechaProximoPago && $fechaProximoPago->isFuture()) {
                    if ($earliestFutureDate === null || $fechaProximoPago->lt($earliestFutureDate)) {
                        $earliestFutureDate = $fechaProximoPago;
                        $nextPendingPayment = [
                            'tipo' => 'pago',
                            'descripcion' => 'Pago a: ' . $empleado->nombre_completo,
                            'fecha' => $fechaProximoPago,
                            'estatus' => 'Pendiente',
                            'link' => route('pagos.create'),
                        ];
                    }
                }
            }

            if ($nextPendingPayment) {
                $proximosPagos->push($nextPendingPayment);
            }
        }

        // 2. Único próximo pago pendiente por empleado (naranja)
        foreach ($proximosPagos as $pago) {
            $eventos[] = [
                'id' => 'pago-pendiente-' . uniqid(),
                'title' => $pago['descripcion'],
                'start' => $pago['fecha']->format('Y-m-d'),
                'color' => '#ff9f40', // Naranja para pagos pendientes
                'type' => 'pago',
                'description' => $pago['descripcion'] . ' (Vence en ' . $pago['fecha']->locale('es')->diffForHumans() . ')',
                'link' => $pago['link'],
            ];
        }

        // Eventos de Tareas
        $tareas = Tarea::all();
        foreach ($tareas as $tarea) {
            $eventos[] = [
                'id' => 'tarea-' . $tarea->id,
                'title' => 'Tarea: ' . $tarea->titulo,
                'start' => $tarea->fecha_fin,
                'color' => '#6f42c1', // Púrpura para tareas
                'type' => 'tarea',
                'description' => $tarea->titulo, // Asumiendo que existe una ruta para ver detalles de tareas
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
        $proximasTareas = Tarea::with('estatusTarea')
            ->whereHas('estatusTarea', function ($query) {
                $query->where('nombre', '!=', 'Completada');
            })
            // Que no sean las urgentes, sino las que siguen
            ->where('fecha_fin', '>', Carbon::now()->addDays(3)) 
            ->orderBy('fecha_fin', 'asc')
            ->take(5)
            ->get();

        // Combinar tareas próximas y pagos próximos en una sola colección
        $actividadesProximas = collect([])->concat($proximasTareas->map(function ($tarea) {
            return [
                'tipo' => 'tarea',
                'descripcion' => 'Tarea: ' . $tarea->titulo,
                'fecha' => $tarea->fecha_fin,
                'estatus' => optional($tarea->estatusTarea)->nombre,
            ];
        }))->concat($proximosPagos->map(function ($pago) {
            return [
                'tipo' => 'pago',
                'descripcion' => $pago['descripcion'],
                'fecha' => $pago['fecha'],
                'estatus' => 'Pendiente', // Los pagos próximos se consideran pendientes
                'link' => $pago['link'],
            ];
        }))->sortBy('fecha')->take(5);

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
            'actividadesProximas',
            'proyectosActivos'
        ));
    }
}