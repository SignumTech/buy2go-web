import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import login from './components/auth/login.vue'
import dashboard from './components/home/dashboard.vue'
import productList from './components/products/products.vue'
import ordersList from './components/orders/orders.vue'

Vue.use(Router)

const routes = [
    {
        path: '/login',
        component: login,
        name: 'Login'
    },
    {
        path: '/dashboard',
        component: dashboard,
        name: 'Dashboard'
    },
    {
        path: '/productList',
        component: productList,
        name: 'ProductList'
    },
    {
        path: '/ordersList',
        component: ordersList,
        name: 'OrdersList'
    },
]

export default new  Router({
    mode: 'history',
    routes
})
