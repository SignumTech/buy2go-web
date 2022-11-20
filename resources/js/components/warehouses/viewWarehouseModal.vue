<template>
<div class="row p-3">
    <div v-if="loadMap" class="col-md-12">
        <google-map :center="center" :zoom="13" style="width: 100%; height: 500px">
        <google-marker v-for="m,index in markers" :key="index" :position="m.position" :clickable="true" :draggable="false" @click="center=m.position"></google-marker>
        </google-map>
    </div>
</div>
</template>
<script>
export default {
    props:['location'],
    data(){
        return{
            loadMap:false,
            center: {
                lat: null,
                lng: null
            },
            markers: [{
                position: {
                lat: null,
                lng: null
                }
            }]
        }
    },
    mounted(){
        console.log(parseFloat(JSON.parse(this.location).lat))
        this.center.lat = parseFloat(JSON.parse(this.location).lat)
        this.center.lng = parseFloat(JSON.parse(this.location).lng)

        this.markers[0].position.lat = parseFloat(JSON.parse(this.location).lat)
        this.markers[0].position.lng = parseFloat(JSON.parse(this.location).lng)
        this.loadMap = true

    }
}
</script>