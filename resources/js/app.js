import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import routes from './router';
import '../scss/app.scss';

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token');
    const rolesJson = localStorage.getItem('auth_roles');
    const roles = rolesJson ? JSON.parse(rolesJson) : [];

    if (!token && to.name !== 'login') {
        return next({ name: 'login' });
    }

    if (to.name === 'login' && token) {
        if (roles.includes('manager')) return next({ name: 'orders.create' });
        if (roles.includes('head')) return next({ name: 'orders.index' });
        return next({ name: 'home' });
    }

    if (to.name === 'orders.create' && !roles.includes('manager')) {
        return next({ name: 'orders.index' });
    }
    if (to.name === 'orders.index' && !roles.includes('head')) {
        return next({ name: 'orders.create' });
    }

    next();
});

createApp(App).use(router).mount('#app');
