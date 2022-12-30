<template>
<nav aria-label="Page d-flex m-auto navigation example" style="cursor:pointer">
    <ul class="pagination justify-content-center">
        <li v-if="currentPage > 1" @click="prev()" class="page-item"><a aria-label="Previous" class="page-link"><span aria-hidden="true" class="">« Previous</span></a></li>
        <li v-for="pd,index in paginationData" :key="index" :class="(index+1 == currentPage)?`page-item active text-white`:`page-item`">
            <a class="page-link" @click="getPage(index+1)" aria-label="Previous">
                <span :class="(index+1 == currentPage)?`text-white`:``" aria-hidden="true" v-html="index+1"></span>
            </a>
        </li>
        <li v-if="currentPage < paginationData.length" @click="next()" class="page-item"><a aria-label="Previous" class="page-link"><span aria-hidden="true" class="">Next »</span></a></li>
    </ul>
</nav>    
</template>
<script>
export default {
    props:['paginationData', 'currentPage', 'products'],
    data(){
        return{
            paginationData:this.paginationData,
            currentPage:this.currentPage,
            products:this.products
        }
    },
    mounted(){
        
    },
    methods:{
        prev(){
            this.loading = true
            this.currentPage--
            this.loading = false
        },
        next(){
            this.loading = true
            this.currentPage++
            this.loading = false
        },
        getPage(index){
            this.loading = true
            this.currentPage = index
            this.products = this.paginationData[index-1]
            this.loading = false
        },
    }
}
</script>