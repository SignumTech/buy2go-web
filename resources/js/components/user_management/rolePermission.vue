<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Roles & Pemrissions</strong></h5>
    </div>
    <div class="col-md-12 mb-5">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="row m-0">
                <div class="col-md-12 mt-2">
                    <button class="btn shadow-sm btn-primary btn-sm float-end" @click="showAddRoleModal()"><strong><span class="fa fa-plus"></span> Add Role</strong></button>
                </div>
                
            </div>
            <div v-if="!loading" class="row m-0 p-3 border-bottom">
                <table class="table table-data table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Access Permission</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                        v-for="td,index in tableData"
                        :key="index"
                        >
                            <td>{{index+1}}</td>
                            <th>{{td.role}}</th>
                            <td style="width: 60%">
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).dashboard">Dashboard</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).dashboardOrders">Dashboard Orders</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).dashboardSales">Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).newUsers">New Users</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).totalRevenue">Total Revenue</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).productList">Products</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).addProduct">Add Products</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).deleteProduct">Delete Products</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).updateProduct">Update Products</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).ordersList">Orders</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).assignDetails">Assign Order Details</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).confirmDelivery">Confirm Delivery</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).categoryList">Categories</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).warehouseManagement">Warehouse Management</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).warehouse">Warehouses</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).warehouseManagers">Warehouse Managers</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).zonesLocations">Zones & Locations</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).zones">Zones</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).locations">Locations</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).fleetManagement">Fleet Management</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).fleetView">Fleet</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).routeList">Routes</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).addRoute">Add Routes</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).deleteRoute">Delete Routes</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).updateRoute">Update Routes</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).driversList">Drivers</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).addDriver">Add Driver</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).deleteDriver">Delete Driver</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).updateDriver">Update Driver</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).visitsList">Visits</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).addVisit">Add Visits</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).trackVisit">Track Visits</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).deleteVisit">Delete Visits</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).updateVisit">Update Visits</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).userManagement">User Management</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).rolePermission">Role Permission</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).StaffManagement">Staff Management</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).addStaff">Add Staff</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).deleteStaff">Delete Staff</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).updateStaff">Update Staff</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).agents">Commission Agent</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).confirmRequest">Confirm Requests</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).verifyAgents">Verify Agents</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).printReciept">Print Reciept</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).paymentRequests">Payment Request</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).creditServices">Credit Services</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).salesReport">SalesReport</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).salesDashboard">Sales Dashboard</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).productSales">Product Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).customerSalesReport">Customer Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).agentSalesReport">Agent Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).rtmRanks">RTM Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).driversReport">Drivers Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).customers">Customers</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).assignSales">Assign Sales</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).verifyShop">Verify Shop</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).banShop">Ban Shop</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).addShopLocation">Add Shop Location</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).deleteShopLocation">Delete Shop Location</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).updateShopLocation">Update Shop Location</span>
                                <span class="badge badge-pill bg-primary p-1 shadow-sm" v-if="JSON.parse(td.permissions).generateQRcode">Generate QR Code</span>
                            </td>
                            <td>
                                <span @click="showEditRoleModal(td)" class="fa fa-edit float-end me-3"></span>
                                <span v-if="td.deletable" @click="deleteRole(td.id)" class="fa fa-trash-alt float-end me-3"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="loading" class="d-flex justify-content-center">
                    <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                </div>
            </div>
        </div>
    </div>
    
</div>
</template>
<script>
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import addrolemodal from './addRoleModal.vue'
import addRolesVue from './addRoles.vue'
import editRolesVue from './editRoles.vue'
export default {
    data(){
        return{
            loading: true,
            isDeleted: false,
            tableData:[], 
        }
    },
    components: {
        addrolemodal,
        PulseLoader
    },
    mounted(){
        this.getRoles()
        
    },
    methods:{
        async deleteRole(id){
            this.isDeleted = true
            var check = confirm("Are you sure you want to delete this Role?")
            if(check == true){
                await axios.delete("/deleteRole/"+id)
                .then( response =>{
                    this.getRoles();
                    this.isDeleted = false
                     this.$notify({
                        group: 'foo',
                        title: 'Role delete successful',
                        text: ''
                    });
                })
            }
        },
        async getRoles(){
            this.loading = true
            await axios.get('/getRoles')
            .then( response => {
                this.tableData = response.data;
                this.loading = false
            })
        },
        showAddRoleModal(){
            this.$modal.show(
                addRolesVue,
                {"modalType" : "Add"},
                { height: "auto", width: "70%"},
                {"closed" : this.closeAdd}    
            );
        },
        showEditRoleModal(role){
            this.$modal.show(
                editRolesVue,
                {role:role},
                { height: "auto", width: "70%"},
                {"closed" : this.closeAdd}    
            );
        },
        closeAdd(event){
            window.location.replace('/rolePermission')
        }
    }
}
</script>