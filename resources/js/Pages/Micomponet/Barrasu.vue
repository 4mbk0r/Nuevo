<template>
<v-app>
    <div>
        <v-container>
            <div v-if="msg.fecha_validacion" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">{{msg.fecha_validacion}}</strong>
            </div>
            <v-row>
                <v-col>
                    <v-sheet height="64">
                        <v-toolbar>
                            <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                                Hoy
                            </v-btn>
                            <!--onkeydown="return false"-->
                            <!--<v-text-field v-model="fecha_uso"  :min="fechacitaMin" :rules="[ fechaValida ]" type="date" label="Fecha De Cita" >
                        </v-text-field>-->
                            <input id="txtDate" type="date" refs="seldate" onkeydown="return false" v-model="fecha_hoy" @change="formatDate" />
                            <v-btn fab text small color="grey darken-2" @click="prev">
                                <v-icon small>
                                    mdi-chevron-left
                                </v-icon>
                            </v-btn>
                            <v-btn fab text small color="grey darken-2" @click="next">
                                <v-icon small>
                                    mdi-chevron-right
                                </v-icon>
                            </v-btn>
                            <v-toolbar-title v-if="$refs.calendar">
                                <!--{{ $refs.calendar.title }}-->
                                {{ textoDate(fecha_hoy) }}
                            </v-toolbar-title>
                            <v-spacer></v-spacer>
                            <button type="button" @click="ventana_modal" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Agregar</button>

                        </v-toolbar>

                    </v-sheet>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-sheet height="500">
                        <!--@change=""-->
                        <v-calendar ref="calendar" v-model="focus" color="primary" type="category" category-show-all :categories="categories" :events="events" :event-color="getEventColor" @click:event="showEvent" :first-interval=7 :interval-minutes=60 :interval-count=12></v-calendar>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-toolbar dark color="#1CA698">
                <v-btn icon dark @click="cerrar_dialog">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Datos Personales</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <buscar></buscar>
            <v-card color="#1CA698">

                <v-container>

                    <v-form ref="formcita" @submit.prevent="addEvent">
                        <v-alert v-if="msg_usuario=='Nuevo'" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">El usuario es {{msg_usuario}}</strong>
                        </v-alert>

                        <v-alert v-if="msg_usuario=='Antiguo'" dense border="left" type="warning">
                            <strong class="font-bold">El usuario es {{msg_usuario}}</strong>
                        </v-alert>

                        <v-container ma-0 pa-0>
                            <v-row no-gutters>
                                <v-col cols="10" md="6">
                                    <v-text-field v-model="ci" type="text" pa-0 solo @change="prueba" label="Carnet" required></v-text-field>
                                </v-col>

                                <v-col cols="10" md="6">
                                    <v-select v-model="sdeparamento" pa-0 solo :items="departamentos" :rules="selectRules" label="Expedido">
                                    </v-select>
                                </v-col>
                            </v-row>
                        </v-container>

                        <v-container v-if="datos_usuario" ma-0 pa-0>
                            <v-row outlined>
                                <v-col md="auto" sm="auto" xs="auto" align-center justify-center>
                                    <v-btn icon color="#f2f2f2" @click="informacion= !informacion">
                                        <v-icon v-if="informacion">
                                            mdi-minus
                                        </v-icon>
                                        <v-icon v-else>
                                            mdi-plus
                                        </v-icon>

                                    </v-btn>
                                    <strong class="white--text">Informacion Del Usuario</strong>
                                </v-col>
                            </v-row>
                            <v-container v-if="informacion" ma-0 pa-0>
                                <v-row no-gutters>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="nombre" :rules="nombreRules" type="text" pa-0 solo label="Nombre">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="paterno" :rules="nombreRules" type="text" pa-0 solo label="Apellido Paterno">
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="materno" :rules="nombreRules" type="text" pa-0 solo label="Apellido Materno">
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col cols="10" md="6">
                                        <v-text-field v-model="correo" type="email" label="Correo" pa-0 solo>
                                        </v-text-field>
                                    </v-col>

                                    <v-col cols="10" md="6" pa-0 solo>
                                        <v-text-field v-model="numero" type="text" label="Numero Celular" pa-0 solo>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="fecha_nacimiento" :min="minFechaNac" :max="maxFechaNac" type="date" label="Fecha de nacimiento" pa-0 solo>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-text-field v-model="direccion" type="text" label="Direccion" pa-0 solo>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" md="4">
                                        <v-select v-model="ssexo" :items="sexo" color="purple darken-3" label="Sexo" pa-0 solo>
                                        </v-select>
                                    </v-col>
                                </v-row>
                            </v-container>
                            <v-row align="center" justify="center">
                                <v-col>
                                    <v-btn type="submit" color="primary" class="mr-4" @click="Guardar_datos">
                                        Guardar Datos
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn color="primary" class="mr-4" @click="ventana_agendar">
                                        Dar cita
                                    </v-btn>
                                </v-col>
                            </v-row>
                            <v-spacer>
                            </v-spacer>

                        </v-container>
                        <v-container v-if="desserts.length>=1">
                            <v-row outlined>
                                <v-col md="auto" sm="auto" xs="auto" align-center justify-center>
                                    <v-btn icon color="#f2f2f2" @click="Historia= !Historia">
                                        <v-icon v-if="Historia">
                                            mdi-minus
                                        </v-icon>
                                        <v-icon v-else>
                                            mdi-plus
                                        </v-icon>
                                    </v-btn>
                                    <strong class="white--text">Historia de Usuario</strong>
                                </v-col>
                            </v-row>
                            <div v-if="Historia">
                                <v-data-table :headers="headers" :footer-props="{itemsPerPageText: 'Pacientes por pagina'}" :items="desserts" :sort-desc.sync="sortDesc" sort-by="fecha" class="elevation-1 cyan lighten-3">
                                    <template v-slot:item.actions="{ item }">
                                        <v-icon small class="mr-2" @click="editItem(item)">
                                            mdi-pencil
                                        </v-icon>
                                        <v-icon small @click="deleteItem(item)">
                                            mdi-delete
                                        </v-icon>
                                    </template>
                                </v-data-table>
                            </div>
                        </v-container>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>

        <v-dialog v-model="v_agendar" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-toolbar dark color="#1CA698">
                <v-btn icon dark @click="cerrar_agendar">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>AGENDAR</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-card>
                <v-container>

                    <v-form ref="form_cita" @submit.prevent="">

                        <div class="text-center">
                            <!--
                                    <v-btn class="ma-2" :loading="loading2" :disabled="loading2" color="success" @click="loader = 'loading2'">
                                        Cita anterior
                                        <template v-slot:loader>
                                            <span>Loading...</span>
                                        </template>
                                    </v-btn>
                                    -->
                            <v-btn class="ma-2" color="info" @click="loader = 'loading4'">
                                Cita posterior
                                <template v-slot:loader>
                                    <span class="custom-loader">
                                        <v-icon light>mdi-cached</v-icon>
                                    </span>
                                </template>
                            </v-btn>
                            <div id="main" class="grid grid-cols-3 gap-1 justify-evenly">
                                <v-text-field v-model="fecha_cita_actual" :rules="nombreRules" :min="fechacitaMin" @change="change_fecha2" type="date" label="Fecha de Cita">
                                </v-text-field>
                                <div>
                                    <v-select v-model="equipo_actual" :items="equipos_actuales" :rules="selectRules" @change="cambioequipo" color="purple darken-3" label="Equipo">
                                    </v-select>
                                </div>
                                <div>
                                    <v-select v-model="t_equipo" :items="tiempos_actuales" :rules="selectRules" color="purple darken-3" label="Hora de inicio">
                                    </v-select>
                                </div>
                            </div>
                            <div>

                                <v-select v-model="ttipocita" :items="tipo_cita" color="purple darken-3" label="Tipo de cita">
                                </v-select>
                                <v-select v-model="tlugar" :items="ttlugares" color="purple darken-3" label="Lugar">
                                </v-select>
                                <v-text-field v-model="tobservacion" type="text" label="Observacion">
                                </v-text-field>
                            </div>
                        </div>
                        <v-btn type="submit" color="primary" class="mr-4" @click="guardar_cita" @click.stop="v_agendar=false">
                            Guardar Cita
                        </v-btn>
                        <v-btn type="submit" color="primary" class="mr-4" @click="imprimir_directo" @click.stop="v_agendar=false">
                            Boleta
                        </v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="v_editar_agendar" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-toolbar dark color="#1CA698">
                <v-btn icon dark @click="cerrar_editar_cita">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>v_editar_agendar</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-card>
                <v-container>

                    <v-form ref="form_cita_edit" @submit.prevent="">
                        <div id="main" class="text-center">
                            <div class="grid grid-cols-2 gap-1 justify-evenly">
                                <div>
                                    <v-text-field v-model="editar.ci" label="Cedula de identidad">
                                    </v-text-field>
                                </div>
                                <div class="text-black">
                                    <v-select label="Expedido" v-model="editar.nom_municipio" :items="departamentos" :rules="selectRules">
                                    </v-select>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-1 justify-evenly">
                                <div>
                                    <v-text-field v-model="editar.nombre" label="Nombre">
                                    </v-text-field>
                                </div>
                                <div>
                                    <v-text-field v-model="editar.ap_paterno" label="Apellido Paterno">
                                    </v-text-field>
                                </div>
                                <div>
                                    <v-text-field v-model="editar.ap_materno" label="Apellido Materno">
                                    </v-text-field>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-1 justify-evenly">
                                <div>
                                    <v-text-field v-model="editar.fecha" :rules="nombreRules" :min="fechacitaMin" @change="change_fecha(editar)" type="date" label="Fecha de Cita">
                                    </v-text-field>
                                </div>
                                <div>
                                    <v-select v-model="editar.equipo" :items="equipos_actuales" :rules="selectRules" @change="cambioequipos(editar)" color="purple darken-3" label="Equipo">
                                    </v-select>
                                </div>
                                <div>
                                    <v-select v-model="editar.hora_inicio" :items="tiempos_actuales" :rules="selectRules" color="purple darken-3" label="Hora de inicio">
                                    </v-select>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-1 justify-evenly">
                                <div>
                                    <v-select v-model="editar.tipo_cita" :items="tipo_cita" color="purple darken-3" label="Tipo de cita">
                                    </v-select>
                                </div>
                                <div>
                                    <v-select v-model="editar.lugar" :items="ttlugares" color="purple darken-3" label="Lugar">
                                    </v-select>
                                </div>
                                <div>
                                    <v-text-field v-model="editar.observacion" type="text" label="Observacion">
                                    </v-text-field>
                                </div>
                            </div>
                        </div>
                        <v-btn type="submit" color="primary" class="mr-4" @click="guardar_cita_edit" @click.stop="v_agendar=false">
                            Guardar
                        </v-btn>
                        <v-btn type="submit" color="primary" class="mr-4" @click="eliminar_cita">
                            Eliminar
                        </v-btn>
                    </v-form>
                </v-container>
            </v-card>
        </v-dialog>

        <v-dialog id="modalInvoice" v-model="v_imprimir" fullscreen transition="dialog-bottom-transition">
            <v-toolbar dark color="#1CA698">
                <v-btn icon dark @click="v_imprimir=false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Boleta</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <v-card>
                <div class="center_columna .item1">
                    <v-btn @click="imprimir_boleta">
                        imprimir
                    </v-btn>
                </div>
                <div id="print">
                    <div class="center_columna .item1">
                        <div><img src="https://pbs.twimg.com/profile_images/1405210297036525568/0KEhDx7D_400x400.jpg" width="100px" height="100px"></div>
                        <div class="aling">
                            <p class="titulo">Gobierno Autónomo Departamental de La Paz</p>
                            <p class="titulo2">SERVICIO DEPARTAMENTAL DE SALUD <br>
                                UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y DISCAPACIDAD</p>
                            <p class="titulo3">UTRAID - LA PAZ</p>
                        </div>
                        <div><img src="https://scontent.flpb1-1.fna.fbcdn.net/v/t1.6435-9/195975552_5609812179090371_6523992678396965776_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=174925&_nc_ohc=Q__ncnQ9wdsAX9F1k9j&_nc_ht=scontent.flpb1-1.fna&oh=00_AT9AKy3kTnKXSiEuySxYnEqyl5t_M59YrL0o4MECdWlANA&oe=62B0F082" width="100px" height="100px"></div>
                    </div>
                    <div class="wrapper alinear_elemento">
                        <div class="box one">
                            <label>NOMBRE COMPLETO:</label>
                            <label class="texto_nombre">{{selectedEvent.nombre}} {{selectedEvent.ap_paterno}} {{selectedEvent.ap_materno}}</label>
                        </div>
                        <div class="box two">
                            <label>Fecha:</label>
                            <p>{{textoDate(selectedEvent.fecha)}} </p>
                        </div>
                        <div class="box three">
                            <label>Hora:</label>
                            <p>{{selectedEvent.hora}} </p>
                        </div>
                        <div class="box four">
                            <label>
                                Lugar de Evaluacion:
                            </label>
                            <p>{{selectedEvent.lugar}}</p>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="alinear_elemento">
                            <label class="label">Observaciones: </label>
                            <textarea class="observaciones" rows="2" cols="100" v-model="selectedEvent.observacion">{{selectedEvent.observacion}}</textarea>
                        </div>
                    </div>
                    <div>
                        <label class="texto_mediano">
                            LA FALTA DE DOCUMENTACION Y EL RETRASO DE 10 MINUTOS DARA LUGAR A REPROGRAMAR<br>
                            PRESENTARSE 10 MINUTOS ANTES DE LA HORA DE PROGRAMACION<br>
                            DE NO ASISTIR INFORMAR CON 48 HORAS DE ANTICIPACION TELEFONO 2412391 UTRAID<br>
                            TRAER: 2 FOTOCOPIAS C.I., 1 COPIA CARNET DISCAPACIDAD, INFORME MEDICO ORIGINAL, CROQUIS DOMICILIO<br>
                            PRESENTAR SU DOCUMENTACION EN FOLDER AMARILLO TAMAÑO OFICIO CON FASTENER</label>
                    </div>

                </div>

            </v-card>
        </v-dialog>

        <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
            <v-card color="grey lighten-4" min-width="350px" flat>
                <v-toolbar :color="selectedEvent.color" dark>
                    <v-btn icon @click="guardar_se_presento">
                        <v-icon>mdi-content-save</v-icon>
                    </v-btn>
                    <v-toolbar-title v-html="selectedEvent.nombre"></v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon @click="editar_cita">
                        <v-icon>mdi-table-edit</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card-text>
                    <span v-html="selectedEvent.details"></span>
                    <label class="font-bold">
                        Carnet: {{selectedEvent.ci}}
                    </label><br>
                    <label class="font-bold">
                        Nombre Completo: {{selectedEvent.name}} {{selectedEvent.ap_paterno}} {{selectedEvent.ap_materno}}
                    </label><br>
                    <label class="font-bold">
                        Tipo de cita: {{selectedEvent.tipo_cita}}
                    </label><br>
                    <label class="font-bold">
                        Se presento:
                        <v-select v-model="selectedEvent.se_presento" :items="opsepresento" :menu-props="{ top: true, offsetY: true }" label=""></v-select>
                    </label><br>

                    <label class="font-bold" v-if="selectedEvent.se_presento=='Si'">
                        Fue Atendido:
                        <v-select v-model="selectedEvent.ci_doctor" :dark="color" hint="Pick your meal" item-text="nombre" item-value="ci" :items="doctores" :menu-props="{ top: true, offsetY: true }" label=""></v-select>
                    </label>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" @click="imprimir">
                        Boleta
                    </v-btn>
                    <v-btn text color="secondary" @click="selectedOpen = false">
                        Cancel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>

    </div>
