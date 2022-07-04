<?php

namespace App\Http\Controllers;

use App\Models\citas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //dd(request());
        $valor = request('valor');
        if ($valor == 1) {
            dd($valor);
            return 'hola';
        } else {

            $date = date_create(date(""), timezone_open('America/La_Paz'));
            $date = date_format($date, 'Y-m-d');
            if (Cache::has('citas' . $date)) {
                $cita_fecha =  Cache::get('citas' . $date);
            } else {
                $cita_fecha = DB::table('citas')
                    ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                    ->where('fecha', $date)
                    ->get();
                Cache::put('citas' . $date, $cita_fecha);
            }
            //$date = DB::table('citas')->where('fecha', $date)->get();
        }
        //  dd($date);
        //dd($users);
        return Inertia::render('Comenzar', ['fechas' => $cita_fecha, 'valor' => $valor]);
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
        //

    }
    public static function guardar(Request $request)
    {
        //
        $persona_nueva = [
            'nombre' =>  $request['pn']['nombre'],
            'ap_paterno' =>  $request['pn']['ap_paterno'],
            'ap_materno' =>  $request['pn']['ap_materno'],
            'celular' =>  $request['pn']['celular'],
            'correo' =>  $request['pn']['correo'],
            'expendido' =>  $request['pn']['expendido'],
            'fecha_nacimiento' =>  $request['pn']['fecha_nacimiento'],
            'ci' =>  $request['pn']['ci'],
            'direccion' =>  $request['pn']['direccion'],
            'sexo' =>  $request['pn']['sexo'],
            'nom_municipio' => 'LA PAZ',
        ];
        try {
            DB::table('persona_citas')->insert([$persona_nueva]);
        } catch (\Throwable $th) {
            return $th;
        }
        return 'OK';
    }
    public static function update_persona(Request $request)
    {
        //
        $ci =  $request['pn']['ci'];
        $persona_nueva = [
            'nombre' =>  $request['pn']['nombre'],
            'ap_paterno' =>  $request['pn']['ap_paterno'],
            'ap_materno' =>  $request['pn']['ap_materno'],
            'celular' =>  $request['pn']['celular'],
            'correo' =>  $request['pn']['correo'],
            'expendido' =>  $request['pn']['expendido'],
            'fecha_nacimiento' =>  $request['pn']['fecha_nacimiento'],
            'direccion' =>  $request['pn']['direccion'],
            'sexo' =>  $request['pn']['sexo'],
            'nom_municipio' => 'LA PAZ',
        ];
        try {
            $resp = DB::table('persona_citas')
                ->where('ci',$ci)
                ->update($persona_nueva);
        } catch (\Throwable $th) {
            return $th;
        }
           
        return 'OK';
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function show($date)
    {
        //
        if (Cache::has('citas' . $date)) {
            $cita_fecha = Cache::get('citas' . $date);
        } else {
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }
        return $cita_fecha;
    }
    public static function mostrar($date)
    {
        //
        if (Cache::has('citas' . $date)) {
            $cita_fecha = Cache::get('citas' . $date);
        } else {
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }
        return $cita_fecha;
    }

    public static function usuario($ci)
    {
        //
        //if (Cache::has('usuario' . $ci)) {
        //$cita_fecha = Cache::get('citas' . $ci);
        //}else {
        $cita_fecha = DB::table('persona_citas')
            ->where('persona_citas.ci', $ci)
            ->get();
        //Cache::put('usuario' . $ci, $cita_fecha);
        //}
        return $cita_fecha;
    }
    public static function cita_usuario($ci)
    {
        //
        //if (Cache::has('usuario' . $ci)) {
        //$cita_fecha = Cache::get('citas' . $ci);
        //}else {
        $cita_fecha = DB::table('citas')
            ->where('citas.ci', $ci)
            ->get();
        //Cache::put('usuario' . $ci, $cita_fecha);
        //}
        return $cita_fecha;
    }
    public static function citas_disponibles($fecha)
    {
        //
        //if (Cache::has('usuario' . $ci)) {
        //$cita_fecha = Cache::get('citas' . $ci);
        //}else {
        $cita_fecha = DB::table('citas')
            ->select(['hora_inicio', DB::raw('sum(case equipo WHEN 1 then 1 else 0 end) AS GRUPO1, 
                sum(case equipo WHEN 2 then 1 else 0 end) AS GRUPO2, 
                sum(case equipo WHEN 3 then 1 else 0 end) AS GRUPO3, 
                sum(case equipo WHEN 4 then 1 else 0 end) AS GRUPO4')])
            ->where('fecha', $fecha)
            ->groupBy('hora_inicio')
            ->get();

        //Cache::put('usuario' . $ci, $cita_fecha);
        //}
        $citas = [
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00"),
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00"),
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00"),
            array("08:30:00", "09:30:00", "10:30:00", "11:30:00", "12:30:00", "14:00:00")
        ];
        $prueba = [];
        $valor = "";
        foreach ($cita_fecha as $value) {
            foreach ($value as $k => $v) {
                if ($k == 'hora_inicio') {
                    $valor = $v;
                    array_push($prueba, $valor);
                }
                if ($k == 'grupo1' && $v == 1) {
     
                    if (($key = array_search($valor, $citas[0])) !== false) {
                        unset($citas[0][$key]);
                    }
                }
                if ($k == 'grupo2' && $v == 1) {
                    if (($key = array_search($valor, $citas[1])) !== false) {
                        unset($citas[1][$key]);
                    }
                }
                if ($k == 'grupo3' && $v == 1) {
                    if (($key = array_search($valor, $citas[2])) !== false) {
                        unset($citas[2][$key]);
                    }
                }
                if ($k == 'grupo4' && $v == 1) {
                    if (($key = array_search($valor, $citas[3])) !== false) {
                        unset($citas[3][$key]);
                    }
                }
            }
        }

        return $citas;
    }
    public static function dar_cita(Request $resquest)
    {
        $cita = request('cita');
        $date = $cita['fecha'];
        try {
            $resp = DB::table('citas')
                ->insert($cita);
        } catch (\Throwable $th) {
            return $th;
        }
        if (Cache::has('citas' . $date)) {
            Cache::forget('citas' . $date);
            $cita_fecha = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();
            Cache::put('citas' . $date, $cita_fecha);
        }
        return 'ok';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public static function eliminar_cita(Request $request){
        

        $cita = $request['cita'];
        
        return $cita;
    }
    public static function eliminar_cita2(Request $request){
        
        /*$cita = $request['cita'];
        $cita_fecha = DB::table('citas')->select('*')
        ->where('fecha', $cita['fecha'])
        ->where('equipo', $cita['equipo'])
        ->where('hora_inicio', $cita['hora_inicio'])
        ->delete();
        Cache::forget('citas' . $cita['fecha']);
        */return $request['cita'];
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
        //
    }
}
