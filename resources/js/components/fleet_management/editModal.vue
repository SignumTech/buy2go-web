<template>
    <div class="row p-3">
        <form action="#" @submit.prevent="addDriver">
            <div class="col-md-12">
                <h5>Edit Driver</h5>
            </div>
            <div class="col-md-12 mt-2">
                <label for="">First Name</label>
                <input required v-model="formData.f_name" type="text" placeholder="First Name" class="form-control">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Last Name</label>
                <input required v-model="formData.l_name" type="text" placeholder="Last Name" class="form-control">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Phone Number</label>
                <input required v-model="formData.phone_no" type="number" class="form-control" placeholder="Phone Number">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">License plate number</label>
                <input required v-model="formData.l_plate" type="text" class="form-control" placeholder="License plate number">
            </div>
            <div class="col-md-12 mt-2">
                <label for="">Routes</label>
                <treeselect
                :multiple="true"
                :options="routes"
                placeholder="Routes"
                v-model="formData.route_id"
                />
            </div>
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> UPDATE DRIVER</button>
            </div>
        </form>
    </div>
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
export default {
    components:{
        Treeselect
    },
    props:["driver","l_plate"],
    data(){
        return{
            formData:{
                f_name:"",
                l_name:"",
                l_plate:"",
                phone_no:"",
                zone_id:"",
                route_id:[]
            },
            routes:[]
        }
    },
    mounted(){
        this.formData = this.driver
        this.formData.l_plate = this.l_plate
        this.formData.route_id = []
        this.driver.routes.forEach(route=>{
            this.formData.route_id.push(route.id)
        })
        this.getRoutes()
    },
    methods:{
        async addDriver(){
            await axios.put('/drivers/'+this.formData.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Driver Updated',
                    text: 'Driver updated successfully'
                });
                this.$emit('close')
            })
        },
        async getRoutes(){
            await axios.get('/routes')
            .then( response => {
                response.data.forEach(route=>{
                    this.routes.push({
                        id:route.id,
                        label:route.route_name
                    })
                    
                })
            })
        }
    }
}
</script>