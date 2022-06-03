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
        Commands\Testeo::class
        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $tareas=Tareas_Programadas::tareas()->get();
        foreach($tareas as $tarea){
            if($tarea->tarea_estado==1){
                if($tarea->tarea_tipo_tiempo==1)  $schedule->command($tarea->tarea_procedimiento)->everyMinute();
                if($tarea->tarea_tipo_tiempo==2)  $schedule->command($tarea->tarea_procedimiento)->everyFiveMinutes();
                if($tarea->tarea_tipo_tiempo==3)  $schedule->command($tarea->tarea_procedimiento)->everyFifteenMinutes();
                if($tarea->tarea_tipo_tiempo==4)  $schedule->command($tarea->tarea_procedimiento)->hourly();
                if($tarea->tarea_tipo_tiempo==5)  $schedule->command($tarea->tarea_procedimiento)->everySixHours();
                if($tarea->tarea_tipo_tiempo==6)  $schedule->command($tarea->tarea_procedimiento)->dailyAt('06:00');
                if($tarea->tarea_tipo_tiempo==7)  $schedule->command($tarea->tarea_procedimiento)->monthlyOn(date('t'), '23:00');
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
