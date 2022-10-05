<template>
    <div class="row p-3">
            <div class="col-md-12">
                <h5 v-if="type=='add'" class="m-0">Add Main Category <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
                <h5 v-if="type=='edit'" class="m-0">Edit Main Category <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
            </div>
            <form v-if="type=='edit'" action="#" @submit.prevent="editCategory">
                <div class="col-md-12 mt-3">
                    <label for="">Category Name</label>
                    <input v-model="formData.cat_name" type="text" class="form-control" required placeholder="Category Name">
                </div>
                <div class="col-md-12 mt-3">
                    <button v-if="type=='edit'" type="submit" class="btn btn-primary form-control"><span class="fa fa-edit"></span> Update</button>
                </div>
            </form>
            <form v-if="type=='add'" action="#" @submit.prevent="addCategory">
                <div class="col-md-12 mt-3">
                    <label for="">Category Name</label>
                    <input v-model="formData.cat_name" type="text" class="form-control" required placeholder="Category Name">
                </div>
                <div class="col-md-12 mt-3">
                    <button v-if="type=='add'"  type="submit" class="btn btn-primary form-control"><span class="fa fa-plus"></span> Add</button>
                </div>            
            </form>
    </div>
</template>
<script>
export default {
    props:["type","cat"],
    data(){
        return{
            formData:{
                cat_name:null,
                cat_type: 'PARENT',
                parent_id: 0
            }
        }
    },
    mounted(){
        if(this.type == 'edit'){
            this.formData = this.cat
        }
    },
    methods:{
        async addCategory(){
            await axios.post('/categories', this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Main Category',
                    text: 'The main category was added successfully'
                });
                this.$emit('close')
            })
            .catch( error =>{
                console.log(error.response)
            })
        },
        async editCategory(){
            await axios.put('/categories/'+this.formData.id, this.formData)
            .then( response =>{
                this.$notify({
                    group: 'foo',
                    type: 'success',
                    title: 'Main Category',
                    text: 'The main category was updated successfully'
                });
                this.$emit('close')
            })
            .catch( error =>{
                console.log(error.response)
            })
        }
    }
}
</script>