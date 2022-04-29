<template>
<div id="app">
    <v-app id="inspire">

        <div v-if="msg.fecha_validacion" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{msg.fecha_validacion}}</strong>

        </div>

        <v-row class="fill-height">
            <v-col>
                <v-sheet height="64">
                    <v-toolbar flat>
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
                            {{ textoDate() }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <button type="button" @click="ventana_modal" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Agregar</button>

                    </v-toolbar>

                </v-sheet>
                <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click="dialog = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Datos Personales</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark text @click="dialog = false">
                                Save
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-card>

                        <v-container>

                            <v-form ref="formcita" @submit.prevent="addEvent">

                                <div id="main" class="grid grid-cols-2 gap-1 justify-evenly">
                                    <v-text-field v-model="ci" type="number" @change="prueba" label="Carnet">
                                    </v-text-field>
                                    <v-select v-model="sdeparamento" :items="departamentos" :rules="selectRules" label="Expedido" color="black">
                                    </v-select>
                                </div>
                                <div v-if="msg_usuario=='Nuevo'" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">El usuario es {{msg_usuario}}</strong>
                                </div>
                                <v-alert v-if="msg_usuario=='Antiguo'" dense border="left" type="warning">
                                    <strong class="font-bold">El usuario es {{msg_usuario}}</strong>
                                </v-alert>
                                <div v-if="datos_usuario">
                                    <div id="main" class="grid grid-cols-3 gap-1 justify-evenly">
                                        <v-text-field v-model="nombre" :rules="nombreRules" type="text" label="Nombre">
                                        </v-text-field>
                                        <v-text-field v-model="paterno" :rules="nombreRules" type="text" label="Apellido Paterno">
                                        </v-text-field>
                                        <v-text-field v-model="materno" :rules="nombreRules" type="text" label="Apellido Materno">
                                        </v-text-field>
                                    </div>
                                    <div class="grid grid-cols-2 gap-1 justify-evenly">
                                        <v-text-field v-model="correo" type="email" label="Correo">
                                        </v-text-field>
                                        <v-text-field v-model="numero" type="number" label="Numero Celular">
                                        </v-text-field>
                                    </div>
                                    <div id="main" class="grid grid-cols-3 gap-1 justify-evenly">
                                        <v-text-field v-model="fecha_nac_actual" :min="minFechaNac" :max="maxFechaNac" :rules="[ rulesFecha_nac ]" type="date" label="Fecha de nacimiento">
                                        </v-text-field>
                                        <v-text-field v-model="direccion" type="text" label="Direccion">
                                        </v-text-field>
                                        <div>
                                            <v-select v-model="ssexo" :items="sexo" color="purple darken-3" label="Sexo">
                                            </v-select>
                                        </div>

                                    </div>
                                </div>

                                <v-data-table v-if="desserts.length>=1" :headers="headers" :footer-props="{itemsPerPageText: 'Pacientes por pagina'}" :items="desserts" sort-by="fecha" class="elevation-1 cyan lighten-3">

                                </v-data-table>

                                <div class="text-center">
                                    <!--
                                    <v-btn class="ma-2" :loading="loading2" :disabled="loading2" color="success" @click="loader = 'loading2'">
                                        Cita anterior
                                        <template v-slot:loader>
                                            <span>Loading...</span>
                                        </template>
                                    </v-btn>
                                    -->
                                    <v-btn class="ma-2" :loading="loading4" :disabled="loading4" color="info" @click="loader = 'loading4'">
                                        Cita posterior
                                        <template v-slot:loader>
                                            <span class="custom-loader">
                                                <v-icon light>mdi-cached</v-icon>
                                            </span>
                                        </template>
                                    </v-btn>
                                </div>
                                <v-btn type="submit" color="primary" class="mr-4" @click="Guardar_datos">
                                    Guardar Datos
                                </v-btn>
                                <v-btn type="submit" color="primary" class="mr-4" @click="v_agendar=true" @click.stop="dialog=false">
                                    Dar cita
                                </v-btn>
                            </v-form>
                            <div>

                            </div>
                        </v-container>
                    </v-card>
                </v-dialog>

                <v-dialog v-model="v_agendar" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click="v_agendar = false">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Agendar</v-toolbar-title>
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
                                    <v-btn class="ma-2" :loading="loading4" :disabled="loading4" color="info" @click="loader = 'loading4'">
                                        Cita posterior
                                        <template v-slot:loader>
                                            <span class="custom-loader">
                                                <v-icon light>mdi-cached</v-icon>
                                            </span>
                                        </template>
                                    </v-btn>
                                    <div id="main" class="grid grid-cols-3 gap-1 justify-evenly">
                                        <v-text-field v-model="fecha_cita_actual" :rules="nombreRules" :min="fechacitaMin" @change="change_fecha" type="date" label="Fecha de Cita">
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
                                </div>
                                <v-btn type="submit" color="primary" class="mr-4" @click="guardar_cita" @click.stop="v_agendar=false">
                                    Guardar Cita
                                </v-btn>
                            </v-form>
                        </v-container>
                    </v-card>
                </v-dialog>

                <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
                    <v-card color="grey lighten-4" min-width="350px" flat>
                        <v-toolbar :color="selectedEvent.color" dark>
                            <v-btn icon>
                                <v-icon>mdi-pencil</v-icon>
                            </v-btn>
                            <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-btn icon>
                                <v-icon>mdi-heart</v-icon>
                            </v-btn>
                            <v-btn icon>
                                <v-icon>mdi-dots-vertical</v-icon>
                            </v-btn>
                        </v-toolbar>
                        <v-card-text>
                            <span v-html="selectedEvent.details"></span>
                            <v-toolbar-title v-html="selectedEvent.id"></v-toolbar-title>
                            
                        </v-card-text>
                        <v-card-actions>
                            <v-btn text color="secondary" @click="selectedOpen = false">
                                Cancel
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-menu>
                <v-sheet height="600">
                    <!--@change=""-->
                    <v-calendar ref="calendar" v-model="focus" color="primary" type="category" category-show-all :categories="categories" :events="events" :event-color="getEventColor" @click:event="showEvent" :first-interval=7 :interval-minutes=60 :interval-count=12></v-calendar>
                </v-sheet>
            </v-col>
        </v-row>
    </v-app>
</div>
</template>

<script>
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
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
        fecha_naciento: null,
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
        msg: [],
        datos_usuario: null,
        msg_usuario: null,
        desserts: [],
        fecha_cita_actual: "",
        fecha_nac_actual: "",
        hora_inicial: null,
        equipo_actual: null,
        loading4: false,
        //equipos
        equipos_actuales: [],
        tiempos_actuales: [],
        lista_tiempos: {},
        t_equipo: null,
        //ventana modal 
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,

    }),
    mounted() {
        this.$refs.calendar.checkChange()
    },
    created() {
        this.initialize()
    },
    methods: {
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
        async guardar_cita() {
            let hof = moment('2017-08-30T' + this.t_equipo).add(1, "h");
            if (this.$refs.form_cita.validate()) {
                var cita = {
                    fecha: this.fecha_cita_actual,
                    hora_inicio: this.t_equipo,
                    equipo: this.equipo_actual,
                    hora_final: hof.format("HH:mm:ss"),
                    tipo_cita: '',
                    se_presento: '',
                    observacion: '',
                    lugar: 'CENTRO',
                    ci: this.ci,
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
                this.$refs.formcita.reset();
                this.$refs.form_cita.reset();
                this.traerdatos();

            }

        },
        async Guardar_datos() {

            var Value1 = 'hola'
            var Value2 = '2'
            let hof = moment('2017-08-30T' + this.t_equipo).add(1, "h");
            if (this.msg_usuario == 'Nuevo' && this.$refs.formcita.validate()) {
                var persona_nueva = {
                    nombre: this.nombre,
                    ap_paterno: this.paterno,
                    ap_materno: this.materno,
                    celular: this.numero,
                    correo: this.correo,
                    expendido: this.sdeparamento,
                    fecha_nacimiento: this.fecha_nac_actual,
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
                    fecha_nacimiento: this.fecha_nac_actual,
                    ci: this.ci,
                    direccion: this.direccion,
                    sexo: this.ssexo,
                }
                var res = await axios({
                    method: 'post',
                    url: 'api/update_usuario',
                    data: {
                        pn: persona_nueva,
                    }
                }).then();
                console.log(res['data']);
            }

        },
        ventana_modal() {
            this.dialog = true;
            console.log(this.fecha_hoy);
            this.fecha_cita_actual = this.fecha_hoy;
            this.change_fecha();
        },
        cambioequipo() {
            this.tiempos_actuales = this.lista_tiempos['' + this.equipo_actual];
            this.t_equipo = this.tiempos_actuales[0];
        },
        async change_fecha() {
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
            this.t_equipo = w
        },
        async Buscarfecha() {
            var a = await axios.post('api/citas_actuales/' + this.fecha_cita_actual).then();
            this.datos_usuario = a['data'];

        },
        rulesFecha_nac() {
            var fn = moment(this.fecha_nac_actual)
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
            this.fecha_naciento = '';
            this.direccion = '';
            this.msg_usuario = '';
            this.ssexo = 'No se tiene registro';
            this.sdeparamento = 'No se tiene registro';
            this.desserts = [];
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
                this.fecha_nac_actual = e.fecha_nacimiento;
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
                //console.log(first);
                //alert(first)
                const second = new Date(element.fecha + 'T' + element.hora_final + '-04:00')
                var datos = {
                    name: element.nombre,
                    id: element.ci,
                    start: first,
                    end: second,
                    color: this.colors[Math.floor(Math.random() * this.colors.length)],
                    timed: 1,
                    category: this.categories[element.equipo - 1],
                }

                this.events.push(datos)
            }
        },
        initialize() {
            this.addelemento(this.datos_cita)
        },
        async traerdatos() {
            var a = await axios.get('api/citas_fecha/' + this.fecha_hoy).then();
            this.addelemento(a['data']);

        },
        async addEvent() {
            if (this.$refs.formcita.validate()) {
                /*//const allDay = this.rnd(0, 3) === 0
                const first = new Date(this.fecha_cita + 'T' + this.tiempo_inicio + ':00-04:00')
                //alert(first)
                const second = new Date(this.fecha_cita + 'T' + this.timepo_fin + ':00-04:00')
                //alert(this.selectequipo);
                this.events.push({
                    name: this.nombre + ' ' + this.paterno,
                    start: first,
                    end: second,
                    color: this.color,
                    timed: 1,
                    category: this.categories[this.selectequipo - 1],
                })
                this.$refs.formcita.reset();
                this.msg_usuario = '';
                this.datos_usuario = null;*/
            } else {
                this.dialog = true;
            }
            //this.$refs.formcita.reset();
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
        textoDate() {
            var dia = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
            var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septimbre', 'Octobre', 'Novembre', 'Diciembre'];
            var dateStr = this.fecha_hoy + 'T00:00:00-04:00';
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
