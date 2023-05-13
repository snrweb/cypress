<template>
    <v-container>
        <v-toolbar color="white">
            <v-toolbar-title>Activities</v-toolbar-title>
            <v-divider inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="addActivityDialog = true"
                >Add Activity</v-btn
            >
        </v-toolbar>
        <v-divider class="mr-10 mb-4" inset></v-divider>
        <div class="mt-2 d-flex flex-row justify-center">
            <div class="cols-12 col-md-3">
                <v-text-field
                    v-model="startDate"
                    type="date"
                    label="Start Date"
                ></v-text-field>
            </div>

            <div class="cols-12 col-md-3 ml-3">
                <v-text-field
                    v-model="endDate"
                    type="date"
                    label="End Date"
                ></v-text-field>
            </div>

            <div class="cols-12 col-md-2 ml-3">
                <v-btn class="primary" style="height: 53px" @click="getAllActivitiesByDateRange">{{
                    isFetching ? "Fetching..." : "Fetch"
                }}</v-btn>
            </div>
        </div>
        <v-row class="mt-2">
            <v-col
                cols="12"
                sm="4"
                v-for="activity in activities"
                :key="activity.id"
            >
                <v-card>
                    <v-img
                        :src="`/uploads/${activity.image}`"
                        height="200px"
                    ></v-img>
                    <v-chip class="ml-4 mt-4" color="primary">
                        <p>
                            {{
                                activity.user == null
                                    ? "GLOBAL"
                                    : activity.user.name
                            }}
                        </p>
                    </v-chip>
                    <v-card-title>{{ activity.title }}</v-card-title>
                    <p class="ml-4">{{ activity.description }}</p>
                    <span class="ml-4">{{
                        formatDateWithoutTime(activity.created_at)
                    }}</span>
                    <v-card-actions>
                        <v-btn color="primary" @click="editActivity(activity)"
                            >Edit</v-btn
                        >
                        <v-btn color="error" @click="deleteActivity(activity)"
                            >Delete</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog persistent v-model="dialogDelete" max-width="600px">
            <v-card>
                <v-card-title class="text-h6"
                    >Are you sure you want to delete this
                    activity?</v-card-title
                >
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete"
                        >Cancel</v-btn
                    >
                    <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                        >OK</v-btn
                    >
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="addActivityDialog" persistent max-width="600px">
            <v-card>
                <v-container>
                    <v-card-title class="mb-4">
                        <span class="text-h5" style="color: black"
                            >Add Activity</span
                        >
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="newActivity.title"
                                    label="Title"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="newActivity.description"
                                    label="Description"
                                ></v-text-field>
                            </v-col>
                            <v-checkbox
                                v-model="newActivity.isGlobal"
                                label="Set as global activity"
                                color="red"
                                input-value="0"
                            ></v-checkbox>
                            <v-col cols="12" v-if="!newActivity.isGlobal">
                                <v-select
                                    v-model="user"
                                    :items="users"
                                    item-title="name"
                                    item-value="name"
                                    label="Users"
                                    persistent-hint
                                    return-object
                                ></v-select>
                            </v-col>
                            <v-col cols="12">
                                <v-file-input
                                    v-model="newActivity.image"
                                    label="Image"
                                ></v-file-input>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="addActivityDialog = false"
                            >Cancel</v-btn
                        >
                        <v-btn color="primary" @click="addNewActivity"
                            >Save</v-btn
                        >
                    </v-card-actions>
                </v-container>
            </v-card>
        </v-dialog>
        <v-dialog v-model="editActivityDialog" persistent max-width="600px">
            <v-card>
                <v-container>
                    <v-card-title class="mb-4">
                        <span class="text-h5" style="color: black"
                            >Edit Activity</span
                        >
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="editedActivity.title"
                                    label="Title"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="editedActivity.description"
                                    label="Description"
                                ></v-text-field>
                            </v-col>
                            <v-checkbox
                                v-model="editedActivity.isGlobal"
                                label="Set as global activity"
                                color="red"
                                :value="editedActivity.isGlobal"
                            ></v-checkbox>
                            <v-col cols="12" v-if="!editedActivity.isGlobal">
                                <v-select
                                    v-model="user"
                                    :items="users"
                                    item-title="name"
                                    item-value="name"
                                    label="Users"
                                    persistent-hint
                                    return-object
                                ></v-select>
                            </v-col>
                            <v-col cols="12">
                                <v-row>
                                    <v-col cols="6">
                                        <v-file-input
                                            v-model="editedActivity.newImage"
                                            label="Update Image"
                                        ></v-file-input>
                                    </v-col>
                                    <v-col cols="2">
                                        <v-img
                                            :src="`/uploads/${editedActivity.image}`"
                                            height="60px"
                                        ></v-img>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="closeEditActivityDialog"
                            >Cancel</v-btn
                        >
                        <v-btn color="primary" @click="updateActivity"
                            >Save</v-btn
                        >
                    </v-card-actions>
                </v-container>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import axios from "axios";
