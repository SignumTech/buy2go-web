<template>
<div class="row p-4">
    <div class="col-md-12">
        <h5 class="m-0"><strong>Routes</strong></h5>
    </div>
    <div class="col-md-9">
        <GmapMap :center="center" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">
            <gmap-info-window v-for="m,index in shopMarkers" :key="`s`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <gmap-info-window v-for="m,index in warehouseMarkers" :key="`w`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <google-marker v-for="m,index in shopMarkers" @click="displayShop($event.latLng)"  :key="`sh`+index"  :icon="`/storage/settings/store.png`" :position="m.position" :clickable="true" :draggable="false" ></google-marker>
            <google-marker v-for="m,index in warehouseMarkers" :icon="`/storage/settings/warehouse.png`"  :key="`wh`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
            <gmap-polygon v-for="path,index in zonePath" :key="index" :paths="path" :editable="false" :draggable="false" @paths_changed="updateEdited($event)"></gmap-polygon>
            <!--<DirectionsRenderer
            travelMode="DRIVING"
            :origin="address"
            :destination="destination"
            />-->
        </GmapMap>
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <label for="">Route name</label>
                <input type="text" class="form-control" placeholder="Route Name">
            </div>
            <div class="col-md-12 mt-3">
                <label for="">Zone</label>
                <select v-model="formData.zone_id" @change="displayZone()" class="form-select">
                    <option value=""></option>
                    <option v-for="zone,index in zones" :key="index" :value="zone.id">{{zone.zone_name}}</option>
                </select>
            </div>
            <div class="col-md-12 mt-5">
                <button class="btn btn-primary form-control rounded-1">Save Route</button>
            </div>
        </div>
    </div>
</div>    
</template>
<script>
import {gmapApi} from 'vue2-google-maps'
export default {
    computed: {
        google: gmapApi
    },
    data(){
        return{
            map:null,
            formData:{
                route_name:null,
                zone_id:null,
                route_path:[],
            },
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
        this.getRoutes()
        this.getShops()
        this.getWarehouses()
        this.getZones()
        this.$refs.mapRef.$mapPromise.then(map => this.map = map)
    },
    methods:{
        displayZone(){
            var myZone = this.zones.find(zone=> zone.id == this.formData.zone_id)
            this.zonePath = JSON.parse(myZone.route)
            var polyCheck = new google.maps.Polygon({
                                    paths: this.zonePath
                                });
            this.shops.forEach(shop => {
                shop.address.forEach( address =>{
                    if(google.maps.geometry.poly.containsLocation(JSON.parse(address.geolocation), polyCheck)){
                        this.shopMarkers = [];
                        this.shopMarkers.push({
                            position: JSON.parse(address.geolocation),
                            infoOptions: {
                                content: '<strong> Shop: '+shop.f_name+'</strong><br>'+'<strong>'+shop.phone_no+'</strong>',
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
                if(google.maps.geometry.poly.containsLocation(JSON.parse(warehouse.location), polyCheck)){
                    this.warehouseMarkers = [];
                    this.warehouseMarkers.push({
                        position: JSON.parse(warehouse.location),
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
            console.log();
            
        },
        displayShop(position){
            console.log(position.lat())
        },
        async getShops(){
            await axios.get('/getShops')
            .then( response =>{
                this.shops = response.data                
            })
        },
        async getRoutes(){
            await axios.get('/routes')
            .then( response =>{
                this.routes = response.data
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
                this.zones = response.data
            })
        }
    }
}
</script>