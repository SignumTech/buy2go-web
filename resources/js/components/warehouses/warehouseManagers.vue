<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Warehouse Managers</strong></h5>
    </div>
    <div class="col-md-12">
        <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white rounded-1 shadow-sm p-3">
            <button @click="addWarehouseManager()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Warehouse Managers</button>
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Warehouse Manager Name</th>
                        <th>Phone Number</th>
                        <th>Created at</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="manager,index in managers" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{manager.f_name}} {{manager.l_name}}</td>
                        <td>+{{manager.country_code}}-{{manager.phone_no}}</td>
                        <td>{{manager.created_at | moment("MMM Do YYYY H:m:s a")}}</td>
                        <td>
                            <span @click="editWarehouseManager(manager)" class="fa fa-edit "></span>
                            <span @click="deleteManager(manager.id)"  class="fa fa-trash-alt ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>    
</template>
<script>
import addWarehouseManagerVue from './addWarehouseManager.vue'
import editWarehouseManagentVue from './editWarehouseManagent.vue'
import LvProgressBar from 'lightvue/progress-bar';
export default {
    components:{
        LvProgressBar: LvProgressBar
    },
    data(){
        return{
            loading:true,
            managers:{}
        }
    },
    mounted(){
        this.getWarehouseManagers()
    },
    methods:{
        async deleteManager(id){
            if(confirm("Are you sure you want to permanently delete this warehouse manager?")){
                await axios.delete('/deleteWarehouseManager/'+id)
                .then( response =>{
                    this.getWarehouseManagers()
                })
            }
            
        },
        addWarehouseManager(){
            this.$modal.show(
                addWarehouseManagerVue,
                {"modalType" : "Add"},
                { height: "auto", width: "500px"},
                {"closed" : this.getWarehouseManagers}   
            )
        },
        editWarehouseManager(manager){
            this.$modal.show(
                editWarehouseManagentVue,
                {manager : manager},
                { height: "auto", width: "500px"},
                {"closed" : this.getWarehouseManagers}   
            )
        },
        async getWarehouseManagers(){
            this.loading = true
            await axios.get('/getWarehouseManagers')
            .then( response =>{
                this.loading = false
                this.managers = response.data
            })
        },
    }
}
</script>