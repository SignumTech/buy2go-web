<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Visit Details</strong></h5>
    </div>
    <div class="col-md-12 mt-2">
        <div class="bg-white rounded-1 p-3">
            <div class="row mx-0 mb-3">
                <div class="col-md-2 align-self-center">
                    <router-link to="/visitsList" style="cursor:pointer"><span class="fa fa-arrow-left fs-5"></span></router-link>
                </div>
                
                <div class="col-md-8 d-flex justify-content-center">
                    <div class="text-center">
                        <div class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="shopping-cart"></i>
                        </div>
                        
                        <p class="text-center m-0">Visit Assigned</p>
                    </div>
                    <div>
                        <div v-if="visit.visit_status == `PENDING` || visit.visit_status == `IN_PROGRESS` || visit.visit_status == `COMPLETED`" class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="truck"></i>
                        </div>
                        <div v-else class="p-2 mx-5 bg-secondary rounded-5 text-white">
                            <i data-feather="truck"></i>
                        </div>
                        <p class="text-center m-0">Visit In Progress</p>
                    </div>
                    <div>
                        <div v-if="visit.visit_status == `IN_PROGRESS` || visit.visit_status == `COMPLTED`" class="p-2 mx-5 bg-success rounded-5 text-white">
                            <i data-feather="check-circle"></i>
                        </div>
                        <div v-else class="p-2 mx-5 bg-secondary rounded-5 text-white">
                            <i data-feather="check-circle"></i>
                        </div>
                        <p class="text-center m-0">Visit Complete</p>
                    </div>
                </div>
            </div>
            <div class="row m-0">
                <div class="col-md-4 mt-3">
                    <h6 class="text-center">Visit No.</h6>
                    <h5 class="text-center"><strong>{{visit.visit_no}}</strong></h5>
                </div>
                <div class="col-md-4 mt-3">
                    <h6 class="text-center">Route Name</h6>
                    <h5 class="text-center"><strong>{{visit.route_name}}</strong></h5>
                </div> 
                <div class="col-md-4 mt-3">
                    <h6 class="text-center">Assigned Driver</h6>
                    <h5 class="text-center"><strong>{{visit.driver}} - {{visit.l_plate}}</strong></h5>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>Shop Location</th>
                                <th>Visit Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center" v-for="shop,index in visit.addresses" :key="index">
                                <td>{{shop.regular_address}}</td>
                                <td>{{shop.status}}</td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>

            </div>
        </div>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            visit:{}
        }
    },
    mounted(){
        this.getVisit()
    },
    methods:{
        async getVisit(){
            await axios.get('/visits/'+this.$route.params.id)
            .then( response =>{
                this.visit = response.data
                this.getShops(this.visit.route_id)
            })
        },
        async getShops(route_id){
            await axios.get('/getRoute/'+route_id)
            .then( response =>{
                this.shops = response.data.addresses
            })
        }
    }
}
</script>