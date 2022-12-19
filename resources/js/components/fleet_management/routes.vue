<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Routes</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <div class="row">
                <div class="col-md-3">
                    <label for="">Route Name</label>
                    <input v-model="queryData.route_name" type="text" class="form-control rounded-1" placeholder="Zone Name">
                </div>
                <div class="col-md-3">
                    <label for="">Zones</label>
                    <select v-model="queryData.zone_id" class="form-select rounded-1">
                        <option :value="null">All Zones</option>
                        <option v-for="zone,index in zones" :key="index" :value="zone.id">{{zone.zone_name}}</option>
                    </select>
                </div>
                <div class="col-md-3 align-self-end">
                    <button @click="filterRoutes()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                </div>
                <div class="col-md-3 align-self-end">
                    <form action="#" @submit.prevent="exportRoutes">
                        <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="bg-white rounded-1 shadow-sm p-3">
            <button @click="addRouteModal()" class="btn btn-primary btn-sm float-end shadow-sm text-white"><span class="fa fa-plus"></span> Add Routes</button>
            <!--<button @click="addRouteModal()" class="btn btn-success btn-sm float-end shadow-sm text-white me-3"><span class="fa fa-route"></span> View on map</button>-->
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Route Name</th>
                        <th>Drivers</th>
                        <th>Shops</th>
                        <th>Zone</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="route,index in routes" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{route.route_name}}</td>
                        <td>{{route.drivers_count}}</td>
                        <td>{{route.shops_count}}</td>
                        <td>{{route.zone_name}}</td>
                        <td class="text-center">
                            <span @click="editModal(route.id)" class="fa fa-edit "></span>
                            <span @click="deleteRoute(route.id)" class="fa fa-trash-alt ms-3"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                            <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>    
</template>
<script>
import addRouteModalVue from './addRouteModal.vue'
import editRouteModalVue from './editRouteModal.vue'
export default {
    data(){
        return{
            zones:{},
            routes:{},
            paginationData:{},
            queryData:{
                route_name:null,
                zone_id:null
            },
            
        }
    },
    mounted(){
        this.getRoutes()
        this.getZones()
    },
    methods:{
        async deleteRoute(id){
            if(confirm("Are you sure you want to permanently delete this route?")){
                await axios.delete('/routes/'+id)
                .then( response =>{
                    this.getRoutes()
                })
            }
        },
        editModal(id){
            this.$modal.show(
                editRouteModalVue,
                {route_id:id},
                {height:"auto", width:"80%"},
                {"closed":this.getRoutes}
            )
        },
        async filterRoutes(){
            await axios.post('/filterRoutes', this.queryData)
            .then( response => {
                this.paginationData = response.data
                this.routes = response.data.data
            })
        },
        async exportRoutes(){
            await axios.post('/exportRoutes', this.queryData, {responseType: 'blob'})
            .then( response =>{
                const href = URL.createObjectURL(response.data);

                // create "a" HTML element with href to file & click
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'routes'+Date.now()+'.xlsx'); //or any other extension
                document.body.appendChild(link);
                link.click();

                // clean up "a" element & remove ObjectURL
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        },
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.routes = response.data.data
            })
        },
        async getZones(){
            await axios.get('/getZones')
            .then( response =>{
                this.zones = response.data.data
            })
        },
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
                this.routes = response.data.data
                this.paginationData = response.data
            })
        },
    }
}
</script>