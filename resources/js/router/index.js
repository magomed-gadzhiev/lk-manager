import LoginView from '../views/LoginView.vue';
import HomeView from '../views/HomeView.vue';
import OrderCreateView from '../views/OrderCreateView.vue';
import OrdersListView from '../views/OrdersListView.vue';

export default [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/manager/orders', name: 'orders.index', component: OrdersListView },
  { path: '/manager/orders/create', name: 'orders.create', component: OrderCreateView },
];
