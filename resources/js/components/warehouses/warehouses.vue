<template>
<div class="row mt-4">
<div class="col-md-12">
    <h5><strong>Warehouses</strong></h5>
</div>
<div class="col-md-12 mt-3">
    <div class="bg-white rounded-1 p-3 shadow-sm">
        <button @click="addWarehouseModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Warehouse</button>
        <table class="table px-2 mt-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Warehouse Name</th>
                    <th>Stock</th>
                    <th>location</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="warehouse,index in warehouses" :key="index">
                    <td>{{index+1}}</td>
                    <td>{{warehouse.w_name}}</td>
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
        warehouses:{}
    }
},
mounted(){
    this.getWarehouses();
},
methods:{
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