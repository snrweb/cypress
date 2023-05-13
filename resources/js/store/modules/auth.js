import axios from 'axios';
import store from '../index'
import router from '../../router'

export default {
    namespace: true,
    state: {
        user: null,
        token: null,
        isLoggedIn: false,
        snackbarData: {
          snackbar: false,
          text: "",
          timeout: 4000,
          color: "green",
        }
    },
    actions: {
        setUser({ commit }, user) {
            commit("setUser", user);
        },
        setToken({ commit }, token) {
            commit("setToken", token);
        },
        setLoginStatus({ commit }, isLoggedIn) {
            commit("setLoginStatus", isLoggedIn);
        },
        logout: ({ commit }, payload) => {
            axios
                .post(`/api/admin/user/logout`, {
                    ...payload,
                }, {
                    headers: {
                        Authorization: `Bearer ${store.state.auth.token}`,
                    },
                })
                .then((res) => {
                    commit("setLoginStatus", false);
                    commit("setToken", "");
                    sessionStorage.removeItem("token");
                    router.push("/login");
                })
                .catch((err) => {
                    commit("setSnackbar", {
                        snackbar: true,
                        text: "An error occurred",
                        timeout: 4000,
                        color: "red",
                    });
                });
        },
    },
    getters: {
        user: (state) => state.user,
        token: (state) => state.token,
        isLoggedIn: (state) => state.isLoggedIn,
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.token = token;
        },
        setLoginStatus(state, isLoggedIn) {
            state.isLoggedIn = isLoggedIn;
        },
        setSnackbar: (state, payload) => {
            state.snackbarData = payload;
        }
    },
};
