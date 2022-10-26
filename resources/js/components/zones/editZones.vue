<template>
<form action="#" @submit.prevent="updateZone">
    <div class="row p-4">
        <div class="col-md-6">
            <label for="">City</label>
            <input required v-model="formData.city" type="text" class="form-control" placeholder="City">
        </div>
        <div class="col-md-6">
            <label for="">Sub city</label>
            <input required v-model="formData.subcity" type="text" class="form-control" placeholder="Sub city">
            <input type="hidden" v-model="formData.route">
        </div>
        <div class="col-md-12 mt-3">
            <h6 class="mt-3 mb-3"><span class="fa fa-info-circle"></span> Drag the nodes on the polygon to adjust the area of the zone.</h6>
            <gmap-map :center="{lat: 8.9806, lng: 38.7578}" :zoom="12" style="width: 100%; height: 400px">
                <gmap-polygon :paths="paths" :editable="true" :draggable="true" @paths_changed="updateEdited($event)">
                </gmap-polygon>
            </gmap-map>   
        </div>
        <div class="col-md-12 mt-3">
            <button type="submit" class="btn btn-primary form-control"> UPDATE ZONE</button>
        </div>
    </div> 
</form>

</template>
<script>
export default {
    props:["zone"],
    data(){
        return{
            formData:{
                route:{},
                city:null,
                subcity:null
            },
            edited: null,
            paths: []
        }
    },
    mounted(){
        this.formData = this.zone
        this.paths = JSON.parse(this.zone.route)
        this.formData.route = JSON.parse(this.zone.route)
        
    },
    methods:{
        async updateZone(){
            await axios.put('/updateZones/'+this.zone.id, this.formData)
            .then( response =>{
                this.$emit('close')
            })
        },
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
            this.formData.route = this.edited
        }
        
    }
}
</script>