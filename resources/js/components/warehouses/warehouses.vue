<template>
<div class="row mt-4">
<div class="col-md-12">
    <h5><strong>Warehouses</strong></h5>
</div>
<div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <div class="row">
                <div class="col-md-3">
                    <label for="">Warehouse Name</label>
                    <input v-model="queryData.w_name" type="text" class="form-control rounded-1" placeholder="Warehouse Name">
                    
                </div>
                <div class="col-md-3">
                    <label for="">Warehouse Manager</label>
                    <select v-model="queryData.user_id" class="form-select rounded-1">
                        <option :value="null">All managers</option>
                        <option v-for="manager,index in managers" :key="index" :value="manager.id">{{manager.f_name}} {{manager.l_name}}</option>
                    </select>
                </div>
                <div class="col-md-3 align-self-end">
                    <button @click="filterWarehouses()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                </div>
                <div class="col-md-3 align-self-end">
                    <form action="#" @submit.prevent="exportWarehouses">
                        <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div class="col-md-12 mt-3">
    <div class="bg-white rounded-1 p-3 shadow-sm">
        <button @click="addWarehouseModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Warehouse</button>
        <table class="table px-2 mt-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Warehouse Name</th>
                    <th>Warehouse Manager</th>
                    <th>Stock</th>
                    <th>location</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="warehouse,index in warehouses" :key="index">
                    <td>{{index+1}}</td>
                    <td>{{warehouse.w_name}}</td>
                    <td>{{warehouse.f_name}} {{warehouse.l_name}}</td>
                    <td>{{warehouse.stock}}</td>
                    <td><h6 @click="viewArea(warehouse.location)" class="m-0" style="cursor:pointer"><strong>View on map <span class="fa fa-external-link-alt"></span></strong></h6></td>
                    <td class="text-center">
                        <span class="fa fa-trash-alt"></span>
                        <span @click="editModal(warehouse)" class="fa fa-edit ms-3"></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>    
</template>
<script>
import addWarehouseModalVue from './addWarehouseModal.vue';
import editWarehouseModalVue from './editWarehouseModal.vue';
import viewAreaModalVue from './viewWarehouseModal.vue';
export default {
data(){
    return{
        warehouses:{},
        queryData:{
            w_name:null,
            user_id:null,
        },
        managers:{}
    }
},
mounted(){
    this.getWarehouses();
    this.getWarehouseManager()
},
methods:{
    async getWarehouseManager(){
        await axios.get('/getWarehouseManagers')
        .then( response =>{
            this.managers = response.data
        })
    },
    async exportWarehouses(){
        await axios.post('/exportWarehouses', this.queryData, {responseType: 'blob'})
        .then( response =>{
            const href = URL.createObjectURL(response.data);

            // create "a" HTML element with href to file & click
            const link = document.createElement('a');
            link.href = href;
            link.setAttribute('download', 'warehouses'+Date.now()+'.xlsx'); //or any other extension
            document.body.appendChild(link);
            link.click();

            // clean up "a" element & remove ObjectURL
            document.body.removeChild(link);
            URL.revokeObjectURL(href);
        })
    },
    async filterWarehouses(){
        await axios.post('/filterWarehouses', this.queryData)
        .then( response =>{
            this.warehouses = response.data
        })
    },
    async getWarehouses(){
        await axios.get('/warehouses')
        .then( response =>{
            this.warehouses = response.data
        })
    },
    editModal(warehouse){
        this.$modal.show(
            editWarehouseModalVue,
            {warehouse:warehouse},
            {width:"700px",height:"auto"},
            {"closed":this.getWarehouses}
        )
    },
    viewArea(location){
        this.$modal.show(
            viewAreaModalVue,
            {location:location},
            {width:"700px",height:"auto"}
        )
    }
    ,
    addWarehouseModal(){
        this.$modal.show(
            addWarehouseModalVue,
            {},
            {width:"600px",height:"auto"},
            {"closed":this.getWarehouses}
        )
    }
}
}
</script>