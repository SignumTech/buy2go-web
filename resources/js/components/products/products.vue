<template>
<div class="row mt-4">
    <div class="col-md-12">
        <h5><strong>Products</strong></h5>
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
                        <th>Promotion</th>
                        <th>Created Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td class="align-middle">
                            <img :src="`/storage/products/`+JSON.parse(product.p_image).main" class="img img-fluid img-thumb cat_img rounded-1" alt="">
                        </td>
                        <td class="align-middle">{{product.p_name}}</td>
                        <td class="align-middle">{{product.price}} Birr</td>
                        
                        <td class="align-middle">{{product.stock}}</td>
                        <td class="align-middle">{{product.p_status}}</td>
                        <td class="align-middle">{{product.promotion_status}}</td>
                        <td class="align-middle">{{product.created_at | moment("ddd, MMM Do YYYY")}}</td>
                        <td class="align-middle">
                            <router-link to="#" class="float-end "><span class="fa fa-trash-alt"></span></router-link>
                            <router-link :to="`/admin/editProduct/`+product.id" class="float-end me-3"><span class="fa fa-edit"></span></router-link>
                            <a @click="makeFlash(product)" class="float-end " href="#"><span class="fa fa-bolt me-3"></span></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            products:{}
        }
    },
    mounted(){
        this.getProducts()
        feather.replace();
    },
    methods:{
        makeFlash(product){
            this.$modal.show(
                makeFlash,
                {"product":product},
                { height: "100%", width: "450px", shiftX: 1},  
                {"closed":this.getProducts}
            );
        },
        async getProducts(){
            await axios.get('/getProductsList')
            .then( response =>{
                this.products = response.data
                console.log(response.data)
                console.log(JSON.parse(response.data[0].p_image).main)
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