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
            <google-marker v-for="m,index in shopMarkers" @click="addShop(m)"  :key="`sh`+index"  :icon="`/storage/settings/store.png`" :position="m.position" :clickable="true" :draggable="false" ></google-marker>
            <google-marker v-for="m,index in warehouseMarkers" :icon="`/storage/settings/warehouse.png`"  :key="`wh`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
            <gmap-polygon v-for="path,index in zonePath" :key="index" :paths="path" :editable="false" :draggable="false" @paths_changed="updateEdited($event)"></gmap-polygon>
            <!--<DirectionsRenderer
            v-for="route_path,index in formData.route_path"
            :key="`d`+index"
            travelMode="DRIVING"
            :origin="route_path[0]"
            :destination="route_path[1]"
            />-->
            <!--<DirectionsRenderer
            travelMode="DRIVING"
            v-for="paths,index in formData.route_path"
            :key="index"
            :origin="formData.route_path[0]"
            :destination="formData.route_path[formData.route_path.length - 1]"
            :waypoints="[{location:{lat:8.983966+(index/100),lng:38.555250+(index/100)}}, {location:{lat:9.015504+(index/100),lng:38.730625+(index/100)}}]"
            />-->
            <RouteRenderer
            travelMode="DRIVING"
            :routePath="newPath"
            :map="map"
            />

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
            <div class="col-md-12 mt-3">
                <label for="">Selected shops</label>
                <treeselect required :beforeClearAll="removeAll"  @deselect="removeShop" @select="addShop" v-model="formData.selectedShops" :disable-branch-nodes="true" :multiple="true" :options="selectedShops" />
            </div>
            <div class="col-md-12 mt-5">
                <button class="btn btn-primary form-control rounded-1">Save Route</button>
            </div>
        </div>
    </div>
</div>    
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
import DirectionsRenderer from '../orders/DirectionsRenderer'
import RouteRenderer from './RouteRenderer'
import {gmapApi} from 'vue2-google-maps'
export default {
    components:{
        DirectionsRenderer,
        RouteRenderer,
        Treeselect
    },
    computed: {
        google: gmapApi
    },
    watch:{
        addId: function(){
            console.log(this.addId)
        }
    },
    data(){
        return{
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
        this.getRoutes()
        this.getShops()
        this.getWarehouses()
        this.getZones()
        this.$refs.mapRef.$mapPromise.then(map => this.map = map)
        this.runSnapToRoad()
    },
    methods:{
        removeAll(){},
        removeShop(node){
           
            this.selectedShops = this.selectedShops.filter(data=>data.id != node.id)
            this.formData.selectedShops = this.formData.selectedShops.splice(this.formData.selectedShops.indexOf(node.id),1)
        },
        async runSnapToRoad(){
            /*var pathValues = [];
            for (var i = 0; i < path.getLength(); i++) {
                pathValues.push(path.getAt(i).toUrlValue());
            }
            var config = {
            method: 'get',
            url: 'https://roads.googleapis.com/v1/snapToRoads?path=-35.27801%2C149.12958%7C-35.28032%2C149.12907%7C-35.28099%2C149.12929%7C-35.28144%2C149.12984%7C-35.28194%2C149.13003%7C-35.28282%2C149.12956%7C-35.28302%2C149.12881%7C-35.28473%2C149.12836&interpolate=true&key=AIzaSyCRNebshVW6XSdv4X2Nxm3FGIt3qbA7UKU',
            headers: { }
            }
            await axios.get(config)
            .then( response =>{
                console.log( response )
                this.processSnapToRoadResponse(response.data)
                this.drawSnappedPolyline()
            })*/
        },
        processSnapToRoadResponse(data){
            this.snappedCoordinates = [];
            this.placeIdArray = [];
            console.log(data)
            for (var i = 0; i < data.snappedPoints.length; i++) {
                var latlng = new google.maps.LatLng(
                    data.snappedPoints[i].location.latitude,
                    data.snappedPoints[i].location.longitude);
                this.snappedCoordinates.push(latlng);
                this.placeIdArray.push(data.snappedPoints[i].placeId);
            }
        },
        drawSnappedPolyline(){
            var snappedPolyline = new google.maps.Polyline({
                path: snappedCoordinates,
                strokeColor: '#add8e6',
                strokeWeight: 4,
                strokeOpacity: 0.9,
            });

            snappedPolyline.setMap(map);
            polylines.push(snappedPolyline);
        },
        displayZone(){
            var myZone = this.zones.find(zone=> zone.id == this.formData.zone_id)
            this.zonePath = JSON.parse(myZone.route)
            var polyCheck = new google.maps.Polygon({
                                    paths: this.zonePath
                                });
            this.shopMarkers = [];
            this.warehouseMarkers = [];
            this.shops.forEach(shop => {
                shop.address.forEach( address =>{
                    if(google.maps.geometry.poly.containsLocation(JSON.parse(address.geolocation), polyCheck)){
                        this.shopMarkers.push({
                            position: JSON.parse(address.geolocation),
                            id: address.id,
                            address: address.regular_address,
                            infoOptions: {
                                content: '<strong><span class="fa fa-plus"></span> Shop: '+shop.f_name+'</strong><br>'+'<strong>'+shop.phone_no+'</strong>',
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