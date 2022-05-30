<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Tareas_Programadas;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\TestTask::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $hora = "[".date("Y-m-d H-i-s")."]";
        $tareas=Tareas_Programadas::tareas()->get();
        
        //cargarTareas
        Storage::append("archiivo.txt", "total tareas ".count($tareas));
        
        foreach($tareas as $tarea){
            Storage::append("archiivo.txt", "tarea encontrada: ".$tarea->tarea_nombre_proceso.',   estado: '.$tarea->tarea_estado.',   tipo: '.$tarea->tarea_tipo_tiempo);

            if($tarea->tarea_estado==1){
                if($tarea->tarea_tipo_tiempo==1)  $schedule->command('facturas:listarFacturas')->everyMinute();
                if($tarea->tarea_tipo_tiempo==2)  $schedule->command('facturas:listarFacturas')->everyFiveMinutes();
                if($tarea->tarea_tipo_tiempo==3)  $schedule->command('facturas:listarFacturas')->everyFifteenMinutes();
                if($tarea->tarea_tipo_tiempo==4)  $schedule->command('facturas:listarFacturas')->hourly();
                if($tarea->tarea_tipo_tiempo==5)  $schedule->command('facturas:listarFacturas')->everySixHours();
                if($tarea->tarea_tipo_tiempo==6)  $schedule->command('facturas:listarFacturas')->dailyAt('06:00');
                if($tarea->tarea_tipo_tiempo==7)  $schedule->command('facturas:listarFacturas')->monthlyOn(date('t'), '23:00');
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
