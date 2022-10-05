<template>
    <div class="row p-3">
        <div class="col-md-12">
            <h5 class="m-0">Add Category Picture <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
        </div>
        <div class="col-md-12 mt-4 text-center">
            <div class="rounded-3 shadow-smz overflow-hidden d-block m-auto" style="width:150px; height:150px">
                <img :src="`/storage/settings/`+cat_img" class="img img-fluid " alt="">
            </div>
            <label class="m-0 float-end rounded-circle hov-main p-2 fs-4 shadow-sm fa fa-pencil-alt" style="cursor:pointer">
                <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event)" style="display: none">
            </label>
        </div>
    </div>   
    </template>
    <script>
    export default {
        props:["type", "id", "cat_img"],
        data(){
            return{
                img_loading:false
            }
        },
        methods:{
            async uploadPic($event){
                const data = new FormData();
                this.photo = $event.target.files[0];
                data.append('photo', this.photo);
                data.append('cat_id', this.id)
                this.img_loading = true;
                await axios.post('/uploadSubPic', data)
                .then( response => {
                    this.$emit('close')
                })
                .catch( error => {
                    if (error.response.status == 422){
                        this.validationErrors = error.response.data.errors.photo;
                    }
                    this.img_loading = false;
                })
            },
        }
    }
    </script>