<template>
    <div class="row p-4 overflow-auto" style="height:80vh">
        <div class="col-md-12">
            <h5>Add Role</h5>
        </div>
        <div class="col-md-6 mb-4">
            <label><strong>Role </strong></label>
            <input type="text" class="form-control" v-model="formData.role">
            <h6 class="text-danger m-0" v-for="an in valErrors.role" :key="an.id">{{an}}</h6>
        </div>
        <div class="col-md-6 mb-4">
            <label for="">Role Type</label>
            <select class="form-select" v-model="formData.deletable" id="">
                <option :value=null></option>
                <option :value=true>Removable</option>
                <option :value=false>Non removable</option>
            </select>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input v-model="formData.permissions.dashboard" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Dashboard</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.dashboard" v-model="formData.permissions.dashboardOrders" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Orders Count</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.dashboard" v-model="formData.permissions.dashboardSales" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Sales</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.dashboard" v-model="formData.permissions.newUsers" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> New Users</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.dashboard" v-model="formData.permissions.totalRevenue" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Total Revenue</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Products</label>
                        <input v-model="formData.permissions.productList" class="form-check-input" type="checkbox">
                        
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.productList" v-model="formData.permissions.addProduct" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Add Product</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.productList" v-model="formData.permissions.deleteProduct" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Delete Product</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.productList" v-model="formData.permissions.updateProduct" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Update Product</label>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Orders</label>
                        <input v-model="formData.permissions.ordersList" class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.ordersList" v-model="formData.permissions.assignDetails" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Assign Order Details</label>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.ordersList" v-model="formData.permissions.confirmDelivery" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Confirm Delivery</label>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-2 mb-3">
                    <div class="form-check form-switch">
                        <input v-model="formData.permissions.categoryList" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Categories</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Warehouse Management</label>
                        <input v-model="formData.permissions.warehouseManagement" class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.warehouseManagement" v-model="formData.permissions.warehouse" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Warehouses</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.warehouseManagement" v-model="formData.permissions.warehouseManagers" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Warehouse Managers</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Zones & Locations</label>
                        <input v-model="formData.permissions.zonesLocations" class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.zonesLocations" v-model="formData.permissions.zones" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Zones</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.zonesLocations" v-model="formData.permissions.locations" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Locations</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Fleet Management</label>
                        <input v-model="formData.permissions.fleetManagement" class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.fleetView" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Fleet</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.routeList" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Routes Page</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.addRoute" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Add Routes</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.updateRoute" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Update Routes</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.deleteRoute" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Delete Routes</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.driversList" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Drivers</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.addDriver" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Add Drivers</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.updateDriver" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Update Drivers</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.deleteDriver" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Delete Drivers</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.visitsList" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Visits</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.addVisit" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Add Visits</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.trackVisit" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Track Visits</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.deleteVisit" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Delete Visits</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.fleetManagement" v-model="formData.permissions.updateVisit" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Update Visits</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> User Management</label>
                        <input v-model="formData.permissions.userManagement" class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.staffManagement" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Staff Management</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.addStaff" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Add staff</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.deleteStaff" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Delete staff</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.updateStaff" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Update staff</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.agents" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Commission Agents</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.verifyAgents" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Verify Agents</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.paymentRequests" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Payment Requests</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.confirmRequest" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Confirm Request</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.printReciept" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Print Reciept</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.creditServices" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Credit Services</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.userManagement" v-model="formData.permissions.rolePermission" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Role Permissions</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input v-model="formData.permissions.salesReport" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Sales Report</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.salesReport" v-model="formData.permissions.salesDashboard" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Sales Dashboard</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.salesReport" v-model="formData.permissions.productSales" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Product Sales Report</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.salesReport" v-model="formData.permissions.customerSalesReport" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Customer Sales Report</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.salesReport" v-model="formData.permissions.agentSalesReport" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Agent Sales Report</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.salesReport" v-model="formData.permissions.rtmRanks" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> RTM Sales Report</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.salesReport" v-model="formData.permissions.driversReport" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Drivers Report</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 py-2 border-bottom border-top">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Customers</label>
                        <input v-model="formData.permissions.customers" class="form-check-input" type="checkbox">
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.assignSales" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Assign Sales</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.verifyShop" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Verify Shops</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.banShop" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Ban Shops</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.addShopLocation" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Add Shop Location</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.deleteShopLocation" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Delete Shop Location</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.updateShopLocation" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Update Shop Location</label>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-check form-switch">
                        <input :disabled="!formData.permissions.customers" v-model="formData.permissions.generateQRcode" class="form-check-input" type="checkbox">
                        <label class="form-check-label" for="flexSwitchCheckChecked"> Generate QR code</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <button @click="createPermission()" class="btn btn-primary btn-sm px-3 rounded-1 float-end"><span class="fa fa-plus"></span> Add Role</button>
            <button class="btn btn-secondary btn-sm px-3 mx-2 rounded-1 float-end"><span class="fa fa-times"></span> Cancel</button>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            valErrors:{},
            formData:{
                role:null,
                deletable:null,
                permissions:{
                    dashboard:false,
                    dashboardSales:false,
                    dashboardOrders:false,
                    newUsers:false,
                    totalRevenue:false,
                    productList:false,
                    addProduct:false,
                    deleteProduct:false,
                    updateProduct:false,
                    ordersList:false,
                    assignDetails:false,
                    confirmDelivery:false,
                    categoryList:false,
                    warehouseManagement:false,
                    warehouse:false,
                    warehouseManagers:false,
                    zonesLocations:false,
                    zones:false,
                    locations:false,
                    fleetManagement:false,
                    fleetView:false,
                    routeList:false,
                    addRoute:false,
                    updateRoute:false,
                    deleteRoute:false,
                    driversList:false,
                    addDriver:false,
                    updateDriver:false,
                    deleteDriver:false,
                    visitsList:false,
                    addVisit:false,
                    trackVisit:false,
                    deleteVisit:false,
                    updateVisit:false,
                    userManagement:false,
                    staffManagement:false,
                    addStaff:false,
                    deleteStaff:false,
                    updateStaff:false,
                    rolePermission:false,
                    agents:false,
                    verifyAgents:false,
                    paymentRequests:false,
                    confirmRequest:false,
                    printReciept:false,
                    creditServices:false,
                    salesReport:false,
                    salesDashboard:false,
                    productSales:false,
                    customerSalesReport:false,
                    agentSalesReport:false,
                    rtmRanks:false,
                    driversReport:false,
                    customers:false,
                    assignSales:false,
                    verifyShop:false,
                    banShop:false,
                    addShopLocation:false,
                    deleteShopLocation:false,
                    updateShopLocation:false,
                    generateQRcode:false
                }
            }
        }
    },
    methods:{
        async createPermission(){
            await axios.post('/createRolePermission', this.formData)
            .then( response => {
                this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Role Permission Added',
                        text: 'role permission added successfully!'
                    });
                this.$emit('close');
            })
            .catch( error =>{
                this.isClicked = false
                if( error.response.status == 422){
                    this.valErrors = error.response.data.errors
                }
            })
        },
    }
}
</script>