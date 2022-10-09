<template>
<div class="row p-3">
    <div class="col-md-12">
        <gmap-map :center="{lat: 8.9806, lng: 38.7578}" :zoom="12" style="width: 100%; height: 400px">
            <gmap-polygon :paths="paths" :editable="true" @paths_changed="updateEdited($event)">
            </gmap-polygon>
        </gmap-map>   
    </div>
</div>
</template>
<script>
export default {
    props:["route"],
    mounted(){
        this.paths = JSON.parse(this.route)
    },
    data(){
        return{
            center: {
                lat: 8.9806,
                lng: 38.7578
            },
            paths: [],
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