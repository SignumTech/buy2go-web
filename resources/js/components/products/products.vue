<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Products</strong></h5>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-2 shadow-sm">
            <div class="row">
                <div class="col-md-2">
                    <label for="">Product Name</label>
                    <input v-model="queryData.p_name" type="text" class="form-control rounded-1" placeholder="Product Name">
                    
                </div>
                <div class="col-md-2">
                    <label for="">Status</label>
                    <select v-model="queryData.status" class="form-select rounded-1">
                        <option :value="null">All statuses</option>
                        <option value="PUBLISHED">Published</option>
                        <option value="DRAFT">Drafts</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-center">
                    <input v-model="queryData.priceRange" :min="range.min" :max="range.max" type="range" class="form-range rounded-1">
                    <h6 class="m-0">{{range.min}} ETB <span class="float-end">{{range.max}} ETB</span></h6>
                </div>
                <div class="col-md-2">
                    <label for="">Featured</label>
                    <select v-model="queryData.featured" class="form-select rounded-1">
                        <option :value="null">All products</option>
                        <option value="FEATURED">Featured</option>
                        <option value="NOT_FEATURED">Not Featured</option>
                    </select>
                </div>
                <div class="col-md-2 align-self-end">
                    <button @click="filterProducts()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                </div>
                <div class="col-md-2 align-self-end">
                    <button class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <router-link to="/addProduct" class="btn btn-primary btn-sm float-end shadow text-white"><span class="fa fa-plus"></span> Add Product</router-link>
            <table class="table px-2 table-sm mt-2">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Created Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product,index in products" :key="index">
                        <td class="align-middle">
                            <img :src="`/storage/products/`+product.p_image" class="img img-fluid img-thumb cat_img rounded-1" alt="">
                        </td>
                        <td class="align-middle">{{product.p_name}}</td>
                        <td class="align-middle">{{product.price}} Birr</td>
                        
                        <td class="align-middle">{{product.stock}}</td>
                        <td class="align-middle">{{product.p_status}}</td>
                        <td class="align-middle">
                            <div class="form-check form-switch">
                                <input @change="toggleFeature(product.id)" class="form-check-input" type="checkbox" :checked="product.featured==`FEATURED`">
                                <label class="form-check-label" for="flexSwitchCheckChecked"> {{product.featured}}</label>
                            </div>
                        </td>
                        <td class="align-middle">{{product.created_at | moment("ddd, MMM Do YYYY")}}</td>
                        <td class="align-middle">
                            <router-link to="#" class="float-end "><span class="fa fa-trash-alt"></span></router-link>
                            <router-link :to="`/editProduct/`+product.id" class="float-end me-3"><span class="fa fa-edit"></span></router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
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
</div>
</template>
<script>
export default {
    data(){
        return{
            products:{},
            paginationData:{},
            range:{},
            queryData:{
                p_name:null,
                featured:null,
                status:null,
                priceRange:null
            }

        }
    },
    mounted(){
        this.getProducts()
        this.getPriceRange()
        feather.replace();
    },
    methods:{
        async filterProducts(){
            await axios.post('/getProductsList', this.queryData)
            .then( response => {
                this.paginationData = response.data
                this.products = response.data.data
            })
        },
        async getPriceRange(){
            await axios.get('/getPriceRange')
            .then( response =>{
                this.range = response.data
            })
        },
        async getPage(pageUrl){
            await axios.get(pageUrl)
            .then( response => {
                this.paginationData = response.data
                this.products = response.data.data
            })
        },
        async toggleFeature(id){
            await axios.put('/toggleFeature/'+id)
            .then( response =>{
                this.getProducts()
            })
        },  
        async getProducts(){
            await axios.get('/getProductsList')
            .then( response =>{
                this.paginationData = response.data
                this.products = response.data.data
            })
        },
        editProduct(product){
            this.$router.push({name:'EditProduct', params:{
                type: 'edit',
                item: product
            }})
        }
    }
}
</script>