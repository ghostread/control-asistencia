<?php

namespace App\Http\Controllers;
use App\Asistencia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function __construct()
        {
            $this->middleware('auth');
        }
    public function index(Request $request)
    {
        // ROLES
        // Administrador=1
        // Autoridad=2
        // Docente=3
        // Auxiliar Docencia=4
        // Auxiliar Laboratorio=5

        if($request){

            $userId=Auth::user()->id;
 
            $sql=trim($request->get('buscarTexto'));

            //Si el usuario es Auxiliar de docencia que es  igual al rol 4
             if(Auth::user()->rol==4){

                //Consulta realizada a la base de datos para obtener las asistencias del rol 4
                $asistencias=DB::table('asistencias')
                    ->join('horas','asistencias.hora','=','horas.id')
                    ->join('materias','horas.materia','=','materias.id')
                    ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
                    ->select('asistencias.id','asistencias.contenido','asistencias.plataforma',
                    'asistencias.herramientas','asistencias.fecharepo','asistencias.link','horas.dia',
                    'asistencias.tipoclase','asistencias.created_at','horas.hora','asistencias.observacion','asistencias.archivos','asistencias.fecha',
                    'materias.nombre','materias.grupo','unidadacademica.facultad',
                    'unidadacademica.nombre as unidad','materias.id as idmateria')
                    ->where('asistencias.usuario','=',$userId)
                    ->where('materias.nombre','LIKE','%'.$sql.'%')
                    ->orderBy('asistencias.id','desc')
                    ->paginate(15);
                //Consulta para obtener los hoarios del rol 4
                $horarios=DB::table('horas')
                ->join('materias','horas.materia','=','materias.id')
                ->join('clases','materias.id','=','clases.materia')
                ->select('horas.id','horas.hora','horas.dia','materias.id as materiaid','materias.nombre')
                ->where('clases.user','=',$userId)
                ->where('horas.tipo','=','Auxiliar')
                ->get();

                return view('asistencia.index',['materias'=>$horarios,'asistencias'=>$asistencias]);
        
             }else if(Auth::user()->rol==3){
                //consulata para obetener las asistencias de los Docentes rol 3
                $asistencias=DB::table('asistencias')
                    ->join('horas','asistencias.hora','=','horas.id')
                    ->join('materias','horas.materia','=','materias.id')
                    ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
                    ->select('asistencias.id','asistencias.contenido','asistencias.plataforma',
                    'asistencias.herramientas','asistencias.fecha','asistencias.fecharepo','asistencias.link','horas.dia',
                    'asistencias.tipoclase','asistencias.created_at','horas.hora','asistencias.observacion','asistencias.archivos',
                    'materias.nombre','materias.grupo','unidadacademica.facultad',
                    'unidadacademica.nombre as unidad','materias.id as idmateria')
                    ->where('asistencias.usuario','=',$userId)
                    ->where('materias.nombre','LIKE','%'.$sql.'%')
                    ->orderBy('asistencias.id','desc')
                    ->paginate(15);

                $horarios=DB::table('horas')
                ->join('materias','horas.materia','=','materias.id')
                ->join('clases','materias.id','=','clases.materia')
                ->select('horas.id','horas.hora','horas.dia','materias.id as materiaid','materias.nombre')
                ->where('clases.user','=',$userId)
                ->where('horas.tipo','=','Docencial')
                ->get();

                return view('asistencia.index',['materias'=>$horarios,'asistencias'=>$asistencias]);
        
             }else if(Auth::user()->rol==5){
                return view('asistencia.index');
        
             }
            
            //Rol 2 = Autoridad 
            if(Auth::user()->rol==2){
             $sql=trim($request->get('buscarTexto'));
             $fechainicio=trim($request->get('fechainicio'));
             $fechafin=trim($request->get('fechafin'));
             
                $asistencias;
                //Si se pasa el filtro de fecha inicio y fecha fin
                if($fechainicio&&$fechafin){
                    
                    // Consulata de asistencias para la vista de la atoridad academica
                    $asistencias=DB::table('asistencias')
                    ->join('horas','asistencias.hora','=','horas.id')
                    ->join('materias','horas.materia','=','materias.id')
                    ->join('users','asistencias.usuario','=','users.id')
                    ->join('roles','users.rol','=','roles.id')
                    ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
                    ->select('asistencias.id','asistencias.contenido','asistencias.plataforma',
                    'asistencias.herramientas','asistencias.fecharepo','asistencias.link','asistencias.fecha',
                    'asistencias.tipoclase','asistencias.created_at','asistencias.hora','asistencias.observacion','asistencias.archivos',
                    'materias.nombre as materia','materias.grupo','unidadacademica.facultad','horas.dia',
                    'unidadacademica.nombre as unidad','materias.id as idmateria','users.nombre','users.apellido','users.codsis','roles.rol')
                     ->where('unidadacademica.jefe','=',$userId)
                    ->whereBetween('asistencias.fecha', [$fechainicio, $fechafin])
                    ->orderBy('asistencias.fecha','desc')
                    ->paginate(15);


                //Cuando no se para el filtro de fechas
                }else{
                    $asistencias=DB::table('asistencias')
                    ->join('horas','asistencias.hora','=','horas.id')
                    ->join('materias','horas.materia','=','materias.id')
                    ->join('users','asistencias.usuario','=','users.id')
                    ->join('roles','users.rol','=','roles.id')
                    ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
                    ->select('asistencias.id','asistencias.contenido','asistencias.plataforma',
                    'asistencias.herramientas','asistencias.fecharepo','asistencias.link','asistencias.fecha',
                    'asistencias.tipoclase','asistencias.created_at','asistencias.hora','asistencias.observacion','asistencias.archivos',
                    'materias.nombre as materia','materias.grupo','unidadacademica.facultad','horas.dia',
                    'unidadacademica.nombre as unidad','materias.id as idmateria','users.nombre','users.apellido','users.codsis','roles.rol')
                    ->where('unidadacademica.jefe','=',$userId)
                    ->orderBy('asistencias.fecha','desc')
                    ->paginate(15);
                }
                   $contador=0;
                   foreach($asistencias as $asi){
                        if($asi->fecharepo){
                            $contador++;
                        }
                   }
                
                return view('asistencia.index',['asistencias'=>$asistencias,'fechainicio'=>$fechainicio,'fechafin'=>$fechafin,'contador'=>$contador]);

            }

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request){
        $materia=trim($request->get('materia'));

        // Query para obtener las horas 
             $horarios=DB::table('horas')
            ->join('materias','horas.materia','=','materias.id')
            ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
            ->select('horas.id','horas.hora','horas.dia','materias.nombre','materias.grupo','unidadacademica.nombre as unidad','unidadacademica.facultad')
            ->where('horas.id','=',$materia)->get();

            //Query para obtener las herramientas
            $herramientas=DB::table('herramientas')
            ->select('id','nombre as herramienta')->get();

            return view('asistencia.create',['horarios'=>$horarios,'herramientas'=>$herramientas]);
           }
        // return $horarios;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $fechaejemplo=date('d-m-Y', strtotime($request->fecha));
        // $created = new Carbon($price->created_at);
        // $now = Carbon::now();
        // $difference = ($created->diff($now)->days < 1)
        // ? 'today'
        //      : $created->diffForHumans($now);
        // $date=Carbon::parse(Carbon::now());
        // $DeferenceInDays = Carbon::parse(Carbon::now())->diffInDays($request->fecha);

                // $date = Carbon::now(); // or $date = new Carbon();
        // $date->setISODate(2021,42); // 2016-10-17 23:59:59.000000
        
        // $fechaejemplo=$request->fecha->diff($request->fechaReposicion);

        $this->validate($request, [
            'herramientas' => 'required',
            'contenido'=>'required',
            'archivo' => 'max:8388600',

        ]);
        // FECHA: {{date('d-m-Y', strtotime($index->fecha_atraso)) }} (12-06-2018)

        
        $he=($request->get('herramientas'));
        $cadena=array_keys($he) ;
        $cadenaTexto = implode(", ", $cadena);
        // $cadenatxt=json_encode($cadena);
        // $cad=null;
        // $tam= count($cadena);
        // for ($i=0; $i<$tam; $i++) { 
        //     $cad=$cad."$cadena[$i]<br>";
        // }
        $asistencia= new Asistencia();
        $asistencia->usuario = $request->usuario;
        $asistencia->hora = $request->horaId;
        $asistencia->fecha = $request->fecha;
        $asistencia->tipoclase = $request->tipoclase;
        $asistencia->contenido = $request->contenido;
        $asistencia->plataforma = $request->plataforma;
        $asistencia->observacion = $request->observacion;
        $asistencia->herramientas =$cadenaTexto;
        $asistencia->fecharepo = $request->fechaReposicion;
        $asistencia->link =  $request->link;
        // $asistencia->archivos =  $request->archivo;
        // $asistencia->archivos =  $request->chek;

         //Handle File Upload
         if($request->hasFile('archivo')){

            //Get filename with the extension
            $filenamewithExt = $request->file('archivo')->getClientOriginalName();
            
            //Get just filename
            $filename = pathinfo($filenamewithExt,PATHINFO_FILENAME);
            
            //Get just ext
            $extension = $request->file('archivo')->guessClientExtension();
            
            //FileName to store
            $fileNameToStore = time().'.'.$extension;
            
            //Upload Image
            $path = $request->file('archivo')->storeAs('public/archivos',$fileNameToStore);
    
            $asistencia->archivos=$fileNameToStore;
           
            } 
            // else{
    
            //     $fileNameToStore="noimagen.jpg";
            // }
            
            // return $now;

            $inicio=Carbon::parse(Carbon::now())->startOfWeek()->toDateString(); 
        $fin=Carbon::parse(Carbon::now())->endOfWeek()->toDateString(); 
        if(($request->fecha>=$inicio)&&($request->fecha<=$fin)){
             $asistencia->save();
            return Redirect::to("asistencias");
        }else{
           $mensaje="La fecha de la clase selecionada No se puede registrar porque no es de esta semana";
            return redirect()->route('asistencias.create',array('materia'=>$request->horaId,'mensaje'=>$mensaje));
        }
            // $asistencia->save();
            // return Redirect::to("asistencias"); 
            // return redirect()->route('asistencias.index');
            
    
        // return $asistencia;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('asistencia.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Asistencia::findOrFail($id)->delete();
        return Redirect::to('asistencias');
    }
    public function generarReportePdf(Request $request){

        $fechaini=($request->get('fechainicio'));
        $fechafin=($request->get('fechafin'));
        $userId=Auth::user()->id;
       
           if($fechaini&&$fechafin){

            $fecha1=Carbon::parse($fechaini);
            $fecha2=Carbon::parse($fechafin);
            $datediff=$fecha1->diffInWeeks($fecha2);

            $reporte=DB::table('asistencias')
            ->join('users','asistencias.usuario','=','users.id')
            ->join('horas','asistencias.hora','=','horas.id')
            ->join('materias','materias.id','=','horas.materia')
            ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
            ->select('users.nombre','users.apellido',
            'materias.grupo','materias.nombre as materia',
            
            'unidadacademica.facultad','unidadacademica.nombre as unidad',DB::raw('count(asistencias.id) as totalregistro'),DB::raw('count(asistencias.id)*2 as cargahoraria'),DB::raw('count(asistencias.fecharepo) as totalrepo')
            )
            ->where('unidadacademica.jefe','=',$userId)
            ->whereBetween('asistencias.fecha', [$fechaini, $fechafin])

            ->groupBy('users.nombre','users.apellido','materias.grupo','materias.nombre',
            'unidadacademica.facultad','unidadacademica.nombre')->get();

            return \PDF::loadView('pdf.reporte',['asistencias'=>$reporte,'fechainicio'=>$fechaini,'fechafin'=>$fechafin,'semanas'=>$datediff])->setPaper('A4')->stream('control_asistencia.pdf');
           }else{

            $totalhorarios=DB::table('horas')
            ->join('asistencias','asistencias.hora','=','horas.id')
            ->select('horas.materia',DB::raw('count(horas.materia) as totalhoras'))
            ->whereBetween('asistencias.fecha', [$fechaini, $fechafin])
            ->groupBy('horas.materia')->get();

            $reporte=DB::table('asistencias')
            ->join('users','asistencias.usuario','=','users.id')
            ->join('horas','asistencias.hora','=','horas.id')
            ->join('materias','materias.id','=','horas.materia')
            ->join('unidadacademica','materias.unidad','=','unidadacademica.id')
            ->select('users.nombre','users.apellido',
            'materias.id as IDmateria','materias.nombre as materia','materias.grupo',
            
            'unidadacademica.facultad','unidadacademica.nombre as unidad',DB::raw('count(asistencias.usuario) as totalregistro'),DB::raw('count(asistencias.usuario)*2 as cargahoraria'),DB::raw('count(asistencias.fecharepo) as totalrepo')
            )
            ->where('unidadacademica.jefe','=',$userId)
            ->groupBy('users.nombre','users.apellido','materias.id','materias.nombre',
            'unidadacademica.facultad','unidadacademica.nombre','materias.grupo')->get();

            return \PDF::loadView('pdf.reporte',['asistencias'=>$reporte,'fechainicio'=>$fechaini,'fechafin'=>$fechafin,'semanas'=>NULL])->setPaper('A4')->stream('control_asistencia.pdf');
           }
    }
    public function auxiliar(Request $request){
          
        return  $request->all();
    }
}
