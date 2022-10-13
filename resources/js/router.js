import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import login from './components/auth/login.vue'
import dashboard from './components/home/dashboard.vue'
import productList from './components/products/products.vue'
import addProduct from './components/products/addProduct.vue'
import ordersList from './components/orders/orders.vue'
import categoryList from './components/categories/categories.vue'
import warehouseList from './components/warehouses/warehouses.vue'
import zones from './components/zones/zones.vue'
import addZones from './components/zones/addZones.vue'
import orderDetails from './components/orders/orderDetails.vue'
import warehouse from './components/warehouses/warehouses.vue'
import rolePermission from './components/user_management/rolePermission.vue'
import staffManagement from './components/user_management/staffManagement.vue'
import vehiclesList from './components/fleet_management/vehicles.vue'
import driversList from './components/fleet_management/drivers.vue'
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
        path: '/addProduct',
        component: addProduct,
        name: 'AddProduct'
    },
    {
        path: '/ordersList',
        component: ordersList,
        name: 'OrdersList'
    },
    {
        path: '/categoryList',
        component: categoryList,
        name: 'CategoryList'
    },
    {
        path: '/warehouseList',
        component: warehouseList,
        name: 'WarehouseList'
    },
    {
        path: '/zones',
        component: zones,
        name: 'Zones'
    },
    {
        path: '/addZones',
        component: addZones,
        name: 'AddZones'
    },
    {
        path: '/orderDetails/:id',
        component: orderDetails,
        name: 'OrderDetails'
    },
    {
        path: '/warehouse',
        component: warehouse,
        name: 'Warehouse'
    },
    {
        path: '/rolePermission',
        component: rolePermission,
        name: 'RolePermission',
        props: true
    },
    {
        path: '/staffManagement',
        component: staffManagement,
        name: 'StaffManagement',
        props: true
    },
    {
        path: '/driversList',
        component: driversList,
        name: 'DriversList',
        props: true
    },
    {
        path: '/vehiclesList',
        component: vehiclesList,
        name: 'VehiclesList',
        props: true
    },
]

export default new  Router({
    mode: 'history',
    routes
})
