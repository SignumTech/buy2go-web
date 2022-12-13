<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Fleet View</strong></h5>
    </div>
    <div class="col-md-12">
        <GmapMap :center="center" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">

            <gmap-info-window v-for="m,index in driverMarkers" :key="index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            
            <google-marker v-for="m,index in driverMarkers" :icon="`/storage/settings/truck.png`" :key="index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
            <!--<gmap-polygon v-for="path,index in paths" :key="index" :paths="path" :editable="false" :draggable="true" @paths_changed="updateEdited($event)"></gmap-polygon>-->
        </GmapMap>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            loading:false,
            shape: {
                coords: [10, 10, 10, 15, 15, 15, 15, 10],
                type: 'poly'
            },
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            drivers:{},
            driverMarkers:[]
        }
    },
    mounted(){
        this.getDrivers()
        this.connect()
    },
    methods:{
        async getDrivers(){
            await axios.get('/getDriversRaw')
            .then( response =>{
                this.drivers = response.data
                this.drivers.forEach(driver=>{
                    this.driverMarkers.push(
                        {
                            infoOptions:{
                                content:"<strong>Test</strong><br><strong>Delivering Order #00001</strong>",
                                pixelOffset:{
                                    height: -50,
                                    width: 0
                                }
                            },
                            position:{
                                lat:8.9806,
                                lng:38.7578
                            },
                            driver_id: driver.id
                        }
                    )
                })
            })
        },
        connect(){
            window.Echo.private('driver_location.0')
            .listen('DriverLocation', (e) => {
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).position.lat = e.lat
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).position.lng = e.lng
                
                //console.log(this.driverMarkers.find(driver=> driver.driver_id == 25))
            });
        },
    }
}
</script>