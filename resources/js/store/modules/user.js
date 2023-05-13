export default {
    namespace: true,
    state: {
        users: [],
    },
    actions: {
        setUsers({ commit }, user) {
            commit("setUsers", user);
        },
    },
    mutations: {
        setUsers(state, users) {
            state.users = users;
        },
    },
};
