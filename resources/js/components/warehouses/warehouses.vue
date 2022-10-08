<template>
<div class="row mt-4">
    <div class="col-md-12">

        <google-map ref="mapRef" :center="center" :zoom="7" style="width: 100%; height: 500px">
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
                lat: 10.0,
                lng: 10.0
            },
            markers: [{
                position: {
                lat: 10.0,
                lng: 10.0
                }
            }, {
                position: {
                lat: 11.0,
                lng: 11.0
                }
            }],
            edited: null,
            paths: [
                [ {lat: 1.380, lng: 103.800}, {lat:1.380, lng: 103.810}, {lat: 1.390, lng: 103.810}, {lat: 1.390, lng: 103.800} ],
                [ {lat: 1.382, lng: 103.802}, {lat:1.382, lng: 103.808}, {lat: 1.388, lng: 103.808}, {lat: 1.388, lng: 103.802} ],
            ],
            paths_two: [
                [ {lat: 1.380, lng: 103.800}, {lat:1.380, lng: 103.810}, {lat: 1.390, lng: 103.810}, {lat: 1.390, lng: 103.800} ],
                [ {lat: 1.382, lng: 103.802}, {lat:1.382, lng: 103.808}, {lat: 1.388, lng: 103.808}, {lat: 1.388, lng: 103.802} ],
            ],
            map:null
        }
    },
    mounted(){
        this.$refs.mapRef.$mapPromise.then(map => this.map = map)
    },
    methods: {
          updateEdited(mvcArray) {
            let paths = [];
            for (let i=0; i<mvcArray.getLength(); i++) {
              let path = [];
              for (let j=0; j<mvcArray.getAt(i).getLength(); j++) {
                let point = mvcArray.getAt(i).getAt(j);
                path.push({lat: point.lat(), lng: point.lng()});
              }
              paths.push(path);
            }
            this.edited = paths;
          }
        }
}
</script>