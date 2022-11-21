<template>
<form action="#" @submit.prevent="addZone">
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
            <button type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> ADD ZONE</button>
        </div>
    </div> 
</form>

</template>
<script>
export default {
    data(){
        return{
            formData:{
                zone_name:null,
                route:{},
                sub_city_id:null
            },
            edited: null,
            paths: [
                [ {lat: 9.01171937808091, lng: 38.722167031625354}, {lat:8.959758487959377, lng: 38.76476823033494}, {lat: 9.011767475867545, lng:38.76162983979211}]
            ],
            subCities:{}
        }
    },
    mounted(){
        this.getSubCities()
    },
    methods:{
        async getSubCities(){
            await axios.get('/getSubCities')
            .then( response =>{
                this.subCities = response.data
            })
        },
        async addZone(){
            await axios.post('/addZones', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    title: 'Zone Added',
                    text: 'Zone added successfully'
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

