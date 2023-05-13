<template>
  <v-container style="height: 100%">
    <v-row justify="center" class="align-center" style="height: 100%">
      <v-col cols="12" sm="8" md="7">
        <v-card>
          <v-card-title>
            <span class="headline">Admin Login</span>
          </v-card-title>

          <v-card-text>
            <v-form ref="form" @submit.prevent="handleSubmit">
              <v-text-field
                label="Email"
                v-model="email"
                :rules="[(v) => !!v || 'Email is required']"
                required
                autocomplete="off"
              ></v-text-field>

              <v-text-field
                label="Password"
                type="password"
                v-model="password"
                :rules="[(v) => !!v || 'Password is required']"
                required
              ></v-text-field>

              <v-card-actions>
                <router-link color="primary" to="/register"
                  >Not registered?</router-link
                >
                <v-spacer></v-spacer>
                <v-btn type="submit" color="primary">Login</v-btn>
              </v-card-actions>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from "axios";
import store from "../../store";
export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async handleSubmit() {
      try {
        const response = await axios.post("/api/admin/login", {
          email: this.email,
          password: this.password,
        });

        store.dispatch("setUser", response.data.user);
        store.dispatch("setToken", response.data.token);
        store.dispatch("setLoginStatus", true);
        sessionStorage.setItem("token", response.data.token);

        this.$router.push("/users");

        store.commit("setSnackbar", {
          snackbar: true,
          text: "Login Successful",
          timeout: 4000,
          color: "green",
        });
      } catch (error) {
        store.commit("setSnackbar", {
          snackbar: true,
          text: error.message,
          timeout: 4000,
          color: "red",
        });
      }
    },
  },
};
</script>