import store from "../../store";
import moment from "moment";
export default {
    data() {
        return {
            activity: null,
            user: null,
            addActivityDialog: false,
            dialogDelete: false,
            isFetching: false,
            startDate: null,
            endDate: null,
            newActivity: {
                title: "",
                description: "",
                image: [],
                isGlobal: 0,
                user_id: null,
                date: null,
            },
            editActivityDialog: false,
            editedActivity: {
                id: null,
                title: "",
                description: "",
                image: null,
                newImage: [],
                isGlobal: 0,
                user_id: null,
                date: null,
            },
        };
    },

    computed: {
        users() {
            return store.state.user.users;
        },
        activities() {
            return store.state.activity.activities;
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

        getAllActivities() {
            axios
                .get(`/api/admin/activities`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.auth.token}`,
                    },
                })
                .then((res) => {
                    store.commit("setActivities", res.data.activities);
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

        getAllActivitiesByDateRange() {
            this.isFetching = true;
            axios
                .get(
                    `/api/admin/activities/${this.startDate}/${this.endDate}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.auth.token}`,
                        },
                    }
                )
                .then((res) => {
                    store.commit("setActivities", res.data.activities);
                    this.isFetching = false;
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

        async addNewActivity() {
            try {
                const formData = new FormData();
                formData.append("title", this.newActivity.title);
                formData.append("description", this.newActivity.description);
                if (this.user?.id) {
                    formData.append("user_id", this.user?.id ?? null);
                }
                formData.append("image", this.newActivity.image[0]);

                // Send the request to the backend API to save the new activity
                const response = await axios.post(
                    "/api/admin/activities",
                    formData,
                    {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            Authorization: `Bearer ${this.$store.state.auth.token}`,
                        },
                    }
                );

                // Add the new activity to the Vuex store
                this.$store.dispatch("setActivities", response.data.activities);

                store.commit("setSnackbar", {
                    snackbar: true,
                    text: "Activity Successfully Added",
                    timeout: 4000,
                    color: "green",
                });

                // Reset the form fields
                this.newActivity.title = "";
                this.newActivity.description = "";
                this.newActivity.date = null;
                this.newActivity.image = [];
                this.user = null;

                // Close the dialog
                this.addActivityDialog = false;
            } catch (error) {
                store.commit("setSnackbar", {
                    snackbar: true,
                    text: error.response.data.message,
                    timeout: 4000,
                    color: "red",
                });
            }
        },

        editActivity(activity) {
            this.editedActivity = { ...activity };
            this.editedActivity.isGlobal = activity.user == null ? 1 : 0;
            this.user = activity.user == null ? null : activity.user;
            this.editActivityDialog = true;
        },

        async updateActivity() {
            let userId = this.user?.id ?? this.editedActivity.user_id;
            userId = this.editedActivity.isGlobal == 1 ? null : userId;
            try {
                const formData = new FormData();
                formData.append("_method", "PATCH");
                formData.append("id", this.editedActivity.id);
                formData.append("title", this.editedActivity.title);
                formData.append("description", this.editedActivity.description);
                if (userId) {
                    formData.append("user_id", userId);
                }
                if (this.editedActivity.newImage) {
                    formData.append("image", this.editedActivity.newImage[0]);
                }

                // Send the request to the backend API to save the new activity
                const response = await axios.post(
                    `/api/admin/activities/${this.editedActivity.id}`,
                    formData,
                    {
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            Authorization: `Bearer ${this.$store.state.auth.token}`,
                        },
                    }
                );

                // Add the new activity to the Vuex store
                this.$store.dispatch("setActivities", response.data.activities);

                store.commit("setSnackbar", {
                    snackbar: true,
                    text: "Activity Successfully Updated",
                    timeout: 4000,
                    color: "green",
                });

                this.editedActivity.title = "";
                this.editedActivity.description = "";
                this.editedActivity.date = null;
                this.editedActivity.image = [];
                this.user = null;

                // Close the dialog
                this.editActivityDialog = false;
            } catch (error) {
                store.commit("setSnackbar", {
                    snackbar: true,
                    text: error?.response?.data?.message ?? error.message,
                    timeout: 4000,
                    color: "red",
                });
            }
        },

        deleteActivity(activity) {
            this.activity = activity;
            this.dialogDelete = true;
        },

        async deleteItemConfirm() {
            try {
                const response = await axios.delete(
                    `/api/admin/activities/${this.activity.id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.auth.token}`,
                        },
                    }
                );
                this.dialogDelete = false;
                this.$store.dispatch("setActivities", response.data.activities);
            } catch (error) {
                store.commit("setSnackbar", {
                    snackbar: true,
                    text: error.message,
                    timeout: 4000,
                    color: "red",
                });
            }
        },

        closeEditActivityDialog() {
            this.user = null;
            this.editActivityDialog = false;
        },

        closeDelete() {
            this.dialogDelete = false;
        },
    },

    created() {
        this.getAllUsers();
        this.getAllActivities();
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
