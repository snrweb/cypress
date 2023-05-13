import { createApp } from 'vue';
import { createStore } from "vuex";
import auth from './modules/auth';
import activity from './modules/activity';
import user from './modules/user';

export default new createStore({
  modules: {
    auth,
    activity,
    user
  },
});
