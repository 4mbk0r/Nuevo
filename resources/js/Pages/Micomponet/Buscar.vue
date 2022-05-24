<template>
<v-toolbar>
    <v-container v-if="buscar">

        <v-row>
            <v-col md="5" >
                <v-text-field hide-details label="Buscar por Apellido Paterno" placeholder="Escriba por Apellido Paterno">
                </v-text-field>
            </v-col>
            <v-col md="5" >
                <v-text-field hide-details label="Buscar por Apellido Paterno" placeholder="Escriba por Apellido Paterno">
                </v-text-field>
            </v-col>
            <v-col md="auto" sm="auto" xs="auto" >
                <v-btn icon @click="buscar=true">
                    <v-icon>mdi-magnify</v-icon>
                </v-btn>
            </v-col>
            <v-col md="auto" sm="auto" xs="auto" >

                <v-btn icon @click="buscar = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-col>
        </v-row>

    </v-container>
    <v-container v-else>
        <v-btn icon @click="buscar=true">
            <v-icon>mdi-magnify</v-icon>
        </v-btn>
        <strong>Busqueda avanzada</strong>
    </v-container>

</v-toolbar>
</template>

<script>
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    data: () => ({

        buscar: false,
    }),
}
</script>
