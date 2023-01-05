<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Fleet View</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <span @click="activateAll()" :class="(activeNav=='ALL')?`fs-6 px-3 shadow-sm border border-success border-3 badge rounded-pill bg-light text-dark me-3`:`fs-6 px-3 shadow-sm border badge rounded-pill bg-light text-dark me-3`" style="cursor:pointer">
            All Drivers
        </span> 
        <span @click="centerDriver(driver.id, index)" v-for="driver,index in drivers" :key="index" :class="(activeNav==driver.id)?`fs-6 px-3 shadow-sm border border-success border-3 badge rounded-pill bg-light text-dark me-3 mt-2`:`fs-6 px-3 mt-2 shadow-sm border badge rounded-pill bg-light text-dark me-3`" style="cursor:pointer">
            <span v-if="driver.online_status == `ONLINE`" class="fa fa-circle fs-7 text-success"></span>
            <span v-if="driver.online_status == `OFFLINE`"  class="fa fa-circle fs-7 text-secondary"></span>
            {{driver.f_name}} | {{driver.l_plate}}</span>        
    </div>
    <div class="col-md-12 mt-3">
        <GmapMap :center="center" :zoom="zoom" style="width: 100%; height: 500px" ref="mapRef">

            <div v-if="type==`ALL`">
                <gmap-info-window v-for="m,index in driverMarkers" :key="`info`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
                </gmap-info-window>
            </div>
            <div v-if="type==`SELECTED`">
                <gmap-info-window :options="driverMarkers[selected].infoOptions" :position="driverMarkers[selected].position" :opened="true" @closeclick="infoWinOpen=false">
                </gmap-info-window>
            </div>
            
            <div v-if="type==`ALL`">
                <google-marker v-for="m,index in driverMarkers" :icon="(m.online_status == `ONLINE`)?`/storage/settings/truck.png`:`/storage/settings/offline.png`" :key="`truck`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,index)"></google-marker>
            </div>
            <div v-if="type==`SELECTED`">
                <google-marker :icon="(driverMarkers[selected].online_status == `ONLINE`)?`/storage/settings/truck.png`:`/storage/settings/offline.png`" :position="driverMarkers[selected].position" :clickable="true" :draggable="false" @click="toggleInfoWindow(driverMarkers[selected],index)"></google-marker>
            </div>
            
            <!--<gmap-polygon v-for="path,index in paths" :key="index" :paths="path" :editable="false" :draggable="true" @paths_changed="updateEdited($event)"></gmap-polygon>-->
        </GmapMap>
    </div>
</div>    
</template>
<script>
export default {
    data(){
        return{
            activeNav:'ALL',
            selected:null,
            type:'ALL',
            center:{
                lat: 8.9806,
                lng: 38.7578
            },
            zoom:12,
            loading:false,
            currentMidx: null,
            shape: {
                coords: [10, 10, 10, 15, 15, 15, 15, 10],
                type: 'poly'
            },
            drivers:[],
            infoWindowPos: null,
            driverMarkers:[],
            tempMarkers:[],
            infoWinOpen:false,
            onlineDrivers:[],
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
        startTimer(){
            setInterval(() => {
                this.driverMarkers.forEach(driver=>{
                driver.reset_timer--
                    if(driver.reset_timer == 0){
                        driver.online_status = 'OFFLINE'
                        this.drivers.find(dr => dr.id == driver.driver_id).online_status = 'OFFLINE'
                        driver.reset_timer = 10
                    }
                }) 
            }, 1000);

        },
        activateAll(){
            this.activeNav = 'ALL'
            this.type = 'ALL'
            this.zoom = 12
        },
        centerDriver(id, index){
            this.type = 'SELECTED'
            this.activeNav = id
            this.selected = index
            const centermarker = this.driverMarkers.find(driver => driver.driver_id == id)
            if(centermarker){
                this.center = centermarker.position
                this.zoom = 14
            }
            
        },
        leaveChannel(){
            window.Echo.leave(`online_driver.0`)
        },
        connectOnline(){
            window.Echo.join(`online_driver.0`)
            .here((users)=>{
                this.onlineDrivers = users.filter(u=>(u.id !== this.$store.state.auth.user.id))
                users.forEach(user=>{
                    let drive = this.drivers.find(driver => driver.id == user.id)
                    let drivemarker = this.driverMarkers.find(driver => driver.driver_id == user.id)
                    if(drive){
                        drive.online_status = "ONLINE"
                    }
                    if(drivemarker){
                        drivemarker.online_status = "ONLINE"
                    }
                    
                })
                
            })
            .joining((user)=>{
                this.onlineDrivers.push(user)
                let drive = this.drivers.find(driver => driver.id == user.id)
                let drivemarker = this.driverMarkers.find(driver => driver.driver_id == user.id)
                if(drive){
                    drive.online_status = "ONLINE"
                }
                if(drivemarker){
                        drivemarker.online_status = "ONLINE"
                    }
                
            })
            .leaving((user)=>{
                this.onlineDrivers = this.onlineDrivers.filter(u=>(u.id !== user.id))
                let drive = this.drivers.find(driver => driver.id == user.id)
                let drivemarker = this.driverMarkers.find(driver => driver.driver_id == user.id)
                if(drive){
                    drive.online_status = "OFFLINE"
                }
                if(drivemarker){
                        drivemarker.online_status = "OFFLINE"
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
                            online_status: driver.online_status,
                            reset_timer:15
                        }
                    )
                })
                this.tempMarkers = this.driverMarkers
                this.startTimer()
            })
        },
        connect(){
            window.Echo.private('driver_location.0')
            .listen('DriverLocation', (e) => {
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).position.lat = e.lat
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).position.lng = e.lng
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).infoOptions.content = "<strong>"+e.name+"</strong><br><strong>"+e.assignment+"</strong>"
                this.driverMarkers.find(driver=> driver.driver_id == e.driver_id).online_status = 'ONLINE'
                this.drivers.find(driver => driver.id == e.driver_id).online_status = 'ONLINE'
                //console.log(this.driverMarkers.find(driver=> driver.driver_id == 25))
            });
        },
    }
}
</script>