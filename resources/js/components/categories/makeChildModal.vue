<template>
<div class="row p-3">
    <div class="col-md-12">
        <h5 class="m-0">Make "{{category.cat_name}}"" a child category <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
    </div>
    <form action="#" @submit.prevent="addCategory">
        <div class="col-md-12 mt-3">
            <label for="">Parent Category</label>
            <select v-model="formData.parent_id" class="form-select">
                <option value=""></option>
                <option v-for="cat in mainCategories" :key="cat.id" :value="cat.id">
                    {{cat.cat_name}}<span v-if="cat.cat_type == `PARENT`"> (Main Category)</span>
                </option>
            </select>
        </div>
        <div class="col-md-12 mt-3">
            <button  type="submit" class="btn btn-primary form-control">Update</button>
        </div>            
    </form>
</div>
</template>
<script>
export default {
    props:['category'],
    data(){
        return{
            tree: {
            },
            formData:{
                cat_id:null,
                parent_id:null
            },
            mainCategories:{}
        }
    },
    mounted(){
        this.formData.cat_id = this.category.id
        this.getMainCategories();
    },
    methods:{
        async getMainCategories(){
            await axios.get('/getNodeCategories')
            .then( response =>{
                this.mainCategories = response.data
            })
        },
        async addCategory(){
            await axios.post('/makeChild', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Category Updated',
                    text: 'Parent category was updated successfully.'
                });
                this.$emit('close')
            })
            .catch( error =>{
                console.log(error.response)
            })
        },
    }
}
</script>