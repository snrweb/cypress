<template>
    <v-container>
        <h2>Users</h2>
        <v-row class="mt-2">
            <v-col cols="12" md="3" sm="4" v-for="user in users" :key="user.id">
                <v-card>
                    <v-list>
                        <v-list-item
                            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
                            :title="user.name"
                            :subtitle="user.email"
                        ></v-list-item>
                    </v-list>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import axios from "axios";
import store from "../../store";
import moment from "moment";
export default {
    data() {
        return {};
    },

    computed: {
        users() {
            return store.state.user.users;
        },
    },

    methods: {
        formatDateWithoutTime(date) {
            return moment(date).format("MMMM D, YYYY");
        },

        getAllUsers() {
            axios
                .get(`/api/admin/users`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.auth.token}`,
                    },
                })
                .then((res) => {
                    store.commit("setUsers", res.data.users);
                })
                .catch((err) => {
                    store.commit("setSnackbar", {
                        snackbar: true,
                        text: err.message,
                        timeout: 4000,
                        color: "red",
                    });
                });
        },
    },

    created() {
        if (this.$store.state.auth.isLoggedIn) this.getAllUsers();
    },
};
</script>

<style scoped>
p {
    font-size: 14px;
}
span {
    font-size: 12px;
    color: #a09e9e;
}
</style>
