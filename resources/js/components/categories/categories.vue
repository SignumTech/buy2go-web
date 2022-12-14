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
                    <button @click="viewMainBin()" class="btn btn-success btn-sm rounded-1 float-end me-3"><span class="fa fa-recycle"></span> Recycle Bin</button>
                </div>
            </div>
            <div class="row mt-1 mx-0">
                <div class="col-md-12 px-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Items</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="mc,index in mainCategories" :key="mc.id">
                                <td>{{index +1}}</td>
                                <td class="align-middle">{{mc.cat_name}}</td>
                                <td class="align-middle">{{mc.items}}</td>
                                <td class="align-middle text-center">
                                    <span @click="deleteCategory(mc.id)" class="fa fa-trash-alt me-3"></span>
                                    <span @click="editMainModal(mc)" class="fa fa-edit me-3"></span>
                                    <span @click="makeChild(mc)" class="fa fa-child me-3"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <div class="row">
                <div class="col-md-3">
                    <label for="">Category Name</label>
                    <input v-model="queryData.cat_name" type="text" class="form-control rounded-1" placeholder="Category Name">
                    
                </div>
                <div class="col-md-3">
                    <label for="">Parent Category</label>
                    <select v-model="queryData.parent_id" class="form-select rounded-1">
                        <option :value="null">All statuses</option>
                        <option v-for="parent,index in parents" :key="index" :value="parent.id">{{parent.cat_name}}</option>
                    </select>
                </div>
                <div class="col-md-3 align-self-end">
                    <button @click="filterCategories()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                </div>
                <div class="col-md-3 align-self-end">
                    <form action="#" @submit.prevent="exportCategories">
                        <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                    </form>
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
                    <button @click="viewSubBin()" class="btn btn-success btn-sm rounded-1 float-end me-3"><span class="fa fa-recycle"></span> Recycle Bin</button>
                </div>
            </div>
            <div class="row mt-1 mx-0">
                <div class="col-md-12 px-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Image</th>
                                <th>Category Name</th>
                                <th>Items</th>
                                <th>Parent Category</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="sc,index in subCategories" :key="sc.id">
                                <td class="align-middle">{{index+1}}</td>
                                <td>
                                    <img :src="`/storage/settings/`+sc.cat_image" class="img img-fluid img-thumb cat_img rounded" alt="">
                                    <span @click="addPictureModal(sc.id,sc.cat_image)" class="fa fa-pencil-alt ml-1" style="cursor:pointer"></span>
                                </td>
                                <td class="align-middle">{{sc.cat_name}}</td>
                                <td class="align-middle">{{sc.items}}</td>
                                <td class="align-middle">{{sc.parent_name}}</td>
                                <td class="align-middle text-center">
                                    <span @click="deleteCategory(sc.id)" class="fa fa-trash-alt me-3"></span>
                                    <span @click="editSubModal(sc)" class="fa fa-edit me-3"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <nav v-if="(!filtered && paginationData.total >= paginationData.per_page)" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                            <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                        </a>
                    </li>
                </ul>
            </nav>
            <nav v-if="(filtered && paginationData.total >= paginationData.per_page)" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                <ul class="pagination justify-content-center">
                    <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                        <a class="page-link" @click="getFilteredPage(pd.url)" aria-label="Previous">
                            <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</template>
<script>
import mainCatModal from './mainCatModal.vue'
import subCatModal from './subCatModal.vue'
import addPictureModal from './addPictureModal'
import makeChildModalVue from './makeChildModal.vue'
import mainCatTrashVue from './mainCatTrash.vue'
import SubCatTrash from './subCatTrash.vue'
export default {
    data(){
        return{
            filtered:false,
            mainCategories:{},
            subCategories:{},
            paginationData:{},
            parents:{},
            categoryData:{},
            queryData:{
                cat_name:null,
                parent_id:null
            }
        }
    },
    mounted(){
        this.getMainCategories()
        this.getSubCategories()
        this.getNodeCategories()
    },
    methods:{
        viewMainBin(){
            this.$modal.show(
                mainCatTrashVue,
                {},
                {height:"auto", width:"80%"},
                {"closed":this.getMainCategories}
            )
        },
        viewSubBin(){
            this.$modal.show(
                SubCatTrash,
                {},
                {height:"auto", width:"80%"},
                {"closed":this.getSubCategories}
            )
        },
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.subCategories = response.data.data
            })
        },
        async getFilteredPage(pageUrl){
            await axios.post(pageUrl, this.queryData)
            .then( response => {
                this.paginationData = response.data
                this.subCategories = response.data.data
            })
        },
        async deleteCategory(id){
            var check = confirm("Are you sure you want to delete this category?")
            if(check){
                await axios.delete('/categories/'+id)
                .then( response =>{
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Category Deleted',
                        text: 'Category Deleted Successfully.'
                    });
                    this.getMainCategories()
                    this.getSubCategories()
                })
            }
            
        },
        async exportCategories(){
            await axios.post('/exportCategories', this.queryData, {responseType: 'blob'})
            .then( response =>{
                const href = URL.createObjectURL(response.data);

                // create "a" HTML element with href to file & click
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'categories'+Date.now()+'.xlsx'); //or any other extension
                document.body.appendChild(link);
                link.click();

                // clean up "a" element & remove ObjectURL
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        },
        async getNodeCategories(){
            await axios.get('/getAllNodeCategories')
            .then( response =>{
                this.parents = response.data
            })
        },
        async filterCategories(){
            this.filtered = true
            await axios.post('/filterCategories', this.queryData)
            .then( response=>{
                this.paginationData = response.data
                this.subCategories = response.data.data
            })
        },
        makeChild(category){
            this.$modal.show(
                makeChildModalVue,
                {category:category},
                {width: '500px', height: 'auto'},
                { "closed" : this.updateData}
            )
        },
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
            this.filtered = false
            await axios.get('/getSubCategories')
            .then( response =>{
                this.paginationData = response.data
                this.subCategories = response.data.data
                
            })
            .catch( error =>{
                
            })
        }
    }
}
</script>