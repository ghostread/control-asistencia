<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnidadAcademica;
use Illuminate\Support\Facades\Redirect;
use DB;

class UnidadAcademicaController extends Controller
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
        // $unidad = DB::table('unidadacademica')->paginate(5);
        if($request){
            $sql=trim($request->get('buscarTexto'));
            $unidades=DB::table('unidadacademica')
            ->join('users','users.id','=','unidadacademica.jefe')
            ->select('unidadacademica.id','unidadacademica.nombre','unidadacademica.facultad','users.nombre as jefe','users.apellido')
            ->where('unidadacademica.nombre','LIKE','%'.$sql.'%')
            ->orderBy('id','desc')
            ->paginate(5);


            //Consulta de jefes de unidad
            $jefes=DB::table('users')
            ->select('users.id','users.nombre')
            ->where('rol','=','2')->get();

            return view('UnidadAcademica.index',['unidades'=>$unidades,'buscarTexto'=>$sql,'jefes'=>$jefes]);
            // return $unidades;
        }
        // return view('UnidadAcademica.index',['unidades'=>$unidades,'buscarTexto'=>$sql]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $this->validate($request, [
        //     'nombre' => 'required',
        //     'facultad'=>'required',
        //     'jefe'=>'required',
        // ]);

        $unidad = new UnidadAcademica(); 
        $unidad->nombre=$request->nombre;
        $unidad->facultad=$request->facultad;
        $unidad->jefe=$request->jefe;
        $unidad->save();
        return Redirect::to('unidadacademica');
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
        $unidad=DB::table('unidadacademica')->where('id',$id)->first();
        return view('UnidadAcademica.editform',compact('unidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function update(Request $request,$id)
    {
        $unidad =UnidadAcademica::findOrFail($request->id_unidad); 
        $unidad->nombre=$request->nombre;
        $unidad->facultad=$request->facultad;
        $unidad->save();

        // $unidad= UnidadAcademica::findOrFail($id);
        // $unidad->update($request->all());
        // return Redirect::to('unidadacademica',);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // DB::table('unidadacademica')->where('id',$id)->delete();
        UnidadAcademica::findOrFail($id)->delete();
        return Redirect::to('unidadacademica');
    }
}
