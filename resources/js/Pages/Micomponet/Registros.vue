<template>
<app-layout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <v-card>
                    <v-container>
                        <v-form ref="formcita" @submit.prevent="">

                            <div id="main" class="grid grid-cols-2 gap-1 justify-evenly">
                                <v-text-field v-model="ci" type="text" label="Carnet" @keyup.enter="buscar_ci(ci)">
                                </v-text-field>
                                <v-select :items="departamentos" filled_label="Filled style">
                                </v-select>

                            </div>
                            <div id="main" class="grid grid-cols-3 gap-1 justify-evenly">
                                <v-text-field v-model="nombre" :rules="nombreRules" type="text" label="Nombre">
                                </v-text-field>
                                <v-text-field v-model="materno" type="text" label="Apellido Materno">
                                </v-text-field>
                                <v-text-field v-model="paterno" type="text" label="Apellido Paterno">
                                </v-text-field>

                            </div>

                            <div class="grid grid-cols-2 gap-1 justify-evenly">
                                <v-text-field v-model="correo" type="email" label="Correo">
                                </v-text-field>
                                <v-text-field v-model="numero" type="text" label="Numero Celular">
                                </v-text-field>
                            </div>
                            <div class="grid grid-cols-5 gap-1 align-content: flex-start justify-evenly">
                                <v-text-field v-model="fecha_cita" class="col-span-2" type="date" label="Fecha de la cita">
                                </v-text-field>
                                <v-text-field v-model="tiempo_inicio" type="time" label="Hora de inicio">
                                </v-text-field>
                                <v-text-field v-model="timepo_fin" type="time" label="Hora de finalizacion">
                                </v-text-field>
                                <v-text-field v-model="color" type="color" label="color (click aqui)">
                                </v-text-field>
                            </div>
                            <!--<div class="grid grid-cols-2 gap-1 justify-evenly">
                                    <v-subheader>
                                        Seleccione un Equipo
                                    </v-subheader>
                                    <v-select v-model="selectequipo" :items="equipo" label="Selecciona el equipo" dense outlined></v-select>

                                </div>-->

                            <v-btn type="submit" class="mr-4" >
                                Aceptar
                            </v-btn>
                        </v-form>
                    </v-container>
                </v-card>
            </div>
        </div>
    </div>
</app-layout>
</template>

<script>
import {Inertia} from '@inertiajs/inertia';
import moment from 'moment'
import AppLayout from '@/Layouts/AppLayout'
const day1 = new Date();
export default {
    components: {
        AppLayout,

    },
    props: {
        persona_antigua: Array,
    },
    data: () => ({
        focus: '',
        name: null,
        details: null,
        start: null,
        end: null,
        color: "#1976D2",
        dialog: false,
        nombre: null,
        paterno: null,
        materno: null,
        ci: null,
        departamento: null,
        correo: null,
        numero: null,
        fecha_cita: null,
        tiempo_inicio: null,
        timepo_fin: null,
        departamentos: ['LP', 'OR', 'CH', 'CB', 'PT', 'TJ', 'SC', 'BE', 'PD'],
        selectequipo: null,
        nombreRules: [v => !!v || 'Se requiere el dato'],
    }),
    methods: {
        async buscar_ci(val) {
            Inertia.post(route('persona_antigua.mostrar'),  { preserveState: true } );
        },
    }

}
</script>
