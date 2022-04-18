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
                    <!--<welcome />-->
                    <barrasu :datos_cita="fechas"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import Barrasu from '@/Pages/Micomponet/Barrasu'

    export default {
        props:{
            fechas: Array,
        },
        components: {
            AppLayout,
            Welcome,
            Barrasu,
        },
    }
</script>
