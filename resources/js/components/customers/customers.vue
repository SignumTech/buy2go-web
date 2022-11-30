<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Customers</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="row m-0 p-3 border-bottom">
                <div class="col-md-4">
                    <input type="text" v-model="queryData.queryItem" class="form-control" placeholder="Phone/Name">
                </div>
                <div class="col-md-4">
                    <button @click="searchUser()" class="btn btn-primary"><span class="fa fa-search"></span> Search</button>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-1 shadow-sm mt-3 p-3">
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Shop Owner</th>
                        <th>Phone Number</th>
                        <th>Shop Locations</th>
                        <th>Shop status</th>
                        <th>Registration Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="shop,index in shops" :key="index">
                        <td class="align-middle">{{index+1}}</td>
                        <td class="align-middle">{{shop.f_name}} {{shop.l_name}}</td>
                        <td class="align-middle">+{{shop.country_code}}-{{shop.phone_no}}</td>
                        <td class="align-middle">
                            <p v-for="address,index in shop.address" :key="index"><span class="fa fa-map-marker-alt"></span> {{address.regular_address}} | {{address.route_name}}</p>
                        </td>
                        <td class="align-middle">{{shop.shop_status}}</td>
                        <td class="align-middle">{{shop.created_at | moment("MMM Do YYYY")}}</td>
                        <td><router-link :to="`/customerDetails/`+shop.id">Shop Details <span class="fa fa-external-link-alt"></span></router-link></td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                    <ul class="pagination justify-content-center">
                        <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                            <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                                <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
        </div>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            shops:{},
            paginationData:{},
            queryData:{
                queryItem: ""
            },
        }
    },
    mounted(){
        this.getShops()
    },
    methods:{
        async searchUser(){
            await axios.post('/searchCustomer', this.queryData)
            .then( response =>{
                this.paginationData = response.data
                this.shops = response.data.data;
            })
        },
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.shops = response.data.data;
            })
        },
        async getShops(){
            await axios.get('/getShops')
            .then( response =>{
                this.paginationData = response.data
                this.shops = response.data.data;
            })
        }
    }
}
</script>