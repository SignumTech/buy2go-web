<template>
<div class="row mt-4 mx-4">
    <div class="col-md-4">
        <div class="bg-white rounded-1 shadow-sm p-4">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="rounded-circle overflow-hidden d-block m-auto" style="width:100px; height:100px">
                        <img class="img img-fluid" src="/storage/settings/store_placeholder.png" alt="">
                    </div>
                    <h5 class="text-center mt-3"><strong>{{shopDetails.shop_details.f_name}} {{shopDetails.shop_details.l_name}}</strong></h5>
                    <h6 class="text-center">+{{shopDetails.shop_details.country_code}}-{{shopDetails.shop_details.phone_no}}</h6>
                    <button v-if="permission.generateQRcode" @click="generateQr()" class="btn btn-primary btn-sm rounded-1 px-2"><span class="fa fa-qrcode"></span> Generate QR</button>
                    <hr>
                </div>
                <div class="col-md-12">
                    <h6><strong>Last Order</strong></h6>
                    <h6 v-if="shopDetails.last_order">{{shopDetails.last_order.order_no}} - {{shopDetails.last_order.created_at | moment("MMM Do YYYY")}}</h6>
                    <h6 class="mt-3"><strong>Average Order Value</strong></h6>
                    <h6>{{shopDetails.average_order | numFormat}} ETB</h6>
                    <h6 class="mt-3"><strong>Shop Registered</strong></h6>
                    <h6>{{shopDetails.shop_details.created_at | moment("MMM Do YYYY")}}</h6>
                    <div v-if="$store.state.auth.user.user_role == `ADMIN`">
                        <h6 class="mt-3"><strong>Sales Manager <span v-if="!shopDetails.sales_manager && shopDetails.shop_details.shop_status === `VERIFIED`" @click="addSalesManager()" class="fa fa-plus"></span></strong></h6>
                        <h6 v-if="shopDetails.sales_manager">{{shopDetails.sales_manager.f_name}} {{shopDetails.sales_manager.l_name}} <span @click="editSalesManager(shopDetails.sales_manager)" class="fa fa-edit"></span></h6>                        
                    </div>
                    <div class="mt-3">
                        <button v-if="shopDetails.shop_details.shop_status != `BANNED` && shopDetails.shop_details.shop_status == `VERIFIED` && permission.banShop" @click="banShop()" class="btn btn-danger btn-sm float-end"><span class="fa fa-ban"></span> Ban shop</button>
                        <button v-if="shopDetails.shop_details.shop_status == `BANNED`" @click="unbanShop() && permission.unbanShop" class="btn btn-warning btn-sm float-end"><span class="fa fa-lock-open"></span> Unban Shop</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-1 shadow-sm p-4 mt-3">
            <div class="row">
                <h6><strong>Shop Locations <span v-if="shopDetails.sales_manager && shopDetails.shop_details.shop_status == `VERIFIED` && permission.addShopLocation" @click="addShop()" class="fa fa-plus float-end"></span></strong></h6>
                <div v-for="address,index in locations" :key="index" class="col-md-12 mt-2">
                    <h6 class="mb-2"><span class="fa fa-map-marker-alt"></span> {{address.regular_address}} <span v-if="permission.deleteShopLocation" class="fa fa-trash-alt float-end"></span> <span v-if="permission.updateShopLocation" @click="editShop(address)" class="fa fa-edit me-3 float-end"></span> </h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div v-if="shopDetails.shop_details.shop_status === `NOT_VERIFIED`" class="bg-warning rounded-1 shadow-sm">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-6 align-self-center">
                    <h5 class="mb-0"><span class="fa fa-exclamation-triangle"></span> This shop is not verified!</h5>
                </div>
                <div class="col-md-6">
                    <button v-if="shopDetails.sales_manager && permission.verfiyShop" @click="verifyModal()" class="btn btn-success btn-sm float-end shadow-sm text-white"><span class="fa fa-check-circle"></span> Verify Shop</button>
                    <button v-if="!shopDetails.sales_manager && permission.assignSales" @click="addSalesManager()" class="btn btn-primary btn-sm float-end shadow-sm text-white me-3"><span class="fa fa-user-plus"></span> Assign Sales</button>
                </div>
            </div>
        </div>
        <div v-if="shopDetails.shop_details.shop_status === `VERIFIED`" class="bg-success text-white rounded-1 shadow-sm">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-6 align-self-center">
                    <h5 class="mb-0"><span class="fa fa-check-circle"></span> Verified Shop.</h5>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
        <div v-if="shopDetails.shop_details.shop_status === `BANNED`" class="bg-danger text-white rounded-1 shadow-sm">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-6 align-self-center">
                    <h5 class="mb-0"><span class="fa fa-ban"></span> Banned Shop.</h5>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
        <div class="bg-white rounded-1 shadow-sm mt-2">
            <div class="row mx-0 border-bottom p-3">
                <div class="col-md-3 align-self-center">
                    <h5 class="mb-0"><strong>Orders</strong></h5>
                </div>
                <div class="col-md-6 align-self-center">
                    <h6 class="text-end mb-0">Total spent <strong>{{orders.total_spent | numFormat}} ETB</strong> on <strong>{{orders.orders_count}}</strong> orders</h6>
                </div>
                <div class="col-md-3 align-self-end">
                    <form action="#" @submit.prevent="exportOrders">
                        <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                    </form>
                </div>
            </div>
            <div class="row mx-0 border-bottom p-3">
                <table class="table">
                    <tbody>
                        <tr v-for="order,index in ordersData" :key="index">
                            <td>{{order.order_no}}</td>
                            <td>{{order.created_at | moment("MMM Do YYYY")}}</td>
                            <td>{{order.order_status}}</td>
                            <td>{{order.items_count}} Items</td>
                            <td><strong>{{order.total | numFormat}} ETB</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row p-3">
                <nav v-if="(orders.orders.total > orders.orders.per_page)" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                    <ul class="pagination justify-content-center">
                        <li v-for="pd,index in paginationData" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                            <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                                <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            
        </div>
    </div>
