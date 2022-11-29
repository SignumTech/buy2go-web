<template>
    <div class="row p-3">
        <form action="#" @submit.prevent="addManager">
            <div class="col-md-12">
                <h5>Add Warehouse Manager</h5>
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
            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> ADD MANAGER</button>
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
    data(){
        return{
            formData:{
                f_name:"",
                l_name:"",
                phone_no:"",
            },
            routes:[]
        }
    },
    mounted(){
        this.getRoutes()
    },
    methods:{
        async addManager(){
            await axios.post('/addWarehouseManager', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Warehouse Manager added',
                    text: 'Warehouse Manager was added successfully'
                });
                this.$emit('close')
            })
        },
        async getRoutes(){
            await axios.get('/routes')
            .then( response => {
                response.data.data.forEach(route=>{
                    this.routes.push({
                        id:route.id,
                        label:route.route_name+' | '+route.zone_name
                    })
                })
            })
        }
    }
}
</script>