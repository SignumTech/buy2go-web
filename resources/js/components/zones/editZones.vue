<template>
<form action="#" @submit.prevent="updateZone">
    <div class="row p-4">
        <div class="col-md-6">
            <label for="">Zone Name</label>
            <input required v-model="formData.zone_name" class="form-control" type="text" placeholder="Zone name">
        </div>
        <div class="col-md-6">
            <label for="">Sub city</label>
            <select required v-model="formData.sub_city_id" class="form-select">
                <option value=""></option>
                <option v-for="sc,index in subCities" :key="index" :value="sc.id">{{sc.sub_city_name}}/{{sc.city_name}}</option>
            </select>
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
            paths: [],
            subCities:{}
        }
    },
    mounted(){
        this.formData = this.zone
        this.paths = JSON.parse(this.zone.route)
        this.formData.route = JSON.parse(this.zone.route)
        this.getSubCities()
        
    },
    methods:{
        async getSubCities(){
            await axios.get('/getSubCities')
            .then( response =>{
                this.subCities = response.data
            })
        },
        async updateZone(){
            await axios.put('/updateZones/'+this.zone.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    title: 'Zone Updated',
                    text: 'Zone updated successfully'
                });
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