</v-app>
</template>

<script>
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)
import buscar from '@/Pages/Micomponet/Buscar'
export default {
    components: {
        buscar,
    },
    props: {
        datos_cita: Array,
    },
    data: () => ({
        headers: [{
                text: 'Fecha de cita',
                align: 'start',
                //sortable: false,
                value: 'fecha',
            },
            {
                text: 'Hora de inicio',
                value: 'hora_inicio'
            },
            {
                text: 'Tipo de cita',
                value: 'tipo_cita'
            },
            {
                text: 'Se presento',
                value: 'se_presento'
            },
            {
                text: 'Equipo',
                value: 'equipo'
            },
            {
                text: 'Observacion',
                value: 'observacion'
            },
            {
                text: 'Accion',
                value: 'actions',
                sortable: false
            },
        ],
        focus: '',
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party', 'Cita'],
        categories: ['Equipo 1', 'Equipo 2', 'Equipo 3', 'Equipo 4'],
        equipo: [1, 2, 3, 4],
        fecha_hoy: day1,
        fecha_uso: day1,
        maxFechaNac: day1,
        minFechaNac: '1899-01-01',
        anterior_dia: '',
        fecha_hoy_actual: day1,
        fechacitaMin: day1,
        name: null,
        details: null,
        start: null,
        end: null,
        color: "#1976D2",
        dialog: false,
        v_agendar: false,
        nombre: null,
        paterno: null,
        materno: null,
        ci: null,
        departamento: null,
        correo: '',
        numero: '',
        fecha_nacimiento: null,
        direccion: null,
        sexo: ['No se tiene registro', 'Masculino', 'Femenino'],
        ssexo: 'No se tiene registro',
        fecha_cita: null,
        tiempo_inicio: null,
        timepo_fin: null,
        departamentos: ['No se tiene registro', 'LP', 'OR', 'CH', 'CB', 'PT', 'TJ', 'SC', 'BE', 'PD'],
        sdeparamento: 'No se tiene registro',
        selectequipo: null,
        nombreRules: [v => !!v || 'Se requiere el dato'],
        selectRules: [v => v != 'No se tiene registro' || 'Se requiere el dato'],
        rules: [
            v => !!v || 'Se requiere dato',
            v => v >= minFechaNac || 'El valor es menor',
            v => v <= maxFechaNac || 'El valor es mayor',
        ],
        items: ['Dr Mamani', 'Dr Magi', 'Dr Fernandez', 'Dr Kantuta'],
        opsepresento: ['Si', 'No'],
        msg: [],
        datos_usuario: null,
        msg_usuario: null,
        desserts: [],
        fecha_cita_actual: "",
        hora_inicial: null,
        equipo_actual: null,
        informacion: true,
        Historia: true,
        //equipos
        equipos_actuales: [],
        tiempos_actuales: [],
        lista_tiempos: {},
        t_equipo: null,
        //ventana modal 
        selectedEvent: {},
        selecevent: {},
        selectedElement: null,
        selectedOpen: false,
        v_carga: false,
        pendingRequests: 0,
        totalRequests: 0,
        resx: '',
        seprento: '',
        tobservacion: '',
        sortDesc: true,
        tipo_cita: ['RECALIFICADO', 'NUEVO'],
        doctores: [],
        ttipocita: 'NUEVO',
        ttlugares: ["CALLE MUÑOZ CORNEJO NRO 2702 - ESQUINA MENDEZ ARCOS - SOPOCACHI",
            "TELEFERICO MORADO - FARO MURILLO",
            "TELEFERICO PLATEADO - CIUDAD SATELITE",
            "IXIAMAS",
            "SAN BUENAVENTURA",
            "APOLO",
            "AUCAPATA",
            "AYATA",
            "CHUMA",
            "CURVA",
            "Gral. J.J. Pérez(CHARAZANI",
            "PELECHUCO",
            "ESCOMA",
            "HUMANATA",
            "MOCOMOCO",
            "PUERTO ACOSTA",
            "PUERTO CARABUCO",
            "ACHACACHI",
            "ANCORAIMES",
            "CHUA COCANI",
            "COMBAYA",
            "HUARINA",
            "HUATAJATA",
            "QUIABAYA",
            "SANTIAGO DE HUATA",
            "SORATA",
            "TACACOMA",
            "BATALLAS",
            "COPACABANA",
            "PUCARANI",
            "PUERTO PEREZ",
            "SAN PEDRO DE TIQUINA",
            "TITO YUPANQUI (Parquipujio)",
            "ACHOCALLA",
            "MECAPACA",
            "PALCA",
            "VIACHA",
            "ALTO BENI",
            "CARANAVI",
            "GUANAY",
            "MAPIRI",
            "PALOS BLANCOS",
            "TEOPONTE",
            "TIPUANI",
            "CHULUMANI (V. De La Libertad)",
            "CORIPATA",
            "COROICO",
            "IRUPANA",
            "LA ASUNTA",
            "YANACACHI",
            "DESAGUADERO",
            "GUAQUI",
            "JESUS DE MACHACA",
            "LAJA",
            "SAN ANDRES DE MACHACA",
            "TARACO",
            "TIAHUANACU",
            "CALACOTO",
            "CAQUIAVIRI",
            "CATACORA",
            "CHARAÑA",
            "COMANCHE",
            "COROCORO",
            "NAZACARA DE PACAJES",
            "SANTIAGO DE MACHACA",
            "CHACARILLA",
            "PAPEL PAMPA",
            "PATACAMAYA",
            "SAN PEDRO CURAHUARA",
            "SANTIAGO DE CALLAPA",
            "SICA SICA (Villa Aroma)",
            "UMALA",
            "AYO AYO",
            "CALAMARCA",
            "COLLANA",
            "COLQUENCHA",
            "SAPAHAQUI",
            "WALDO BALLIVIAN",
            "CAIROMA",
            "LURIBAY",
            "MALLA",
            "YACO",
            "CAJUATA",
            "COLQUIRI",
            "ICHOCA",
            "INQUISIVI",
            "QUIME",
            "VILLA LIBERTAD LICOMA"
        ],
        tlugar: "CALLE MUÑOZ CORNEJO NRO 2702 - ESQUINA MENDEZ ARCOS - SOPOCACHI",
        v_imprimir: false,
        v_editar_agendar: false,
        editar: {},
    }),
    mounted() {
        this.$refs.calendar.checkChange()
    },
    created() {
        this.initialize()
    },
    methods: {
        async editItem(item) {
            console.log(item);
            this.v_editar_agendar = true
            this.editar = item;

            Object.assign(this.editar, {
                hora: this.editar.hora_inicio
            });

            this.tiempos_actuales = [];
            console.log("---este es un evento---")
            console.log(this.editar)

            this.selecevent = this.selectedEvent;
            this.selectedEvent = item;
            await this.change_fecha(this.editar);
            this.v_editar_agendar = true;
            this.tiempos_actuales = this.lista_tiempos['' + this.editar.equipo];
            //this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            this.tiempos_actuales.push();
        },
        async deleteItem(item) {
            await this.eliminar_cita2(item);
            var b = await axios.post('api/datos_citas/' + item.ci).then();
            this.desserts = b['data'];
            console.log(b);
            if (this.desserts.length == 0) {
                this.desserts = [];
            }

        },
        async guardar_se_presento() {
            var res = await axios({
                method: 'post',
                url: 'api/guardar_datos',
                data: {
                    cita: this.selectedEvent,
                }
            }).then();
            this.traerdatos();

        },
        cerrar_editar_cita() {
            this.v_editar_agendar = false
        },
        async editar_cita() {

            this.tiempos_actuales = [];
            console.log("_____este es un evento____")
            console.log(this.selectedEvent)
            this.editar = this.selectedEvent
            this.selecevent = {
                fecha: this.selectedEvent.fecha,
                equipo: this.selectedEvent.equipo,
                hora: this.selectedEvent.hora
            }
            await this.change_fecha(this.editar);
            this.v_editar_agendar = true;

            this.tiempos_actuales = this.lista_tiempos['' + this.editar.equipo];
            
            this.tiempos_actuales.push(this.editar.hora_inicio);
            this.tiempos_actuales.sort()
            this.editar.hora_inicio=this.editar.hora_inicio

        },
        cerrar_agendar() {
            this.v_agendar = false;
        },
        ventana_agendar() {
            this.v_agendar = true;
            this.change_fecha2();
        },
        limipiar_form() {
            this.ci = "";
            this.nombre = '';
            this.paterno = '';
            this.materno = '';
            this.correo = '';
            this.numero = '';
            this.fecha_nacimiento = '';
            this.direccion = '';
            this.msg_usuario = '';
            this.ssexo = 'No se tiene registro';
            this.sdeparamento = 'No se tiene registro';
            this.desserts = [];
            this.datos_usuario = null;
            this.fecha_nacimiento = "";
            this.informacion = true;

            //this.$refs.formcita.reset();
        },
        cerrar_dialog() {
            this.dialog = false;
            this.limipiar_form();

        },
        imprimir_directo() {
            this.selectedEvent = {
                fecha: this.fecha_cita_actual,
                hora: this.t_equipo,
                equipo: this.equipo_actual,
                tipo_cita: this.ttipocita,
                se_presento: '',
                observacion: this.tobservacion,
                lugar: this.tlugar,
                ci: this.ci,
                name: this.nombre,
                ap_paterno: this.paterno,
                ap_materno: this.materno,
            }
            console.log(this.tobservacion);

            this.v_imprimir = true;

        },
        imprimir() {
            this.v_imprimir = true;
        },
        async imprimir_boleta() {

            let printContents = document.getElementById('print').innerHTML;
            let w = window.open();
            w.document.write('<style>.titulo{font-family:Helvetica,Arial,sans-serif;font-weight:100%;font-size:14px}.titulo2{font-family:Arial,sans-serif;font-weight:700;font-size:12px}.titulo3{font-size:9px;font-weight:900}.aling{align-items:center;align-content:center;text-align:center;padding:5px}.center_columna{justify-content:center;align-content:center;display:flex;justify-content:center;align-items:center;border:3px solid gray}.aling{align-items:center;align-content:center;text-align:center;padding:0,0,0,0;font-size:10px}.alinear_elemento{justify-content:center;align-items:center;padding:5px;border-radius:5px;border:2px solid gray}.total_ancho{width:100%;background-color:#1ca698}.box{border-radius:10px;border:2px solid gray;justify-content:center;align-items:center;align-content:center;align-self:center;height:90px;align-items:center;align-content:center;text-align:center;align-content:center;padding:1px;justify-content:center;word-break:break-all}.wrapper{display:grid;grid-template-columns:repeat(3,1fr);grid-auto-rows:minmax(10px,90px);justify-content:center;text-align:center;padding:0,0,0,0;font-size:20px;border:0 solid gray;align-content:center;text-align:center;word-break:break-all}.one{grid-column:1/4;grid-row:1;align-content:center;height:30px}.two{grid-column:1;grid-row:2}.three{grid-column:2;grid-row:2}.four{grid-column:3;grid-row:2;font-size:14px}.five{grid-column:1/4;grid-row:3}.label{display:flex;flex-wrap:wrap;align-content:center;justify-content:center}.grid-container{display:grid;grid-template-columns:auto auto auto;padding:1px;font-size:20px}.grid-item{background-color:rgba(255,255,255,.8);border:1px solid rgba(0,0,0,.8);padding:1px;font-size:20px;text-align:center}.grid-nombre{background-color:rgba(255,255,255,.8);border:1px solid rgba(0,0,0,.8);font-size:20px;text-align:center}.texto_nombre{padding:0}.texto_mediano{font-size:16px}.observaciones{width:max-content}</STYLE>');

            w.document.write(printContents);

            w.document.close(); // necessary for IE >= 10
            w.focus(); // necessary for IE >= 10
            w.print();
            w.close();
            return true;

            window.print();

            //this.$inertia.get('api/imprimir', usuario);
        },
        showEvent({
            nativeEvent,
            event
        }) {
            const open = () => {
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
            }

            if (this.selectedOpen) {
                this.selectedOpen = false
                requestAnimationFrame(() => requestAnimationFrame(() => open()))
            } else {
                open()
            }

            nativeEvent.stopPropagation()
        },
        limipiar_formcita() {

        },
        async eliminar_cita() {
            console.log("----")
            console.log(this.selectedEvent)
            var res = await axios({
                method: 'post',
                url: 'api/eliminar_cita',
                data: {
                    cita: this.selectedEvent,
                }
            }).then();
            console.log(res)
            this.v_editar_agendar = false
            this.traerdatos();
        },
        async eliminar_cita2() {
            alert("ss");
            var res = await axios({
                method: 'post',
                url: 'api/eliminar_cita2',
                data: {
                    cita: this.selectedEvent,
                }
            }).then();
        },
        async guardar_cita_edit() {

            let hof = moment('2017-08-30T' + this.editar.hora).add(1, "h");
            if (this.$refs.form_cita_edit.validate()) {
                var cita = {
                    fecha: this.editar.fecha,
                    hora_inicio: this.editar.hora,
                    equipo: this.edita.equipo,
                    hora_final: hof.format("HH:mm:ss"),
                    tipo_cita: this.editar.tipo_cita,
                    se_presento: '',
                    observacion: this.editar.observacion,
                    lugar: this.editar.lugar,
                    ci: this.editar.id,
                    ci_doctor: '-1',

                }
                console.log(".....")
                console.log(this.editar)
                var res = await axios({
                    method: 'post',
                    url: 'api/dar_cita',
                    data: {
                        cita: cita,
                    }
                }).then();
                console.log(res['data']);

                this.v_editar_agendar = false;
                this.$refs.form_cita_edit.reset();
                var esp = await this.eliminar_cita()

            }

        },
        async guardar_cita() {
            let hof = moment('2017-08-30T' + this.t_equipo).add(1, "h");
            if (this.$refs.form_cita.validate()) {
                var cita = {
                    fecha: this.fecha_cita_actual,
                    hora_inicio: this.t_equipo,
                    equipo: this.equipo_actual,
                    hora_final: hof.format("HH:mm:ss"),
                    tipo_cita: this.ttipocita,
                    se_presento: '',
                    observacion: this.tobservacion,
                    lugar: this.tlugar,
                    ci: this.ci,
                    ci_doctor: '-1',

                }
                console.log(this.ci);
                var res = await axios({
                    method: 'post',
                    url: 'api/dar_cita',
                    data: {
                        cita: cita,
                    }
                }).then();
                console.log(res['data']);
                this.cerrar_dialog();
                this.$refs.formcita.reset();
                this.$refs.form_cita.reset();
                this.traerdatos();
                this.limipiar_formcita();
            }

        },
        async Guardar_datos() {

            let hof = moment('2017-08-30T' + this.t_equipo).add(1, "h");
            if (this.msg_usuario == 'Nuevo' && this.$refs.formcita.validate()) {
                var persona_nueva = {
                    nombre: this.nombre,
                    ap_paterno: this.paterno,
                    ap_materno: this.materno,
                    celular: this.numero,
                    correo: this.correo,
                    expendido: this.sdeparamento,
                    fecha_nacimiento: this.fecha_nacimiento,
                    ci: this.ci,
                    direccion: this.direccion,
                    sexo: this.ssexo,
                }

                var res = await axios({
                    method: 'post',
                    url: 'api/guardar_citas',
                    data: {
                        pn: persona_nueva,
                    }
                }).then();
                console.log(res['data']);
            }
            if (this.msg_usuario == 'Antiguo' && this.$refs.formcita.validate()) {
                console.log(this.numero);
                var persona_nueva = {
                    nombre: this.nombre,
                    ap_paterno: this.paterno,
                    ap_materno: this.materno,
                    celular: this.numero,
                    correo: this.correo,
                    expendido: this.sdeparamento,
                    fecha_nacimiento: this.fecha_nacimiento,
                    ci: this.ci,
                    direccion: this.direccion,
                    sexo: this.ssexo,
                }
                const vm = this;

                axios.interceptors.request.eject(null)
                var res = await axios({
                    method: 'post',
                    url: 'api/update_usuario',
                    data: {
                        pn: persona_nueva,
                    }
                }).then();
                //enableInterceptor
                console.log(res['data']);
            }

        },
        ventana_modal() {
            this.dialog = true;
            console.log(this.fecha_hoy);
            this.fecha_cita_actual = this.fecha_hoy;
        },
        cambioequipo() {
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            this.t_equipo = this.tiempos_actuales[0];
        },
        cambioequipos() {
            this.tiempos_actuales = this.lista_tiempos['' + this.editar.equipo];
            this.editar.hora = this.tiempos_actuales[0];
        },
        async change_fecha2() {
            var a = await axios.post('api/citas_actuales/' + this.fecha_cita_actual).then();
            console.log(a['data']);
            var array = a['data']
            this.equipos_actuales = [];
            var primera = true;
            var p, w = "";
            this.lista_tiempos = {};
            for (let i = 0; i < array.length; i++) {
                if (Object.keys(array[i]).length >= 1) {
                    this.equipos_actuales.push(i + 1);
                    var aux = [];
                    for (const key in array[i]) {
                        aux.push(array[i][key]);
                        if (primera) {
                            p = i,
                                w = array[i][key]
                            primera = false;
                        }
                    }
                    this.lista_tiempos['' + (i + 1)] = aux;
                }
            }
            this.equipo_actual = p + 1;
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            this.editar.equipo = w
        },
        async change_fecha(evento) {
            var a = await axios.post('api/citas_actuales/' + evento.fecha).then();
            //console.log(a['data']);
            var array = a['data']
            this.equipos_actuales = [];
            var primera = true;
            var p, w = "";
            this.lista_tiempos = {};
            for (let i = 0; i < array.length; i++) {
                if (Object.keys(array[i]).length >= 1) {
                    this.equipos_actuales.push(i + 1);
                    var aux = [];
                    for (const key in array[i]) {
                        aux.push(array[i][key]);
                        if (primera) {
                            p = i,
                                w = array[i][key]
                            primera = false;
                        }
                    }
                    this.lista_tiempos['' + (i + 1)] = aux;
                }
            }
            this.equipo_actual = p + 1;
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            console.log(this.tiempos_actuales)
            this.t_equipo = w
        },
        async Buscarfecha() {
            var a = await axios.post('api/citas_actuales/' + this.fecha_cita_actual).then();
            this.datos_usuario = a['data'];

        },
        rulesFecha_nac() {
            var fn = moment(this.fecha_nacimiento)
            var fmin = moment(this.minFechaNac)
            var fmax = moment(this.maxFechaNac)
            if (!(fmax > fn && fn > fmin)) {
                return "Fecha no valida";
            }
            return true;
        },
        async prueba() {

            this.datos_usuario = null;
            this.nombre = '';
            this.paterno = '';
            this.materno = '';
            this.correo = '';
            this.numero = '';
            this.fecha_nacimiento = '';
            this.direccion = '';
            this.msg_usuario = '';
            this.ssexo = 'No se tiene registro';
            this.sdeparamento = 'No se tiene registro';
            this.desserts = [];
            if (this.ci == '') {
                return;
            }
            var a = await axios.post('api/datos_usuario/' + this.ci).then();
            this.datos_usuario = a['data'];
            if (this.datos_usuario.length == 0) {
                this.msg_usuario = 'Nuevo';
            }
            if (this.datos_usuario.length == 1) {
                this.msg_usuario = 'Antiguo';
                var b = await axios.post('api/datos_citas/' + this.ci).then();
                this.desserts = b['data'];
                console.log(b);
                if (this.desserts.length == 0) {
                    this.desserts = [];
                }
            }
            if (this.datos_usuario.length >= 2) {
                this.msg_usuario = 'Duplicado';
                this.datos_usuario = null;
            }
            for (var e of this.datos_usuario) {
                this.nombre = e.nombre;
                this.paterno = e.ap_paterno;
                this.materno = e.ap_materno;
                this.correo = e.correo;
                this.numero = e.celular;
                this.fecha_nacimiento = e.fecha_nacimiento;
                this.direccion = e.direccion;
                this.ssexo = e.sexo;
                this.sdeparamento = e.expendido;
                if (e.sexo == '') {
                    this.ssexo = 'No se tiene registro';
                }
                if (e.expendido == '') {
                    this.sdepartamento = 'No se tiene registro';
                }
            }
            this.$refs.formcita.validate()

        },
        prueba_fecha() {
            console.log(this.fecha_hoy);
        },
        addelemento(array) {
            this.events = []
            for (var element of array) {

                const first = new Date(element.fecha + 'T' + element.hora_inicio + '-04:00')
                console.log(element);
                //alert(first)
                const second = new Date(element.fecha + 'T' + element.hora_final + '-04:00')
                var datos = {
                    name: element.nombre,
                    start: first,
                    end: second,
                    color: 'black',
                    timed: 1,
                    category: this.categories[element.equipo - 1],
                }
                if (element.nom_municipio == '') {
                    element.nom_municipio = 'No se tiene registro'
                }
                datos = Object.assign(datos, element);
                this.events.push(datos)
            }
        },
        initialize() {
            this.addelemento(this.datos_cita)
            this.doctorlista()
        },
        async doctorlista() {
            var a = await axios.get('api/doctor').then();
            this.doctores = a['data'];
        },
        async traerdatos() {
            console.log(this.fecha_hoy);
            var a = await axios.get('api/citas_fecha/' + this.fecha_hoy).then();
            this.addelemento(a['data']);

        },
        getEventColor(event) {
            return event.color
        },
        setFecha() {
            const today = new Date();
            const date = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();
            const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            const timestamp = date + ' ' + time;
            return date;
        },
        setToday() {

            this.msg['fecha_validacion'] = '';
            this.focus = ''
            var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
            this.anterior_dia = today;
            this.fecha_hoy = today;

            this.traerdatos();
            //alert("anterios dia"+this.anterior_dia+"hoy"+this.fecha_hoy);

        },
        diavalido(day) {
            if (day.getUTCDay() == 0 || day.getUTCDay() == 6) {
                return true;
            }
            var feriados = {}
            feriados['2022-05-02'] = true;
            feriados['2022-04-15'] = true;
            return feriados[day.toISOString().substr(0, 10)];
        },
        todayone(valor) {
            var dateStr = this.fecha_hoy_actual + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            f.setDate(f.getDate() + valor);
            return f.toISOString().substr(0, 10);
        },
        contarvalidos(valor) {
            var dateStr = this.fecha_hoy + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            var contador = 1;
            f.setDate(f.getDate() + valor);
            while (this.diavalido(f)) {
                f.setDate(f.getDate() + valor);
                contador++;
            }
            this.fecha_hoy = f.toISOString().substr(0, 10);
            this.anterior_dia = this.fecha_hoy;
            return contador;
        },
        prev() {
            var valor = -1;
            var contador = this.contarvalidos(valor);
            this.$refs.calendar.prev(contador);
            this.traerdatos();
        },
        next() {
            var valor = 1;
            var contador = this.contarvalidos(valor);
            this.$refs.calendar.next(contador);
            this.traerdatos();
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        async formatDate() {
            //alert(val);
            if (this.anterior_dia == '') {
                var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
                this.anterior_dia = today;
            }

            var elDate = document.getElementById('txtDate');
            var dateStr = this.fecha_hoy + 'T00:00:00-04:00';
            console.log(dateStr);
            var now = new Date(dateStr)
            var dayp = now.getUTCDay();
            this.msg['fecha_validacion'] = '';
            if (dayp == 0) {
                this.msg['fecha_validacion'] = 'Domingos no disponibles, por favor seleccione otro día.';
                this.fecha_hoy = this.anterior_dia;
                return;
            }
            if (dayp == 6) {
                this.msg['fecha_validacion'] = 'Sabados no disponibles, por favor seleccione otro día.';
                this.fecha_hoy = this.anterior_dia;
                return;
            }
            var feriados = {}
            feriados['2022-05-02'] = true;
            feriados['2022-04-15'] = true;
            if (feriados[this.fecha_hoy]) {
                this.msg['fecha_validacion'] = this.fecha_hoy + ' es feriado.';
                this.fecha_hoy = this.anterior_dia;
                return;
            }
            const date1 = new Date(this.anterior_dia);
            const date2 = new Date(this.fecha_hoy);
            const diffTime = date2 - date1;
            const nday = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            //alert(val + " " + this.anterior_dia + " " + nday);
            this.anterior_dia = this.fecha_hoy
            this.$refs.calendar.move(nday);
            //this.$emit('input', dateObj);
            this.traerdatos();
        },
        textoDate(val) {
            var dia = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septimbre', 'Octobre', 'Novembre', 'Diciembre'];
            var dateStr = val + 'T00:00:00-04:00';
            var now = new Date(dateStr)
            return dia[now.getDay()] + ' ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
            //this.focus = val;
            //alert(val);
            //alert(" =>"+ver1+" "+ver2);
            //this.$emit('input', dateObj);
        },
    },
    computed: {

    },
}
</script>
