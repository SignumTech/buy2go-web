<template>
    <form action="#" @submit.prevent="addVisit">
        <div class="row p-4">
            <div class="col-md-12">
                <h5 class="m-0">Add a visit</h5>
            </div>
            <div class="col-md-12 mt-3">
                <label for="">Route</label>
                <select required @change="getRouteDrivers()" v-model="formData.route_id" class="form-select">
                    <option value=""></option>
                    <option v-for="route,index in routes" :key="index" :value="route.id">{{route.route_name}} | {{route.zone_name}}</option>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <label for="">Driver</label>
                <select required v-model="formData.user_id" :disabled="(formData.route_id)?false:true" class="form-select">
                    <option value=""></option>
                    <option v-for="driver,index in drivers" :key="index" :value="driver.driver_id">{{driver.f_name}} {{driver.l_name}} | {{driver.l_plate}}</option>
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <label for="">Visit Reward (in balance)</label>
                <input v-model="formData.commission" required class="form-control" type="number" placeholder="Reward Balance">
            </div>
            <div class="col-md-12 mt-4">
                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> Add Visit</button>
            </div>
        </div>
    </form>

</template>
<script>
export default {
    data(){
        return{
            formData:{
                route_id:null,
                user_id:null,
                commission:null,
            },
            routes:{},
            drivers:{},
        }
    },
    mounted(){
        this.getRoutes()
    },
    methods:{
        async addVisit(){
            await axios.post('/visits', this.formData)
            .then( response =>{
                this.$emit('close')
            })
            .catch( response => {
                
            })
        },
        async getRoutes(){
            await axios.get('/getRoutesRaw')
            .then( response =>{
                this.routes = response.data
            })
        },
        async getRouteDrivers(){
            await axios.get('/getRouteDrivers/'+this.formData.route_id)
            .then( response =>{
                this.drivers = response.data
            })
        }
    }
}
</script>