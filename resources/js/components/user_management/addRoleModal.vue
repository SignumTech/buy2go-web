<template>
 <div class="row m-0 p-2">
    <div class="col-md-12 border-bottom p-2">
        <h5>{{modalType == 'Edit' ? "Edit" : "Add"}} role</h5>
    </div>
    <div class="col-md-6">
        <label><strong>Role </strong></label>
        <input type="text" class="form-control" v-model="data.role">
        <h6 class="text-danger m-0" v-for="an in valErrors.role" :key="an.id">{{an}}</h6>
    </div>
    <div v-if="modalType != 'Edit'" class="col-md-6">
        <label for="">Role Type</label>
        <select class="form-select" v-model="data.deletable" id="">
            <option :value=null></option>
            <option :value=true>Removable</option>
            <option :value=false>Non removable</option>
        </select>
    </div>
    <div class="col-md-12 pt-2">
        <h6>Access Permission</h6>
    </div>
    <div class="col-md-12 pt-2">
        <input class="form-check-input" @click="changeState('Dashboard')"  v-model="data.permissions.Dashboard" type="checkbox" value="Sales"> Dashboard
    </div>
    <div class="col-md-12 pt-2">
        <input class="form-check-input" @click="changeState('Products')" v-model="data.permissions.Products" type="checkbox" value="Sales"> Products
    </div>
    <div class="col-md-12 pt-2">
        <input class="form-check-input" @click="changeState('Orders')" v-model="data.permissions.Orders" type="checkbox" value="Sales"> Orders
    </div>
    <div class="col-md-12 pt-2">
        <input class="form-check-input" @click="changeState('Categories')" v-model="data.permissions.Categories" type="checkbox" value="Sales"> Categories
    </div>
    <div class="col-md-12 pt-2">
        <input class="form-check-input" @click="changeState('Warehouses')" v-model="data.permissions.Warehouses" type="checkbox" value="Sales"> Warehouses
    </div>
    <div class="col-md-12 pt-2">
        <input class="form-check-input" @click="changeState('Zones')" v-model="data.permissions.Zones" type="checkbox" value="Sales"> Zones
    </div>
    <div class="col-md-12 pt-2">
        <span class="mr-3" data-bs-toggle="collapse" href="#fleet_m" role="button" aria-expanded="false" aria-controls="collapseExample" style="padding-right: 12px">
            <span class="fa fa-angle-down"> </span> 
        </span>
        <input class="form-check-input" @click="changeState('FleetManagement')" v-model="data.permissions.FleetManagement" type="checkbox" value="Sales"> Fleet Management
        <div class="collapse mt-2 ms-5" id="fleet_m">
            <div>
                <input class="form-check-input" v-model="data.permissions.Routes" type="checkbox" value="Sales"> Routes
            </div>
            <div>
                <input class="form-check-input" v-model="data.permissions.Drivers" type="checkbox" value="Sales"> Drivers
            </div>
        </div>
    </div>
    <div class="col-md-12 pt-2">
        <span class="mr-3" data-bs-toggle="collapse" href="#business_m" role="button" aria-expanded="false" aria-controls="collapseExample" style="padding-right: 12px">
            <span class="fa fa-angle-down"> </span> 
        </span>
        <input class="form-check-input" @click="changeState('UserManagement')" v-model="data.permissions.UserManagement" type="checkbox" value="Sales"> User Management
        <div class="collapse mt-2 ms-5" id="business_m">
            <div>
                <input class="form-check-input" v-model="data.permissions.RolePermission" type="checkbox" value="Sales"> Role Permission
            </div>
            <div>
                <input class="form-check-input" v-model="data.permissions.StaffManagement" type="checkbox" value="Sales"> Staff Management
            </div>
            <div>
                <input class="form-check-input" v-model="data.permissions.PaymentRequest" type="checkbox" value="Sales"> Payment Request
            </div>
            <div>
                <input class="form-check-input" v-model="data.permissions.CommissionAgents" type="checkbox" value="Sales"> Commission Agents
            </div>
            <div>
                <input class="form-check-input" v-model="data.permissions.CreditService" type="checkbox" value="Sales"> Credit Service
            </div>
        </div>
    </div>
    <div class="col-md-12 pt-2 pb-2">
        <input class="form-check-input" @click="changeState('SalesReport')" v-model="data.permissions.SalesReport" type="checkbox" value="Sales"> SalesReport
    </div>
    <div class="col-md-12 pt-2 pb-2 border-bottom">
        <input class="form-check-input" @click="changeState('Customers')" v-model="data.permissions.Customers" type="checkbox" value="Sales"> Customers
    </div>
    <div class="col-md-12 p-2 text-end">
        <button :disabled="isClicked" @click="$emit('close')" class="btn btn-secondary">Cancel</button>
        <button :disabled="isClicked" v-if="!isEdit" @click="createPermission()" class="button btn btn-primary">Save</button>
        <button :disabled="isClicked" v-if="isEdit" @click="updatePermission()" class="button btn btn-primary">Edit</button>
    </div>
