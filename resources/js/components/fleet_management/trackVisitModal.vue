<template>
<div class="row p-4">
    <div class="col-md-4">
        <h5><strong>Visit No. {{visit.visit_no}} on route {{visit.route_name}}</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <GmapMap :center="center" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">
            <gmap-info-window v-if="visit.visit_status != `COMPLETED`" :options="driverMarker.infoOptions" :position="driverMarker.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <gmap-info-window v-for="m,index in markers" :key="`in`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <gmap-info-window v-for="m,index in confirmMarkers" :key="`in`+index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <google-marker v-if="visit.visit_status != `COMPLETED`" :icon="`/storage/settings/truck.png`" :position="driverMarker.position" :clickable="true" :draggable="false" ></google-marker>
            <google-marker v-for="m,index in confirmMarkers" :icon="`/storage/settings/confirm.png`" :shape="shape" :key="`cn`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
            <google-marker v-for="m,index in markers" :icon="`/storage/settings/store.png`" :shape="shape" :key="`sh`+index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
            <!--<gmap-polygon v-for="path,index in paths" :key="index" :paths="path" :editable="false" :draggable="true" @paths_changed="updateEdited($event)"></gmap-polygon>-->
        </GmapMap>
    </div>
</div>   
</template>
<script>
export default {
    props:['visit'],
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
            address:null,
            markers: [],
            confirmMarkers:[],
            driverMarker:{
                infoOptions:{
                    content:"<strong>"+this.visit.driver+"</strong>",
                    pixelOffset:{
                        height: -50,
                        width: 0
                    }
                },
                position:{
                    lat: 8.9806,
                    lng: 38.7578
                }
            },
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
        this.populateAddresses()
        this.connect()
    },
    methods:{
        populateAddresses(){
            
            var addresses = this.visit.addresses
            addresses.forEach(address =>{
                this.markers.push({
                    position:{
                        lat: parseFloat(JSON.parse(address.geolocation).lat),
                        lng: parseFloat(JSON.parse(address.geolocation).lng)
                    } ,
                    infoOptions: {
                        content: '<strong>'+address.regular_address+'</strong>',
                        //optional: offset infowindow so it visually sits nicely on top of our marker
                        pixelOffset: {
                        width: 0,
                        height: -35
                        }
                    }
                })
                if(address.confirm_location){
                    this.confirmMarkers.push({
                        position:{
                            lat: parseFloat(JSON.parse(address.confirm_location).lat),
                            lng: parseFloat(JSON.parse(address.confirm_location).lng)
                        } ,
                        infoOptions: {
                            content: '<strong>Visited '+address.regular_address+'</strong><br><strong>'+address.updated_at+'</strong>',
                            //optional: offset infowindow so it visually sits nicely on top of our marker
                            pixelOffset: {
                            width: 0,
                            height: -35
                            }
                        }
                    })                    
                }

                this.loading = true
                //this.startSimulation()
            });
        },
        startSimulation(){
            window.setInterval(()=>{
                this.driverMarker.position.lng -= 0.0001 
                this.driverMarker.position.lng -= 0.0002 
            }, 500);
        },
        connect(){
            window.Echo.private('visit_location.'+this.$route.params.id)
            .listen('VisitLocation', (e) => {
                this.driverMarker.position.lng = e.lng
                this.driverMarker.position.lat = e.lat
            });
        },
    }
}
</script>