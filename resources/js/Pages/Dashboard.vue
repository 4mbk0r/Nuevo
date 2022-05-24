<template>
<app-layout>

    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>

    </template>

    <div class="py-12 ma-12 pa-12"">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!--<welcome />-->
                
                <v-sheet height="400" class="overflow-hidden" style="position: relative;">
                    <v-container class="fill-height">
                        <v-row align="center" justify="center">
                            <v-btn color="pink" dark @click.stop="drawer = !drawer">
                                Toggle
                            </v-btn>
                        </v-row>
                    </v-container>

                    <v-navigation-drawer v-model="drawer" absolute temporary>
                        <v-list-item>
                            <v-list-item-avatar>
                                <v-img src="https://randomuser.me/api/portraits/men/78.jpg"></v-img>
                            </v-list-item-avatar>

                            <v-list-item-content>
                                <v-list-item-title>John Leider</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-divider></v-divider>

                        <v-list dense>
                            <v-list-item v-for="item in items" :key="item.title" link>
                                <v-list-item-icon>
                                    <v-icon>{{ item.icon }}</v-icon>
                                </v-list-item-icon>

                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                    </v-navigation-drawer>
                </v-sheet>
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
    data() {
        return {
            drawer: null,
            items: [{
                    title: 'Home',
                    icon: 'mdi-view-dashboard'
                },
                {
                    title: 'About',
                    icon: 'mdi-forum'
                },
            ],
        }
    },
    components: {
        AppLayout,
        Welcome,
        Barrasu,
    },

}
</script>
