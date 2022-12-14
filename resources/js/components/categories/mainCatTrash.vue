<template>
    <div class="row p-4">
        <div class="col-md-12">
            <h5><strong><span class="fa fa-trash-alt"></span> Recycle Bin</strong> <span @click="$emit('close')" class="fa fa-times float-end"></span></h5>
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
    }
}
</script>