<template>
    <div class="row p-3">
        <form action="#" @submit.prevent="updateManager">
            <div class="col-md-12">
                <h5>Edit Warehouse Manager</h5>
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
                <button type="submit" class="btn btn-primary form-control">UPDATE MANAGER</button>
            </div>
        </form>
    </div>
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
export default {
    props:['manager'],
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
        this.formData = this.manager
    },
    methods:{
        async updateManager(){
            await axios.put('/updateWarehouseManager/'+this.manager.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Warehouse Manager updated',
                    text: 'Warehouse Manager was updated successfully'
                });
                this.$emit('close')
            })
        },
    }
}
</script>