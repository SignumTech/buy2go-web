<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Fleet View</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <span v-for="driver,index in drivers" :key="index" class="fs-6 px-3 shadow-sm border badge rounded-pill bg-light text-dark me-3">
            <span v-if="driver.online_status == `ONLINE`" class="fa fa-circle fs-7 text-success"></span>
            <span v-if="driver.online_status == `OFFLINE`"  class="fa fa-circle fs-7 text-secondary"></span>
        {{driver.f_name}} | {{driver.l_plate}}</span>        
    </div>
    <div class="col-md-12 mt-3">
        <GmapMap :center="center" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">

            <gmap-info-window v-for="m,index in driverMarkers" :key="`info`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            
            <google-marker v-for="m,index in driverMarkers" :icon="(m.online_status == `ONLINE`)?`/storage/settings/truck.png`:`/storage/settings/offline.png`" :key="`truck`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,index)"></google-marker>
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
            currentMidx: null,
            shape: {
                coords: [10, 10, 10, 15, 15, 15, 15, 10],
                type: 'poly'
            },
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            drivers:[],
            infoWindowPos: null,
            driverMarkers:[],
            infoWinOpen:false,
            onlineDrivers:[]
        }
    },
    mounted(){
        this.getDrivers()
        this.connectOnline()
        this.connect()
    },
    beforeDestroy(){
        this.leaveChannel()
        
    },
    methods:{
        leaveChannel(){
            window.Echo.leave(`online_driver.0`)
        },
        connectOnline(){
            window.Echo.join(`online_driver.0`)
            .here((users)=>{
                this.onlineDrivers = users.filter(u=>(u.id !== this.$store.state.auth.user.id))
                users.forEach(user=>{
                    let drive = this.drivers.find(driver => driver.id == user.id)
                    if(drive){
                        drive.online_status = "ONLINE"
                    }
                    
                })
                
            } )
            .joining((user)=>{
                this.onlineDrivers.push(user)
                let drive = this.drivers.find(driver => driver.id == user.id)
                if(drive){
                    drive.online_status = "ONLINE"
                }
                
            })
            .leaving((user)=>{
                this.onlineDrivers = this.onlineDrivers.filter(u=>(u.id !== user.id))
                let drive = this.drivers.find(driver => driver.id == user.id)
                if(drive){
                    drive.online_status = "OFFLINE"
                }
            })
            .listen('NewMessage', (e) => {
                //
            });
        },
        toggleInfoWindow: function(marker, idx) {
            
            this.infoWindowPos = marker.position;
            this.infoOptions = marker.infoOptions;

            //check if its the same marker that was selected if yes toggle
            if (this.currentMidx === idx) {
              this.infoWinOpen = !this.infoWinOpen;
            }
            //if different marker set infowindow to open and reset current marker index
            else {
                console.log('test', idx)
              this.infoWinOpen = true;
              this.currentMidx = idx;

            }
          },
        async getDrivers(){
            await axios.get('/getDriversRaw')
            .then( response =>{
                this.drivers = response.data
                this.drivers.forEach(driver=>{
                    this.driverMarkers.push(
                        {
                            infoOptions:{
                                content:"<strong>"+driver.f_name+' '+driver.l_name+"</strong>",
                                pixelOffset:{
                                    height: -50,
                                    width: 0
                                }
                            },
                            position:{
                                lat:8.9806,
                                lng:38.7578
                            },
                            driver_id: driver.id,
                            online_status: driver.online_status
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
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).infoOptions.content = "<strong>"+e.name+"</strong><br><strong>"+e.assignment+"</strong>"
                
                //console.log(this.driverMarkers.find(driver=> driver.driver_id == 25))
            });
        },
    }
}
</script>