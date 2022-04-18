<?php

namespace App\Http\Controllers;

use App\Models\Persona_cita;
use App\Http\Requests\StorePersona_citaRequest;
use App\Http\Requests\UpdatePersona_citaRequest;
use App\Models\Persona_atencion;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
class PersonaCitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = DB::select('select * from persona_citas');
        $mensaje ='';
        //$pa = personas_antiguo::select('select * from users);
        return Inertia::render('Administrador', ['pacientes'=>$users, 'mensaje'=>$mensaje]);
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
     * @param  \App\Http\Requests\StorePersona_citaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersona_citaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Persona_cita  $persona_cita
     * @return \Illuminate\Http\Response
     */
    public function show(Persona_cita $persona_cita)
    {
        
        /*$users = DB::table('citas')->where('fecha', '18/04/2022')->get();;
        return $users;*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Persona_cita  $persona_cita
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona_atencion $persona_cita)
    {
        //dd(request('persona_cita'));
        $persona_cita =request('persona_cita');
        DB::table('persona_citas')->where('ci', $persona_cita['ci'])->update($persona_cita);
        $users = DB::select('select * from persona_citas');
        //$pa = personas_antiguo::select('select * from users);
        return redirect()->back()->with('mensaje', 'Yay it worked');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePersona_citaRequest  $request
     * @param  \App\Models\Persona_cita  $persona_cita
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersona_citaRequest $request, Persona_cita $persona_cita)
    {
        //
        $users = DB::select('select * from citas');
        return "ciars ".$users;  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Persona_cita  $persona_cita
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona_cita $persona_cita)
    {
        //
    }
}
