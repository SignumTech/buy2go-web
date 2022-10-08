<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Add Zones</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="p-3 bg-white shadow-sm rounded-1">
            <div class="row m-0">
                
                    <div class="col-md-3">
                        <label for="">City</label>
                        <input type="text" class="form-control" placeholder="City">
                        <label for="">Sub city</label>
                        <input type="text" class="form-control" placeholder="Sub city">
                    </div>
                    <div class="col-md-9">
                        <gmap-map :center="{lat: 8.9806, lng: 38.7578}" :zoom="12" style="width: 100%; height: 500px">
                            <gmap-polygon :paths="paths" :editable="true" @paths_changed="updateEdited($event)">
                            </gmap-polygon>
                        </gmap-map>
                    </div>                

            </div>            
        </div>

        
    </div>
</div> 
</template>
<script>
export default {
    data(){
        return{
            edited: null,
            paths: [
                [ {lat: 9.01171937808091, lng: 38.722167031625354}, {lat:8.959758487959377, lng: 38.76476823033494}, {lat: 9.011767475867545, lng:38.76162983979211}],
            ]
        }
    },
    methods:{
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