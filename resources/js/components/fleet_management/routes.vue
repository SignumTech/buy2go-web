<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Routes</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white rounded-1 shadow-sm p-3">
            <button @click="addRouteModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Routes</button>
            <button @click="addRouteModal()" class="btn btn-success btn-sm float-end shadow-sm text-white me-3"><span class="fa fa-route"></span> View on map</button>
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Route Name</th>
                        <th>Drivers</th>
                        <th>Zone</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="route,index in routes" :key="index">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center">
                            <span @click="editModal(route)" class="fa fa-edit "></span>
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
import addRouteModalVue from './addRouteModal.vue'
export default {
    data(){
        return{
            routes:{}
        }
    },
    mounted(){
        this.getRoutes()
    },
    methods:{
        addRouteModal(){
            this.$modal.show(
                addRouteModalVue,
                {},
                {height:"auto", width:"80%"},
                {"closed":this.getRoutes}
            )
        },
        async getRoutes(){
            await axios.get('/routes')
            .then( response =>{
                this.routes = response.data
            })
        },
    }
}
</script>