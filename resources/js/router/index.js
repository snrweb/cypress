import { createApp } from 'vue';
import { createRouter, createWebHistory } from "vue-router";
import store  from '../store';
import Login from '../components/admin/Login.vue';
import Register from '../components/admin/Register.vue';
import ActivityManagement from '../components/admin/ActivityManagement.vue';
import Users from '../components/admin/Users.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login, name: 'Login' },
    { path: '/register', component: Register, name: 'Register' },
    { path: '/activities', component: ActivityManagement, name: 'ActivityManagement', meta: { requiresAuth: true }, },
    { path: '/users', component: Users, name: 'Users', meta: { requiresAuth: true }, },
  ],
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const isLoggedIn = store.state.auth.isLoggedIn;

  if (requiresAuth && !isLoggedIn) {
    return next({ name: 'Login' });
  } else {
    return next();
  }
});

export default router;
