<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\DB;

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

            $users = DB::table('citas')
                ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
                ->where('fecha', $date)
                ->get();

            $date = DB::table('citas')->where('fecha', $date)->get();
        }
        //  dd($date);
        //dd($users);
        return Inertia::render('Comenzar', ['fechas' => $users, 'valor'=>$valor]);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $users = DB::table('citas')
            ->join('persona_citas', 'citas.ci', '=', 'persona_citas.ci')
            ->where('fecha', $id)
            ->get();
        return $users;
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
