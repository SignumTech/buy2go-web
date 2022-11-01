<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Customers</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white rounded-1 shadow-sm p-3">
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
                        <td class="align-middle">{{shop.phone_no}}</td>
                        <td class="align-middle">
                            <p v-for="address,index in shop.address" :key="index"><span class="fa fa-map-marker-alt"></span> {{address.regular_address}} | {{address.route_name}}</p>
                        </td>
                        <td class="align-middle">{{shop.shop_status}}</td>
                        <td class="align-middle">{{shop.created_at | moment("MMM Do YYYY")}}</td>
                        <td><router-link :to="`/customerDetails/`+shop.id">Shop Details <span class="fa fa-external-link-alt"></span></router-link></td>
                    </tr>
                </tbody>
                
            </table>
        </div>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            shops:{}
        }
    },
    mounted(){
        this.getShops()
    },
    methods:{
        async getShops(){
            await axios.get('/getShops')
            .then( response =>{
                this.shops = response.data
            })
        }
    }
}
</script>