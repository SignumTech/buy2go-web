<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Add Product</strong></h5>
    </div>
    <div class="col-md-12">
        <div class="bg-white rounded-1 shadow-sm px-3 py-5">
            <div class="row">
                <div class="col-md-6 align-self-center">
                    <div v-if="!picUploaded && !picLoading" style="height: 100%">
                        <h1 class="text-center pt-2"><span class="fa fa-camera"></span></h1>
                        <h5 class="text-center pt-1"><strong>800 X 800</strong></h5>
                        <h6 class="text-center">Please choose image according to the aspect ratio</h6>
                        <label class="text-center d-block m-auto fs-3 pt-2 fa fa-plus mt-2" style="cursor:pointer">
                            <input type="file" class="form-control" id="photo" name="photo" @change="uploadPic($event,index)" style="display: none">
                        </label>
                    </div>
                    <h6 v-if="picError" class="text-center text-danger">You need to upload a product picture first!</h6>
                    <img v-if="picUploaded" :src="`/storage/products/`+formData.p_image" class="img img-fluid img-thumb d-block m-auto" alt="">
                    <div v-if="picLoading" class="d-flex justify-content-center mt-5 mb-5">
                        <pulse-loader :color="`#BF7F25`" :size="`15px`"></pulse-loader> 
                    </div>
                </div>
                <div class="col-md-6 border-start">
                    <form action="#" @submit.prevent="publishProduct()">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Product Name</label>
                                <input v-model="formData.p_name" required type="text" placeholder="Product Name" class="form-control">
                                <h6 v-for="er in validationErrors.p_name" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>
                            <div class="col-md-6">
                                <label for="">Price</label>
                                <input v-model="formData.price" required type="number" placeholder="Price" class="form-control">
                                <h6 v-for="er in validationErrors.price" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">Category</label>
                                <treeselect required v-model="formData.cat_id" :disable-branch-nodes="true" :multiple="false" :options="categoryList" />
                                <h6 v-for="er in validationErrors.cat_id" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">Product Commission (%)</label>
                                <input v-model="formData.p_commission" required type="number" placeholder="Price" class="form-control">
                                <h6 v-for="er in validationErrors.p_commission" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>
                            <div v-for="warehouse,index in  formData.warehouse" :key="index" class="col-md-4 mt-3">
                                <label for="">{{formData.quantities[index].warehouse_name}}:</label>
                                <input required v-model="formData.quantities[index].quantity" type="number" placeholder="Quantity" class="form-control">
                                <h6 v-for="er in validationErrors.quantities" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="">Warehouses</label>
                                <treeselect required :beforeClearAll="removeAll"  @deselect="removeWarehouse" @select="addWarehouse" v-model="formData.warehouse" :disable-branch-nodes="true" :multiple="true" :options="wareHouseList" />
                                <h6 v-for="er in validationErrors.warehouse" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label for="">Description</label>
                                <textarea v-model="formData.description" required class="form-control" id="" cols="30" rows="5"></textarea>
                                <h6 v-for="er in validationErrors.description" :key="er.id" class="text-danger m-0">{{er}}</h6>
                            </div>

                            <div class="col-md-6 mt-3">
                                <button @click="saveDraft()" class="btn btn-warning form-control"><span class="fa fa-save"></span> SAVE AS DRAFT</button>
                            </div>
                            <div class="col-md-6 mt-3">
                                <button type="submit" class="btn btn-primary form-control"><span class="fa fa-check-circle"></span> PUBLISH</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>    
</template>
<script>
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
import axios from 'axios'
import { formatWithOptions } from 'util'
export default {
    components: { 
        Treeselect,
        PulseLoader 
    },
    beforeMount() {
        window.addEventListener("beforeunload", this.preventNav)
        this.$once("hook:beforeDestroy", () => {
        window.removeEventListener("beforeunload", this.preventNav);
        })
    },
    beforeRouteLeave(to, from, next) {
        if (this.isEditing) {
            if (!window.confirm("Leave without saving?")) {
                return;
            }
            else{
                this.deletePic()
            }
        }
        next();
    },
    data(){
        return{
            isEditing: false,
            validationErrors:{},
            picUploaded:false,
            picError:false,
            picLoading:false,
            formData:{
                p_name:null,
                price:null,
                cat_id:null,
                p_commission:0,
                warehouse:null,
                quantities:[],
                p_image:""
            },
            categoryList: [],
            wareHouseList: [],
        }
    },
    mounted(){
        this.getCategories(),
        this.getWarehouses()
    },
    methods:{
        async saveDraft(){
            await axios.post('/saveDraft', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Draft Successful',
                    text: 'You have successfuly saved a product to Drafts!'
                });
                window.location.replace('/productList')
            })
            .catch( error=>{
                if(error.response.status == 422){
                    this.validationErrors = error.response.data.errors
                }
            }) 
        },
        async deletePic(){
            await axios.post('/deleteProductPic', {fileName:this.formData.p_image})
            .then( response =>{

            })
        },
        preventNav(event) {
            if (!this.isEditing) return
            event.preventDefault()
            event.returnValue = ""
        },
        removeAll(){
            this.formData.quantities = []
            return true
        },
        addWarehouse(node,instanceId){
            this.formData.quantities.push({
                warehouse_id:node.id,
                warehouse_name:node.label,
                quantity:""
            })
        },
        removeWarehouse(node,instanceId){
            console.log(node)
            this.formData.quantities = this.formData.quantities.filter(data=>data.warehouse_id != node.id)
        },
        async getCategories(){
            await axios.get('/chooseSubCategories')
            .then( response =>{
                this.categoryList = response.data
            })
        },
        async getWarehouses(){
            await axios.get('/warehouses')
            .then( response =>{
                response.data.forEach((warehouse) =>{
                    this.wareHouseList.push({
                        id:warehouse.id,
                        label:warehouse.w_name
                    })
                });
            })
        },
        async publishProduct(){
            if(this.picUploaded){
                await axios.post('/products', this.formData)
                .then( response =>{
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Publishing Successful',
                        text: 'You have successfuly published your product!'
                    });
                    this.isEditing = false
                    window.location.replace('/productList')
                })
                .catch( error=>{
                    if(error.response.status == 422){
                        this.isEditing = true
                        this.validationErrors = error.response.data.errors
                    }
                })                
            }
            else{
                this.picError = true
            }
        },
        async uploadPic($event, index){
            this.picLoading = true
            const data = new FormData();
            this.photo = $event.target.files[0];
            data.append('photo', this.photo);
            this.img_loading = true;
            await axios.post('/uploadProductPic', data)
            .then( response => {
                this.formData.p_image = response.data.fileName
                this.picUploaded = true
                this.picLoading = false
                this.picError = false
                this.isEditing = true
            })
            .catch( error => {
                if (error.response.status == 422){
                    this.validationErrors = error.response.data.errors.photo;
                    this.isEditing = false
                }
                this.picLoading = false;
                this.picUploaded = false
            })
        },
    }
}
</script>