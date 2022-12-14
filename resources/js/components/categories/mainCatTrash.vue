<template>
    <div class="row p-4">
        <div class="col-md-12">
            <h5><strong><span class="fa fa-trash-alt"></span> Recycle Bin</strong> <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
        </div>
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
                            <button @click="restoreCategory(mc.id)" class="btn btn-sm btn-success rounded-1"><span class="fa fa-trash-restore-alt"></span> Restore</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
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
    },
    methods:{
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.products = response.data.data
            })
        },
        async getMainCategories(){
            await axios.get('/getDeletedMainCategories')
            .then( response =>{
                this.mainCategories = response.data
            })
            .catch( error =>{

            })
        },
        async restoreCategory(id){
            var check = confirm("Are you sure you want to restore this category?")
            if(check){
                await axios.put('/restoreCategory/'+id)
                .then( response =>{
                    this.getMainCategories()
                                    
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Category Restored',
                        text: 'Category Restored Successfully.'
                    });
                
                })
            }
        }
    }
}
</script>