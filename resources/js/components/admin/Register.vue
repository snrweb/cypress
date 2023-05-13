<template>
  <v-container style="height: 100%">
    <v-row justify="center" class="align-center" style="height: 100%">
      <v-col cols="12" sm="8" md="7">
        <v-card>
          <v-card-title>
            <span class="headline">Admin Register</span>
          </v-card-title>

          <v-card-text>
            <v-form ref="form" @submit.prevent="handleSubmit">
              <v-text-field
                label="Full Name"
                v-model="name"
                :rules="[(v) => !!v || 'User name is required']"
                required
                autocomplete="off"
              ></v-text-field>

              <v-text-field
                label="Email"
                v-model="email"
                :rules="[(v) => !!v || 'Email is required']"
                required
                autocomplete="on"
              ></v-text-field>

              <v-checkbox
                v-model="isAdmin"
                label="Is Admin"
                color="red"
                input-value="0"
              ></v-checkbox>

              <v-text-field
                label="Password"
                type="password"
                v-model="password"
                :rules="[(v) => !!v || 'Password is required']"
                required
              ></v-text-field>

              <v-text-field
                label="Confirm Password"
                type="password"
                v-model="confirmPassword"
                :rules="[
                  (v) =>
                    !!v ||
                    password != confirmPassword ||
                    'Password does not match',
                ]"
                required
              ></v-text-field>

              <v-card-actions>
                <router-link color="primary" to="/login"
                  >Already registered?</router-link
                >
                <v-spacer></v-spacer>
                <v-btn type="submit" color="primary">Register</v-btn>
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
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
      isAdmin: "0",
    };
  },
  methods: {
    async handleSubmit() {
      try {
        const response = await axios.post("/api/admin/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          is_admin: this.isAdmin,
        });

        store.commit("setSnackbar", {
          snackbar: true,
          text: "Registration Successful",
          timeout: 4000,
          color: "green",
        });
        this.$router.push("/login");
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
