<script setup>
import axios from 'axios';
import Load from '@/Widgets/icons/Load.vue';
import {useBusinessStore} from "@/storage/business/business.js";
import { route } from 'ziggy-js';
import { onMounted, reactive } from 'vue';
import BrandStatistic from '../../business/BrandStatistic.vue';
import Create from '../../business/Create.vue';
import BusinessProducts from '../../business/BusinessProducts.vue'


const load = reactive({main: true});

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
            getProducts();
        },1000);
    } catch (error){
        console.log(error);
    }
}

const getProducts = async () => {
    if(useBusinessStore().id == null) return;
    try{
        const res = await axios.post(route('business.products'));
        useBusinessStore().products = res.data;
        load.main = false;
    } catch(error){
        alert("error can't find products");
    }
}

onMounted(async () => {
    await getBusiness();
    getProducts();
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
