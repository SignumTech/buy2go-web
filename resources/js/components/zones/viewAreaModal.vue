<template>
<div class="row p-3">
    <div class="col-md-12">
        <google-map :center="center" :zoom="10" style="width: 100%; height: 500px">
        <google-marker v-for="m,index in markers" :key="index" :position="m.position" :clickable="true" :draggable="true" @click="center=m.position"></google-marker>
        </google-map>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            markers: [{
                position: {
                lat: 8.9806,
                lng: 38.7578
                }
            }, {
            position: {
                lat: 9.00,
                lng: 38.7578
                }
            }]
        }
    }
}
</script>