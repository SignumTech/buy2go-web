<template>
<div class="row p-4">
    <div class="col-md-4">
        <h5><strong>Visit No. {{visit.visit_no}}</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <GmapMap :center="center" :zoom="12" style="width: 100%; height: 500px" ref="mapRef">
            <gmap-info-window :options="infoOptions" :position="address" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <gmap-info-window v-for="m,index in markers" :key="index" :options="m.infoOptions" :position="m.position" :opened="true" @closeclick="infoWinOpen=false">
            </gmap-info-window>
            <google-marker v-if="!loading" :icon="`/storage/settings/store.png`" :position="address" :clickable="true" :draggable="false" ></google-marker>
            <google-marker v-for="m,index in markers" :icon="`/storage/settings/store.png`" :shape="shape" :key="index" :position="m.position" :clickable="true" :draggable="false" @click="toggleInfoWindow(m,i)"></google-marker>
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
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            address:null,
            markers: [],
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

    },
    methods:{
        populateAddresses(){
            console.log(this.visit)
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
                this.loading = true
            });
        }
    }
}
</script>