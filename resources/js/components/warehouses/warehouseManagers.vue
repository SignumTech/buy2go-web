<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Drivers</strong></h5>
    </div>
    <div class="col-md-12">
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
                        <td>{{manager.phone_no}}</td>
                        <td>{{manager.created_at | moment("MMM Do YYYY H:m:s a")}}</td>
                        <td>
                            <span @click="editModal(driver)" class="fa fa-edit "></span>
                            <span  class="fa fa-trash-alt ms-3"></span>
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
export default {
    data(){
        return{
            managers:{}
        }
    },
    mounted(){
        this.getWarehouseManagers()
    },
    methods:{
        addWarehouseManager(){
            this.$modal.show(
                addWarehouseManagerVue,
                {"modalType" : "Add"},
                { height: "auto", width: "500px"},
                {"closed" : this.getWarehouseManagers}   
            )
        },
        async getWarehouseManagers(){
            await axios.get('/getWarehouseManagers')
            .then( response =>{
                this.managers = response.data
            })
        },
    }
}
</script>