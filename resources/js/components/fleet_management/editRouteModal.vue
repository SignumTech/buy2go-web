<template>
<div class="row p-4">
    <div class="col-md-12">
        <h5 class="m-0"><strong>Routes</strong></h5>
    </div>
    <div class="col-md-9">
        <GmapMap :center="center" @click="displayShop($event.latLng)" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">
            <gmap-info-window v-for="m,index in shopMarkers" :key="`s`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <gmap-info-window v-for="m,index in warehouseMarkers" :key="`w`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <google-marker v-for="m,index in shopMarkers" @click="addShop(m)"  :key="`sh`+index"  :icon="markerIcon" :position="m.position" :clickable="true" :draggable="false" ></google-marker>
            <google-marker v-for="m,index in warehouseMarkers" :icon="`/storage/settings/warehouse.png`"  :key="`wh`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
            <gmap-polygon v-for="path,index in zonePath" :key="index" :paths="path" :editable="false" :draggable="false" @paths_changed="updateEdited($event)"></gmap-polygon>
        </GmapMap>
    </div>
    <div class="col-md-3">
        <form action="#" @submit.prevent="updateRoute">
            <div class="row">
                <div class="col-md-12">
                    <label for="">Route name</label>
                    <input v-model="formData.route_name" required type="text" class="form-control" placeholder="Route Name">
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">Zone</label>
                    <select required v-model="formData.zone_id" @change="displayZone()" class="form-select">
                        <option value=""></option>
                        <option v-for="zone,index in zones" :key="index" :value="zone.id">{{zone.zone_name}}</option>
                    </select>
                </div>
                <div class="col-md-12 mt-3">
                    <label for="">Selected shops</label>
                    <treeselect required :beforeClearAll="removeAll"  @deselect="removeShop" @select="addShop" v-model="formData.selectedShops" :disable-branch-nodes="true" :multiple="true" :options="selectedShops" />
                </div>
                <div class="col-md-12 mt-5">
                    <button type="submit" class="btn btn-primary form-control rounded-1">Save Route</button>
                </div>
            </div>
        </form>
    </div>
</div>    
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
import DirectionsRenderer from '../orders/DirectionsRenderer'
import RouteRenderer from './RouteRenderer'
import {gmapApi} from 'vue2-google-maps'
export default {
    props:["route_id"],
    components:{
        DirectionsRenderer,
        RouteRenderer,
        Treeselect
    },
    computed: {
        google: gmapApi
    },
    data(){
        return{
            routeDetail:{},
            markerIcon:'/storage/settings/store.png',
            addId:null,
            map:null,
            drawingManager:null,
            placeIdArray:[],
            polylines:[],
            snappedCoordinates:[],
            newPath:[],
            formData:{
                route_name:null,
                zone_id:null,
                selectedShops:[],
            },
            selectedShops:[],
            routes:{},
            shops:{},
            warehouses:{},
            zones:{},
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            shopMarkers:[],
            warehouseMarkers:[],
            zonePath:[],
            triangleCoords: [
                { lat: 25.774, lng: -80.19 },
                { lat: 18.466, lng: -66.118 },
                { lat: 32.321, lng: -64.757 },
            ]
        }
    },
    mounted(){
        this.getRoute()
        this.getRoutes()
        this.getShops()
        this.getWarehouses()
        this.$refs.mapRef.$mapPromise.then(map => this.map = map)

    },
    methods:{
        async getRoute(){
            await axios.get('/getRoute/'+this.route_id)
            .then( response =>{
                this.formData = response.data
                this.addShops()
                this.getZones() 
            })
        },
        addShops(){
            this.formData.addresses.forEach( address =>{  
                
                this.shopMarkers.push({
                    
                    position:{
                        lat:parseFloat(JSON.parse(address.geolocation).lat),
                        lng:parseFloat(JSON.parse(address.geolocation).lng)
                    } ,
                    id: address.id,
                    address: address.regular_address,
                    infoOptions: {
                        content: '<strong>'+address.regular_address+'</strong>',
                        //optional: offset infowindow so it visually sits nicely on top of our marker
                        pixelOffset: {
                        width: 0,
                        height: -35
                        }
                    }
                })

                this.selectedShops.push({
                    id:address.id,
                    label:address.regular_address
                })
                    

            })
        },
        removeAll(){},
        removeShop(node){
            this.selectedShops = this.selectedShops.filter(data=>data.id != node.id)
            this.formData.selectedShops = this.formData.selectedShops.splice(this.formData.selectedShops.indexOf(node.id),1)
        },
        displayZone(){
            var myZone = this.zones.find(zone=> zone.id == this.formData.zone_id)
            
            this.zonePath = JSON.parse(myZone.route)
            var polyCheck = new google.maps.Polygon({
                                    paths: this.zonePath
                                });
            //this.shopMarkers = [];
            this.warehouseMarkers = [];
            this.shops.forEach(shop => {
                shop.address.forEach( address =>{
                    if(google.maps.geometry.poly.containsLocation({
                            lat:parseFloat(JSON.parse(address.geolocation).lat),
                            lng:parseFloat(JSON.parse(address.geolocation).lng)
                        }, polyCheck)){
                        
                        this.shopMarkers.push({
                            
                            position:{
                                lat:parseFloat(JSON.parse(address.geolocation).lat),
                                lng:parseFloat(JSON.parse(address.geolocation).lng)
                            } ,
                            id: address.id,
                            address: address.regular_address,
                            infoOptions: {
                                content: '<strong>'+shop.f_name+'</strong>',
                                //optional: offset infowindow so it visually sits nicely on top of our marker
                                pixelOffset: {
                                width: 0,
                                height: -35
                                }
                            }
                        })
                    }

                })

            }); 
            this.warehouses.forEach(warehouse => {
                if(google.maps.geometry.poly.containsLocation({
                            lat:parseFloat(JSON.parse(warehouse.location).lat),
                            lng:parseFloat(JSON.parse(warehouse.location).lng)
                    }, polyCheck)){

                    this.warehouseMarkers.push({
                        position:{
                            lat:parseFloat(JSON.parse(warehouse.location).lat),
                            lng:parseFloat(JSON.parse(warehouse.location).lng)
                        } ,
                        infoOptions: {
                            content: '<strong>'+warehouse.w_name+'</strong>',
                            //optional: offset infowindow so it visually sits nicely on top of our marker
                            pixelOffset: {
                            width: 0,
                            height: -35
                            }
                        }
                    })
                }

            });                   
            
            
        },
        addShop(data){
            if(!this.formData.selectedShops.includes(data.id)){
                this.formData.selectedShops.push(data.id)
                this.selectedShops.push({
                    id:data.id,
                    label:data.address
                })
            }
            else{
                this.removeShop(data)
            }
            
        },
        async getShops(){
            await axios.get('/getShopsWithNoRoutes')
            .then( response =>{
                this.shops = response.data                
            })
        },
        async getRoutes(){
            await axios.get('/routes')
            .then( response =>{
                this.routes = response.data.data
            })
        },
        async getWarehouses(){
            await axios.get('/warehouses')
            .then( response =>{
                this.warehouses = response.data
            })
        },
        async getZones(){
            await axios.get('/getZones')
            .then( response =>{
                this.zones = response.data.data
                this.displayZone()
            })
        },
        async updateRoute(){
            await axios.put('/routes/'+this.formData.id, this.formData)
            .then( response =>{
                this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Route added!',
                        text: 'You have successfuly added a route'
                    });
                this.$emit('close')
            })
        }
    }
}
</script>