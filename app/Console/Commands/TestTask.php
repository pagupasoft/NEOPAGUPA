<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\tareasProgramadasController;
use App\Models\Tareas_Programadas;

;

class TestTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facturas:listarFacturas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar las facturas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tareas=Tareas_Programadas::tareas()->get();
        $hora = "[".date("Y-m-d H-i-s")."]";
        //listar los 10 primeros usuarios
        Storage::append("archiivo.txt", "a guardar empresa id: "."1");
        
        foreach($tareas as $tarea){
            Storage::append("archiivo.txt", $hora."dentro del try 1 ".$tarea->tarea_nombre_proceso.'  '.$tarea->tarea_estado);
        }
    }
}