</div>
</template>
<script>
import addSalesModalVue from './addSalesModal.vue'
import addShopModalVue from './addShopModal.vue'
import editSalesModalVue from './editSalesModal.vue'
import editShopModalVue from './editShopModal.vue'
import qrModalVue from './qrModal.vue'
import verifyShopModalVue from './verifyShopModal.vue'
export default {
    data(){
        return{
            shopDetails:{
                shop_details:{
                    country_code:null,
                    created_at:null,
                    f_name:null,
                    l_name:null,
                    phone_no:null,
                    shop_status:null
                }
            },
            orders:{},
            permission:{},
            paginationData:{},
            locations:{},
            ordersData:{}
        }
    },
    mounted(){
        this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permission = this.$store.state.auth.permissions
        })
            this.getShopDetails()
            this.getShopOrders()
            this.getShopLocations()
    },
    methods:{
        generateQr(){
            this.$modal.show(
                qrModalVue,
                {id:this.$route.params.id},
                {height:'auto', width:"600px"}
            )
        },
        async exportOrders(){
            await axios.post('/exportCustomerOrders', {id:this.$route.params.id}, {responseType: 'blob'})
            .then( response =>{
                const href = URL.createObjectURL(response.data);

                // create "a" HTML element with href to file & click
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', this.shopDetails.shop_details.phone_no+'_orders_'+Date.now()+'.xlsx'); //or any other extension
                document.body.appendChild(link);
                link.click();

                // clean up "a" element & remove ObjectURL
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        },
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data.orders.links
                this.orders = response.data
                this.ordersData = response.data.orders.data
            })
        },
        async banShop(){
            var check = confirm('Are you sure you want to ban this shop?')
            if(check){
                await axios.put('/banShop/'+this.shopDetails.shop_details.id)
                .then( response =>{
                    this.getShopDetails()
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Shop Banned',
                        text: 'Shop Banned successfully'
                    });
                })
            }
            
        },
        async unbanShop(){
            var check = confirm('Are you sure you want to activate this shop?')
            if(check){
                await axios.put('/unbanShop/'+this.shopDetails.shop_details.id)
                .then( response =>{
                    this.getShopDetails()
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Shop Banned',
                        text: 'Shop Banned successfully'
                    });
                })
            }
            
        },
        editShop(address){
            this.$modal.show(
                editShopModalVue,
                {address:address},
                {height:"auto", width:'500px'},
                {"closed":this.updateData}
            )
        },
        updateData(){
            this.getShopDetails()
            this.getShopOrders()
            this.getShopLocations()
        },
        addSalesManager(){
            this.$modal.show(
                addSalesModalVue,
                {shop_id:this.$route.params.id},
                {height:"auto", width:'500px'},
                {"closed":this.updateData}
            )
        },
        editSalesManager(sales_manager){
            this.$modal.show(
                editSalesModalVue,
                {shop_id:this.$route.params.id, sales_manager:sales_manager},
                {height:"auto", width:'500px'},
                {"closed":this.updateData}
            )
        },
        addShop(){
            this.$modal.show(
                addShopModalVue,
                {shop_id:this.$route.params.id},
                {height:"auto", width:'500px'},
                {"closed":this.updateData}
            )
        },
        verifyModal(){
            this.$modal.show(
                verifyShopModalVue,
                {shop_id:this.$route.params.id},
                {height:"auto", width:'500px'},
                {"closed":this.updateData}
            )
        },
        async getShopDetails(){
            await axios.get('/shopDetails/'+this.$route.params.id)
            .then( response =>{
                this.shopDetails = response.data
            })
        },
        async getShopOrders(){
            await axios.get('/shopOrders/'+this.$route.params.id)
            .then( response =>{
                this.paginationData = response.data.orders.links
                this.orders = response.data
                this.ordersData = response.data.orders.data
            })
        },
        async getShopLocations(){
            await axios.get('/shopLocations/'+this.$route.params.id)
            .then( response =>{
                this.locations = response.data
            })
        }
    }
}
</script>