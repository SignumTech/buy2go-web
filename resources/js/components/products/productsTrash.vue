<template>
    <div class="row p-4">
        <div class="col-md-12 mt-3">
                <table class="table table-fixed px-2 table-sm mt-2">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Created Date</th>
                            <th>Deleted Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product,index in products" :key="index">
                            <td class="align-middle">
                                <img :src="`/storage/products/`+product.p_image" class="img img-fluid img-thumb cat_img rounded-1" alt="">
                            </td>
                            <td class="align-middle" style="width: 20%">{{product.p_name}}</td>
                            <td class="align-middle">{{product.price}} Birr</td>
                            <td class="align-middle">{{product.created_at | moment("MMM Do YYYY h:m:s a")}}</td>
                            <td class="align-middle">{{product.deleted_at | moment("MMM Do YYYY h:m:s a")}}</td>
                            <td class="align-middle">
                                <a type="button"><span @click="restoreProduct(product.id)" class="fa fa-trash-restore-alt"></span></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <nav v-if="paginationData.total > paginationData.per_page" aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
                    <ul class="pagination justify-content-center">
                        <li v-for="pd,index in paginationData.links" :key="index" :class="(pd.active)?`page-item active text-white`:`page-item`">
                            <a class="page-link" @click="getPage(pd.url)" aria-label="Previous">
                                <span :class="(pd.active)?`text-white`:``" aria-hidden="true" v-html="pd.label"></span>
                            </a>
                        </li>
                    </ul>
                </nav>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            products:{},
            paginationData:{},
        }
    },
    mounted(){
        this.getProducts()
        feather.replace();
    },
    methods:{
        async getProducts(){
            await axios.get('/getDeletedProducts')
            .then( response =>{
                this.paginationData = response.data
                this.products = response.data.data
            })
        },
    }
    
}
</script>