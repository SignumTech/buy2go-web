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
import locations from './components/zones/locations.vue'
import addZones from './components/zones/addZones.vue'
import orderDetails from './components/orders/orderDetails.vue'
import shippingDetails from './components/orders/shippingDetails.vue'
import directions from './components/orders/directions.vue'
import warehouse from './components/warehouses/warehouses.vue'
import rolePermission from './components/user_management/rolePermission.vue'
import creditServices from './components/user_management/creditServices.vue'
import staffManagement from './components/user_management/staffManagement.vue'
import vehiclesList from './components/fleet_management/vehicles.vue'
import driversList from './components/fleet_management/drivers.vue'
import routeList from './components/fleet_management/routes.vue'
import customers from './components/customers/customers.vue'
import customerDetails from './components/customers/customerDetails.vue'
import agents from './components/user_management/commissionAgents.vue'
import agentDetails from './components/user_management/agentDetails.vue'
import editProduct from './components/products/editProduct.vue'
import paymentRequests from './components/user_management/paymentRequests.vue'
import requestDetails from './components/user_management/requestDetails.vue'
import salesReport from './components/sales_report/salesReport.vue'
import warehouseManagers from './components/warehouses/warehouseManagers.vue'
import visitsList from './components/fleet_management/visits.vue'
import visitDetail from './components/fleet_management/visitDetail.vue'
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
        path: '/shippingDetails/:id',
        component: shippingDetails,
        name: 'ShippingDetails'
    },
    {
        path: '/directions',
        component: directions,
        name: 'directions'
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
        path: '/routeList',
        component: routeList,
        name: 'RouteList',
        props: true
    },
    {
        path: '/vehiclesList',
        component: vehiclesList,
        name: 'VehiclesList',
        props: true
    },
    {
        path: '/customers',
        component: customers,
        name: 'Customers',
        props: true
    },
    {
        path: '/customerDetails/:id',
        component: customerDetails,
        name: 'CustomerDetails',
        props: true
    },
    {
        path: '/agents',
        component: agents,
        name: 'Agents',
        props: true
    },
    {
        path: '/agentDetail/:id',
        component: agentDetails,
        name: 'AgentDetails',
        props: true
    },
    {
        path: '/editProduct/:id',
        component: editProduct,
        name: 'EditProduct',
        props: true
    },
    {
        path: '/paymentRequests',
        component: paymentRequests,
        name: 'PaymentRequests',
        props: true
    },
    {
        path: '/requestDetails/:id',
        component: requestDetails,
        name: 'RequestDetails',
        props: true
    },
    {
        path: '/creditServices',
        component: creditServices,
        name: 'CreditServices',
        props: true
    },
    {
        path: '/locations',
        component: locations,
        name: 'Locations',
        props: true
    },
    {
        path: '/salesReport',
        component: salesReport,
        name: 'SalesReport',
        props: true
    },
    {
        path: '/warehouseManagers',
        component: warehouseManagers,
        name: 'WarehouseManagers',
        props: true
    },
    {
        path: '/visitsList',
        component: visitsList,
        name: 'VisitsList',
        props: true
    },
    {
        path: '/visitDetails/:id',
        component: visitDetail,
        name: 'VisitDetail',
        props: true
    }
    
]

export default new  Router({
    mode: 'history',
    routes
})
