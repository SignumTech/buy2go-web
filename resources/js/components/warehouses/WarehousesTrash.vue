<template>
    <div class="row p-4">
        <div class="col-md-12">
            <h5><strong><span class="fa fa-trash-alt"></span> Recycle Bin</strong> <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
        </div>
        <div class="col-md-12 mt-3">
            <table class="table px-2 mt-2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Warehouse Name</th>
                    <th>Warehouse Manager</th>
                    <th>Stock</th>
                    <th>Created at</th>
                    <th>Deleted at</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="warehouse,index in warehouses" :key="index">
                    <td>{{index+1}}</td>
                    <td>{{warehouse.w_name}}</td>
                    <td>{{warehouse.f_name}} {{warehouse.l_name}}</td>
                    <td>{{warehouse.stock}}</td>
                    <td>{{warehouse.created_at | moment("MMM Do YYYY h:m:s a")}}</td>
                    <td>{{warehouse.deleted_at | moment("MMM Do YYYY h:m:s a")}}</td>
                    <td class="text-center">
                        <button @click="restoreWarehouse(warehouse.id)" class="btn btn-sm btn-success rounded-1"><span class="fa fa-trash-restore-alt"></span> Restore</button>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            warehouses:{},
        }
    },
    mounted(){
        this.getWarehouses()
    },
    methods:{
        async getWarehouses(){
            await axios.get('/getDeletedWarehouses')
            .then( response =>{
                this.warehouses = response.data
            })
        },
        async restoreWarehouse(id){
            var check = confirm("Are you sure you want to restore this warehouse?")
            if(check){
                await axios.put('/restoreWarehouse/'+id)
                .then( response =>{
                    this.getWarehouses()
                                    
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Warehouse Restored',
                        text: 'Warehouse Restored Successfully.'
                    });
                
                })
            }
        }
    }

}
</script>