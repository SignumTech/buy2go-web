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
                    <label for="">Price Range</label>
                    <vue-slider
                    v-model="queryData.range"
                    :min="parseFloat(min)"
                    :max="parseFloat(max)"
                    ></vue-slider>
                    <h6 class="m-0">{{queryData.range[0]}} ETB <span class="float-end">{{queryData.range[1]}} ETB</span></h6>
                </div>
                <div class="col-md-2">
                    <label for="">Featured</label>
                    <select v-model="queryData.featured" class="form-select rounded-1">
                        <option :value="null">All products</option>
                        <option value="FEATURED">Featured</option>
                        <option value="NOT_FEATURED">Not Featured</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="">Category</label>
                    <treeselect required v-model="queryData.cat_id" :disable-branch-nodes="false" :multiple="false" :options="categoryList" />
                </div>
                <div class="col-md-2 align-self-end">
                    <div class="row">
                        <div class="col-md-12">
                            <button @click="filterProducts()" class="btn btn-success form-control rounded-1"><span class="fa fa-filter"></span> Filter</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <form action="#" @submit.prevent="exportProducts">
                                <button type="submit" class="btn btn-primary form-control rounded-1"><span class="fa fa-file-export"></span> Export</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
    <LvProgressBar v-if="loading" mode="indeterminate" color="#011b48" />
        <div class="bg-white rounded-1 p-3 shadow-sm">
            <router-link v-if="permission.addProduct" to="/addProduct" class="btn btn-primary btn-sm float-end shadow text-white"><span class="fa fa-plus"></span> Add Product</router-link>
            <button v-if="permission.deleteProduct" @click="viewBin()" class="btn btn-success btn-sm rounded-1 float-end me-3"><span class="fa fa-recycle"></span> Recycle Bin</button>
            <table class="table table-fixed px-2 table-sm mt-2">
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
                        <td class="align-middle" style="width: 20%">{{product.p_name}}</td>
                        <td class="align-middle">{{product.price}} Birr</td>
                        
                        <td class="align-middle">{{product.stock}}</td>
                        <td class="align-middle">{{product.p_status}}</td>
                        <td class="align-middle">
                            <div class="form-check form-switch">
                                <input @change="toggleFeature(product.id)" class="form-check-input" type="checkbox" :checked="product.featured==`FEATURED`">
                                <label class="form-check-label" for="flexSwitchCheckChecked"> {{product.featured}}</label>
                            </div>
                        </td>
                        <td class="align-middle">{{product.created_at | moment("MMM Do YYYY h:m:s a")}}</td>
                        <td class="align-middle">
                            <a v-if="permission.deleteProduct" type="button"><span @click="deleteProduct(product.id)" class="fa fa-trash-alt float-end"></span></a>
                            <router-link v-if="permission.updateProduct" :to="`/editProduct/`+product.id" class="float-end me-2"><span class="fa fa-edit"></span></router-link>
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
</div>
</template>
<script>
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/antd.css'
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import ProductsTrash from './productsTrash.vue'
import LvProgressBar from 'lightvue/progress-bar';
export default {
    components: {
        VueSlider,
        Treeselect,
        LvProgressBar: LvProgressBar
    },
    data(){
        return{
            loading:true,
            permission:{},
            products:{},
            paginationData:{},
            min:0,
            max:10000000,
            categoryList:[],
            queryData:{
                p_name:null,
                featured:null,
                status:null,
                range:[],
                cat_id:null
            }

        }
    },
    mounted(){
        this.$store.dispatch('auth/permissions')
        .then( () =>{
            this.permission = this.$store.state.auth.permissions
        })
        this.getProducts()
        this.getPriceRange()
        this.getCategories(),
        feather.replace();
    },
    methods:{
        viewBin(){
            this.$modal.show(
                ProductsTrash,
                {},
                {height:"auto", width:"80%"},
                {"closed":this.getProducts}
            )
        },
        async getCategories(){
            await axios.get('/chooseSubCategories')
            .then( response =>{
                this.categoryList = response.data
            })
        },
        async deleteProduct(id){
            var check = confirm("Are you sure you want to delete this product?")
            if(check){
                await axios.delete('/products/'+id)
                .then( response =>{
                    this.$notify({
                        group: 'foo',
                        type: 'success',
                        title: 'Product Deleted',
                        text: 'Product Deleted Successfully.'
                    });
                    this.getProducts()
                })
            }
            
        },
        async exportProducts(){
            await axios.post('/exportProducts', this.queryData, {responseType: 'blob'})
            .then( response =>{
                const href = URL.createObjectURL(response.data);

                // create "a" HTML element with href to file & click
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'products'+Date.now()+'.xlsx'); //or any other extension
                document.body.appendChild(link);
                link.click();

                // clean up "a" element & remove ObjectURL
                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        },
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
                
                this.queryData.range.push(response.data.max)
                this.queryData.range.push(response.data.min)
                this.min = response.data.min
                this.max = response.data.max
                this.queryData.priceRange = response.data.max
            })
        },
        async getPage(pageUrl){
            this.loading = true
            await axios.post(pageUrl, this.queryData)
            .then( response => {
                this.paginationData = response.data
                this.products = response.data.data
                this.loading = false
            })
        },
        async toggleFeature(id){
            await axios.put('/toggleFeature/'+id)
            .then( response =>{
                this.getProducts()
            })
        },  
        async getProducts(){
            this.loading = true
            await axios.get('/getProductsList')
            .then( response =>{
                this.paginationData = response.data
                this.products = response.data.data
                this.loading = false
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