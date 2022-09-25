<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Categories</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <div class="row m-0">
                <div class="col-md-6 px-0">
                    <h5 class="m-0"><strong>Main Categories</strong></h5>
                </div>
                <div class="col-md-6 px-0">
                    <button @click="addMainModal()" class="btn btn-primary btn-sm float-end"><span class="fa fa-plus"></span> Add Main Category</button>
                </div>
            </div>
            <div class="row mt-1 mx-0">
                <div class="col-md-12 px-0">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Items</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="mc in mainCategories" :key="mc.id">
                                <td class="align-middle">{{mc.cat_name}}</td>
                                <td class="align-middle">{{mc.items}}</td>
                                <td class="align-middle">
                                    <button class="btn btn-danger btn-sm float-end ms-3"><span class="fa fa-trash-alt"></span></button>
                                    <button @click="editMainModal(mc)" class="btn btn-success btn-sm float-end "><span class="fa fa-edit"></span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <div class="row m-0">
                <div class="col-md-6 px-0">
                    <h5 class="m-0"><strong>Sub Categories</strong></h5>
                </div>
                <div class="col-md-6 px-0">
                    <button @click="addSubModal()" class="btn btn-primary btn-sm float-end"><span class="fa fa-plus"></span> Add Sub Category</button>
                </div>
            </div>
            <div class="row mt-1 mx-0">
                <div class="col-md-12 px-0">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Category Name</th>
                                <th>Items</th>
                                <th>Parent Category</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sc in subCategories" :key="sc.id">
                                <td>
                                    <img :src="`/storage/settings/`+sc.cat_image" class="img img-fluid img-thumb cat_img rounded" alt="">
                                    <span @click="addPictureModal(sc.id,sc.cat_image)" class="fa fa-pencil-alt ml-1" style="cursor:pointer"></span>
                                </td>
                                <td class="align-middle">{{sc.cat_name}}</td>
                                <td class="align-middle">{{sc.items}}</td>
                                <td class="align-middle">{{sc.parent_name}}</td>
                                <td class="align-middle">
                                    
                                    <button class="btn btn-danger btn-sm float-end ms-3"><span class="fa fa-trash-alt"></span></button>
                                    <button @click="editSubModal(sc)" class="btn btn-success btn-sm float-end "><span class="fa fa-edit"></span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import mainCatModal from './mainCatModal.vue'
import subCatModal from './subCatModal.vue'
import addPictureModal from './addPictureModal.vue'
export default {
    data(){
        return{
            mainCategories:{},
            subCategories:{},
        }
    },
    mounted(){
        this.getMainCategories()
        this.getSubCategories()
    },
    methods:{
        updateData(){
            this.getMainCategories()
            this.getSubCategories()
        },
        addPictureModal(id, cat_img){
            this.$modal.show(
                addPictureModal,
                { "type": "add", "id":id, "cat_img":cat_img},
                {width: '300px', height: 'auto'},
                { "closed" : this.updateData}
            )
        },
        addSubModal(){
            this.$modal.show(
                subCatModal,
                { "type": "add"},
                {width: '500px', height: 'auto'},
                { "closed" : this.updateData}
            )
        },
        editSubModal(cat){
            this.$modal.show(
                subCatModal,
                { "type": "edit", "cat": cat},
                {width: '500px', height: 'auto'},
                { "closed" : this.updateData}
            )
        },
        addMainModal(){
            this.$modal.show(
                mainCatModal,
                { "type": "add"},
                {width: '500px', height: 'auto'},
                { "closed" : this.updateData}
            )
        },
        editMainModal(cat){
            this.$modal.show(
                mainCatModal,
                { "type": "edit", "cat": cat},
                {width: '500px', height: 'auto'},
                { "closed" : this.updateData}
            )
        },
        async getMainCategories(){
            await axios.get('/getMainCategories')
            .then( response =>{
                this.mainCategories = response.data
            })
            .catch( error =>{

            })
        },
        async getSubCategories(){
            await axios.get('/getSubCategories')
            .then( response =>{
                this.subCategories = response.data
            })
            .catch( error =>{
                
            })
        }
    }
}
</script>