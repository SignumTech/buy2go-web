<template>
<div class="row p-4">
    <div class="col-md-12">
        <h5 class="m-0"><strong>Shipping Details | Order {{order.order_no}}</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="row mt-2">
            <div class="col-md-8">
                <GmapMap :center="center" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">
                    <gmap-info-window :options="infoOptions" :position="address" :opened="true" @closeclick="infoWinOpen=false">
                    </gmap-info-window>
                    <gmap-info-window v-for="m,index in markers" :key="index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
                    </gmap-info-window>
                    <google-marker v-if="!loading" :icon="`/storage/settings/store.png`" :position="address" :clickable="true" :draggable="false" ></google-marker>
                    <google-marker v-for="m,index in markers" :icon="`/storage/settings/warehouse.png`" :shape="shape" :key="index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
                    <!--<gmap-polygon v-for="path,index in paths" :key="index" :paths="path" :editable="false" :draggable="true" @paths_changed="updateEdited($event)"></gmap-polygon>-->
                    <DirectionsRenderer
                    travelMode="DRIVING"
                    :origin="address"
                    :destination="destination"
                    />
                </GmapMap>
            </div>
            <div class="col-md-4">
                <form action="#" @submit.prevent="shipOrder">
                    <div class="row">
                        <div class="col-md-12"></div>
                        <div class="col-md-12">
                            <label for="">Warehouse</label>
                            <select required @change="setDestination()" v-model="formData.warehouse_id" class="form-select" id="">
                                <option value=""></option>
                                <option v-for="warehouse,index in warehouses" :key="index" :value="warehouse.id">{{warehouse.w_name}}</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-4">
                            <h6>Drivers List</h6>
                        </div>
                        <div class="col-md-12">
                            <ul class="list-group">
                                <li v-for="driver,index in drivers" :key="index" class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-2 align-self-center text-center">
                                            <input name="driver" required class="form-check-input" type="radio" v-model="formData.driver_id" :value="driver.driver_id">
                                        </div>
                                        <div class="col-md-10 border-start">
                                            <strong>{{driver.f_name}} {{driver.l_name}} ({{driver.l_plate}})</strong> <br>
                                            {{driver.active_assignments}} Active assignments <br>
                                            +251-{{driver.phone_no}}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-truck"></span> SHIP ORDER</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>    
</template>
<script>
import DirectionsRenderer from "./DirectionsRenderer";
export default {
    components: {
      DirectionsRenderer,
    },
    props:["id"],
    data(){
        return{
            formData:{
                warehouse_id:"",
                zone_id:null,
                driver_id:""
            },
            driver_id:{},
            destination:{},
            loading:true,
            drivers:{},
            order:{},
            address_name:null,
            address:null,
            orderItems:{},
            shape: {
                coords: [10, 10, 10, 15, 15, 15, 15, 10],
                type: 'poly'
            },
            zones:{},
            warehouses:{},
            map:null,
            location:{
                    lat: 8.9806,
                    lng: 38.7578
                },
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            markers: [],
            paths: [],
            infoWindowPos: null,
            infoWinOpen: false,
            currentMidx: null,

            infoOptions: {
                content: '',
                //optional: offset infowindow so it visually sits nicely on top of our marker
                pixelOffset: {
                width: 0,
                height: -35
                }
            },
            
        }
    },
    mounted(){
        this.$refs.mapRef.$mapPromise.then(map => this.map = map);
        //this.getZones()
        this.getWarehouses()
        this.getOrder()
    },
    methods:{
        async shipOrder(){
            await axios.put('/assignDetails/'+this.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Details Assigned',
                    text: 'Details assigned successfully'
                });
                window.location.replace('/ordersList')
            })
        },
        setDestination(){
            var myWarehouse = this.warehouses.find(warehouse=> warehouse.id == this.formData.warehouse_id)
            this.destination = JSON.parse(myWarehouse.location)
        },
        async getDrivers(id){
            await axios.get('/getRouteDrivers/'+id)
            .then( response =>{
                this.drivers = response.data
            })
        },
        async getOrder(){
            this.loading = true
            await axios.get('/orders/'+this.id)
            .then( response =>{
                this.order = response.data.order_details
                this.orderItems = response.data.order_items
                
                this.getAddress(response.data.order_details.delivery_details)
            })
        },
        async getAddress(id){
            await axios.get('/addressBooks/'+id)
            .then( response =>{
                this.infoOptions.content = response.data.regular_address
                this.address = JSON.parse(response.data.geolocation)
                this.center = JSON.parse(response.data.geolocation)
                this.loading = false
                this.getDrivers(response.data.route_id)
            })
        },
        async getWarehouses(){
            await axios.get('/warehouses')
            .then( response =>{
                this.warehouses = response.data
                this.warehouses.forEach(warehouse => {
                    this.markers.push({
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
                });
            })
        },
        toggleInfoWindow: function(marker, idx) {
            this.infoWindowPos = marker.position;
            this.infoOptions.content = marker.infoText;

            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx == idx) {
              this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker index
            else {
              this.infoWinOpen = true;
              this.currentMidx = idx;

            }
          }
    }
}
</script>