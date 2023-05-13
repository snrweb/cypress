<template>
    <div style="height: 100%">
        <div style="height: 100%">
            <v-layout v-if="isAuth">
                <v-navigation-drawer expand-on-hover rail>
                    <v-list>
                        <v-list-item
                            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
                            :title="user.name"
                            :subtitle="user.email"
                        ></v-list-item>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list density="compact" nav>
                        <router-link color="primary" to="/users"
                            ><v-list-item
                                prepend-icon="mdi-account-multiple"
                                title="Users"
                                value="shared"
                            ></v-list-item>
                        </router-link>

                        <router-link color="primary" to="/activities">
                            <v-list-item
                                prepend-icon="mdi-folder"
                                title="Activities"
                                value="myfiles"
                            ></v-list-item>
                        </router-link>
                        <v-list-item
                            @click="logout"
                            prepend-icon="mdi-logout"
                            title="Logout"
                            value="logout"
                        ></v-list-item>
                    </v-list>
                </v-navigation-drawer>

                <v-main>
                    <router-view v-slot="{ Component }">
                        <v-slide-x-reverse-transition>
                            <component :is="Component" />
                        </v-slide-x-reverse-transition>
                    </router-view>
                </v-main>
            </v-layout>
            <v-layout style="height: 100%" v-else>
                <router-view v-slot="{ Component }">
                    <v-slide-x-reverse-transition>
                        <component :is="Component" />
                    </v-slide-x-reverse-transition>
                </router-view>
            </v-layout>
        </div>
        <v-snackbar
            v-model="mSnackbar.snackbar"
            :timeout="mSnackbar.timeout"
            :color="mSnackbar.color"
        >
            {{ mSnackbar.text }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    color="white"
                    text
                    v-bind="attrs"
                    @click="mSnackbar.snackbar = false"
                >
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </div>
</template>

<script>
import store from "../store";
import AppLogin from "./admin/Login.vue";
export default {
    name: "App",
    components: {
        AppLogin,
    },

    computed: {
        isAuth() {
            return store.state.auth.isLoggedIn;
        },
        user() {
            return store.state.auth.user;
        },
        mSnackbar() {
            return store.state.auth.snackbarData;
        },
    },

    methods: {
        logout() {
            store.dispatch("logout", store.state.auth.user);
        },
    },

    mounted() {
        console.log("Component mounted.");
    },
};
</script>

<style scoped>
html,
body {
    height: 100%;
}
</style>
