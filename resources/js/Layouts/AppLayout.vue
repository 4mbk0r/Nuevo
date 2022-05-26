<template>
<v-app id="inspire" class="grey--text text--darken-2">
    <div class="c_azul">
        <!-- Page Heading -->
        <v-app-bar color="#a2315a" class="white--text" app>
            <v-app-bar-nav-icon style="color: white;" @click="drawer = !drawer">

            </v-app-bar-nav-icon>

            <v-toolbar-title>UTRAID
            </v-toolbar-title>

        </v-app-bar>

        <!-- Page Content -->
        <v-navigation-drawer v-model="drawer" color="#a2315a" style="color: white;" app>
            <v-list>
                <v-list-item class="px-2">
                    <v-img max-height="80" max-width="150" src="./assets/logo-sedes-lapaz.png"></v-img>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content style="color: white;">
                        <v-list-item-title>
                            {{$page.props.user.name }}
                        </v-list-item-title>
                        <v-list-item-subtitle style="color: white;">{{$page.props.user.email }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>

                <form method="POST" @submit.prevent="logout">
                    <jet-responsive-nav-link as="button">

                        <v-list-item-title style="color: black;">Cerrar Secion</v-list-item-title>
                    </jet-responsive-nav-link>
                </form>
            </v-list>

            <v-divider></v-divider>

            <v-list nav dense>
                
                    <jet-responsive-nav-link :href="route('inicio')" :active="route().current('inicio')">

                        <v-icon >mdi-file-document-edit</v-icon>
                        <a :href="route('inicio')">Agendar</a>
                    </jet-responsive-nav-link>

                    <jet-responsive-nav-link :href="route('administrar.index')" :active="route().current('administrar.index')">

                        <v-icon >mdi-account-multiple</v-icon>
                        <a :href="route('registro')">Usuario</a>
                    </jet-responsive-nav-link>

            </v-list>
        </v-navigation-drawer>

        <v-main>
            <slot></slot>
        </v-main>

        <!-- Modal Portal -->
        <portal-target name="modal" multiple>
        </portal-target>
        <v-dialog v-model="v_carga" hide-overlay persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Espere por favor
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</v-app>
</template>

<script>
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetBanner from '@/Jetstream/Banner'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetNavLink from '@/Jetstream/NavLink'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'

export default {
    components: {
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
    },

    data() {
        return {
            showingNavigationDropdown: false,
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
            v_carga: false,
            pendingRequests: 0,
            totalRequests: 0,
        }
    },

    methods: {

        switchToTeam(team) {
            this.$inertia.put(route('current-team.update'), {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },

        logout() {

            this.$inertia.post(route('logout'));
        },
        enableInterceptor() {
            axios.interceptors.request.use(config => {
                // Every Axios request should increment our counter
                this.v_carga = true;
                this.pendingRequests++;
                this.totalRequests++;
                return config;
            });
            axios.interceptors.response.use(response => {
                // Every Axios response should decrease our counter
                this.pendingRequests--;
                if (this.pendingRequests == 0) {
                    this.v_carga = false;
                }
                return response;
            });
        }
    },
    mounted() {
        this.enableInterceptor()
    },
}
</script>
