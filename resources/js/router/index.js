import LoginView from '../views/LoginView.vue';
import HomeView from '../views/HomeView.vue';

export default [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
];
