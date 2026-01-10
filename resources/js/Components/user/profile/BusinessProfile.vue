<script setup>
import axios from 'axios';
import Load from '@/Widgets/icons/Load.vue';
import {useBusinessStore} from "@/storage/business/business.js";
import { route } from 'ziggy-js';
import { onMounted, onUnmounted, reactive } from 'vue';
import BrandStatistic from '../../business/BrandStatistic.vue';
import Create from '../../business/Create.vue';
import BusinessProducts from '../../business/BusinessProducts.vue';


const load = reactive({main: true});
const productPagination = reactive({nextCursor: null, allProductsLoaded: false, isLoading: false});

const getBusiness = async () => {
    if(useBusinessStore().id != null){
        load.main = false;
        return;
    }
    try{
        const business = await axios.post(route('business.get'));
        useBusinessStore().name = business.data.name;
        setTimeout(() => {
            useBusinessStore().id = business.data.id;
            useBusinessStore().productsQuantity = business.data.products_quantity;
            useBusinessStore().sales = business.data.sales;
            useBusinessStore().rating = business.data.average_rating;
            useBusinessStore().reviews = business.data.quantity_reviews;
            load.main = false;
            getProducts();
        },1000);
    } catch (error){
        alert('error server');
    }
}

const getProducts = async () => {
    if(useBusinessStore().id == null || productPagination.allProductsLoaded || productPagination.isLoading) return;
    productPagination.isLoading = true;
    try{
        const params = productPagination.nextCursor ? { cursor: productPagination.nextCursor } : {};
        const res = await axios.post(route('business.products'), params);
        useBusinessStore().products.push(...res.data.data);
        productPagination.nextCursor = res.data.next_cursor;
        if(!res.data.has_more || !res.data.next_cursor) {
            productPagination.allProductsLoaded = true;
        }
    } catch(error){
        alert("error can't find products");
    } finally{
        productPagination.isLoading = false;
    }
}

const handleScroll = () => {
    const scrollTop = window.scrollY;
    const scrollHeight = document.documentElement.scrollHeight;
    const clientHeight = window.innerHeight;
    if(scrollTop + clientHeight >= scrollHeight){
        getProducts();
    }
}

onMounted(async () => {
    await getBusiness();
    getProducts();
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    useBusinessStore().products = [];
    window.removeEventListener('scroll', handleScroll);
});
</script>
<template>
    <div v-if="load.main" class="flex justify-center items-center h-[calc(100%-17px)]">
        <Load text="load"></Load>
    </div>
    <div v-if="!load.main">
        <div v-if="useBusinessStore().id != null">
            <BrandStatistic></BrandStatistic>
            <BusinessProducts></BusinessProducts>
        </div>
        <div class="mt-2.5 flex flex-col gap-y-2.5" v-else>
            <Create></Create>
        </div>
    </div>
</template>