</div>   
</template>
<script>
export default {
    props:["modalType", "id"],
    data(){
        return{
            isClicked: false,
            valErrors: {},
            isEdit: false,
            data:{
                role: null,
                id: null,
                deletable:null,
                permissions:{
                    Dashboard: false,
                    Products: false,
                    Orders: false,
                    Categories: false,
                    Marketing: false,
                    FlashSales: false,
                    FleetManagement: false,
                    Routes:false,
                    Drivers:false,
                    UserManagement: false,
                    StaffManagement: false,
                    RolePermission: false,
                    CreditService: false,
                    PaymentRequest: false,
                    CommissionAgents: false,
                    SalesReport: false,
                    Customers: false,
                }
            },
        }
    },
    mounted(){
        if(this.modalType == 'Edit'){
            this.showRole()
            this.isEdit = true
            this.data.id = this.id
            console.log(this.data.id)
        }
    },
    methods:{
        async showRole(){
            await axios.get('/getRoles/'+this.id)
            .then( response =>{
                this.data.role = response.data.role
                this.data.permissions = JSON.parse(response.data.permissions)
            })
        },
        changeState(page){
            if(page === 'Dashboard'){
                if(this.data.permissions.Dashboard === true){
                    this.data.permissions.Dashboard = false;
                }
                else{
                    this.data.permissions.Dashboard = true;
                }
            }
            if(page === 'Products'){
                if(this.data.permissions.Products === true){
                    this.data.permissions.Products = false;
                }
                else{
                    this.data.permissions.Products = true;
                }
            }
            if(page === 'Orders'){
                if(this.data.permissions.Orders === true){
                    this.data.permissions.Orders = false;
                }
                else{
                    this.data.permissions.Orders = true;
                }
            }
            if(page === 'Categories'){
                if(this.data.permissions.Categories === true){
                    this.data.permissions.Categories = false;
                }
                else{
                    this.data.permissions.Categories = true;
                }
            }
            if(page === 'FleetManagement'){
                if(this.data.permissions.Routes === true){
                    this.data.permissions.Routes = false;
                }
                else{
                    this.data.permissions.Routes = true;
                }
                if(this.data.permissions.Drivers === true){
                    this.data.permissions.Drivers = false;
                }
                else{
                    this.data.permissions.Drivers = true;
                }
            }
            if(page === 'UserManagement'){
                if(this.data.permissions.RolePermission === true){
                    this.data.permissions.RolePermission = false;
                }
                else{
                    this.data.permissions.RolePermission = true;
                }
                if(this.data.permissions.StaffManagement === true){
                    this.data.permissions.StaffManagement = false;
                }
                else{
                    this.data.permissions.StaffManagement = true;
                }
                if(this.data.permissions.CommissionAgents === true){
                    this.data.permissions.CommissionAgents = false;
                }
                else{
                    this.data.permissions.CommissionAgents = true;
                }
                if(this.data.permissions.PaymentRequest === true){
                    this.data.permissions.PaymentRequest = false;
                }
                else{
                    this.data.permissions.PaymentRequest = true;
                }
                if(this.data.permissions.CreditService === true){
                    this.data.permissions.CreditService = false;
                }
                else{
                    this.data.permissions.CreditService = true;
                }
            }
            if(page === 'SalesReport'){
                if(this.data.permissions.SalesReport === true){
                    this.data.permissions.SalesReport = false;
                }
                else{
                    this.data.permissions.SalesReport = true;
                }
            }
            if(page === 'Customers'){
                if(this.data.permissions.Customers === true){
                    this.data.permissions.Customers = false;
                }
                else{
                    this.data.permissions.Customers = true;
                }
            }
        },
        async createPermission(){
            this.isClicked = true
            await axios.post('/createRolePermission', this.data)
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
        async updatePermission(){
            await axios.post('/updateRolePermission', this.data)
            .then( response => {
                this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Role Permission Updated',
                        text: 'Role permission updatedsuccessfully!'
                    });
                this.$emit('close');
            })
            .catch( error =>{
                this.isClicked = false
                if( error.response.status == 422){
                    this.valErrors = error.response.data.errors
                }
            })
        }
    }
}
</script>