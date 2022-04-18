<template>
<div id="app">
    <v-app id="inspire">
        <v-row class="fill-height">
            <v-col>
                <v-sheet height="64">
                    <v-toolbar flat>
                        <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                            Hoy
                        </v-btn>
                        <input type="date" refs="seldate" onkeydown="return false" v-model="fecha_hoy" @input="formatDate(fecha_hoy)" />
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
                        <button type="button" @click="dialog = true" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-full text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Agregar</button>

                    </v-toolbar>
                </v-sheet>
                <v-dialog v-model="dialog" max-width="500">
                    <v-card>
                        <v-container>
                            <v-form ref="formcita" @submit.prevent="addEvent">

                                <div id="main" class="grid grid-cols-2 gap-1 justify-evenly">
                                    <v-text-field v-model="ci" type="number" label="Carnet">
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
                                    <v-text-field v-model="fecha_hoy" class="col-span-2" type="date" label="Fecha de la cita">
                                    </v-text-field>
                                    <v-text-field v-model="tiempo_inicio" type="time" label="Hora de inicio">
                                    </v-text-field>
                                    <v-text-field v-model="timepo_fin" type="time" label="Hora de finalizacion">
                                    </v-text-field>
                                    <v-text-field v-model="color" type="color" label="color (click aqui)">
                                    </v-text-field>
                                </div>
                                <div class="grid grid-cols-2 gap-1 justify-evenly">
                                    <v-subheader>
                                        Seleccione un Equipo
                                    </v-subheader>
                                    <v-select v-model="selectequipo" :items="equipo" label="Selecciona el equipo" dense outlined></v-select>

                                </div>

                                <v-btn type="submit" color="primary" class="mr-4" @click.stop="dialog=false">
                                    Crear Evento
                                </v-btn>
                            </v-form>
                        </v-container>
                    </v-card>
                </v-dialog>

                <v-sheet height="600">
                    <!--@change=""-->
                    <v-calendar ref="calendar" v-model="focus" color="primary" type="category" category-show-all :categories="categories" :events="events" :event-color="getEventColor" :first-interval=7 :interval-minutes=60 :interval-count=12></v-calendar>
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
        focus: '',
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party', 'Cita'],
        categories: ['Equipo 1', 'Equipo 2', 'Equipo 3', 'Equipo 4'],
        equipo: [1, 2, 3, 4],
        fecha_hoy: day1,
        anterior_dia: '',
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
    mounted() {
        this.$refs.calendar.checkChange()
    },
    created() {
        this.initialize()
    },
    methods: {
        addelemento(array) {
            this.events = []
            for (var element of array) {

                const first = new Date(element.fecha + 'T' + element.hora_inicio + '-04:00')
                //console.log(first);
                //alert(first)
                const second = new Date(element.fecha + 'T' + element.hora_final + '-04:00')
                var datos = {
                    name: element.nombre,
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
            //console.log(this.datos_cita);
            // You can use `let` instead of `const` if you like
            this.addelemento(this.datos_cita)
            /*
            const first = new Date(this.da + 'T' + this.tiempo_inicio + ':00-04:00')
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
            */
        },
        async traerdatos() {
            var a = await axios.get('agendar/' + this.fecha_hoy).then();

            this.addelemento(a['data']);

        },
        async addEvent() {
            if (this.$refs.formcita.validate()) {
                //const allDay = this.rnd(0, 3) === 0
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
                this.nombre = null
                this.paterno = null
                this.materno = null
                this.ci = null
                this.departamento = null
                this.correo = null
                this.celular = null
                this.fecha_cita = null
                this.tiempo_inicio = null
                this.timepo_fin = null
                this.selectequipo = null
            } else {
                this.dialog = true;
            }

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
            this.focus = ''
            var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
            this.anterior_dia = today;
            this.fecha_hoy = today;
            
            this.traerdatos();
            //alert("anterios dia"+this.anterior_dia+"hoy"+this.fecha_hoy);

        },
        prev() {
            this.$refs.calendar.prev()
            var dateStr = this.fecha_hoy;
            var days = 1;
            var result = new Date(new Date(dateStr).setDate(new Date(dateStr).getDate() - days));

            
            
            //alert(result + " " +result.toISOString().substr(0, 10));
            this.fecha_hoy = result.toISOString().substr(0, 10);
            //this.fecha_hoy = result.toISOString().substr(0, 10);
            this.anterior_dia = this.fecha_hoy;
            
            this.traerdatos();


        },
        next() {
            this.$refs.calendar.next();
            
            var dateStr = this.fecha_hoy;
            var days = 1;
            var result = new Date(new Date(dateStr).setDate(new Date(dateStr).getDate() + days));
            //alert(result + " " +result.toISOString().substr(0, 10));
            this.fecha_hoy = result.toISOString().substr(0, 10);
            this.anterior_dia = this.fecha_hoy;
            this.traerdatos();
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        async formatDate(val) {
            //alert(val);
            if (this.anterior_dia == '') {
                var today = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2);
                this.anterior_dia = today;
            }
            //alert(this.anterior_dia);
            //alert(this.anterior_dia);
            //alert(this.fecha_hoy);
            const date1 = new Date(this.anterior_dia);
            const date2 = new Date(val);
            const diffTime = date2 - date1;
            const nday = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            //alert(val + " " + this.anterior_dia + " " + nday);
            this.anterior_dia = val;
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
