<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import { Head } from '@inertiajs/vue3';
import NoAuth from '@/Components/user/helpers/NoAuth.vue';
import { onMounted, reactive, ref } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';


const test = ref(false);
const orders = reactive({data: []});
const getOrders = async () => {
    // if(useUserStore().id == null) return;
    try{
        const res = await axios.post(route('user.get.orders'));
        orders.data = res.data;
        console.log(orders.data);
    } catch(error){
        alert('error server');
    }
}

onMounted(() =>{
    getOrders();
})

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('orders') }}</title>
        <meta name="description" :content="useTranslateStore().t('ordersDescription')">
    </Head>
    <MainLayout>
        <div v-if="useUserStore().id != null">
            <div class="uppercase text-xl text-center">{{ useTranslateStore().t('orders') }}</div>
            <div class="mt-5 transition-all duration-300 bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] rounded-[10px]">
                <div class="px-2.5 py-3.75 flex justify-between items-center">
                    <div class="text-xl max-w-303.75 wrap-break-word">{{ useTranslateStore().t('order') }} - <span class="text-violet-800">100</span></div>
                    <img @click.prevent="test = !test" :class="{ 'rotate-180': test }" class="cursor-pointer block transition duration-150" src="/public/img/arrow.svg" alt="open"/>
                </div>
                <div :class="['px-2.5 overflow-hidden', test ? 'grid grid-rows-[1fr] transition-all duration-300' : 'grid grid-rows-[0fr] transition-all duration-300']">
                    <div class="overflow-hidden">
                        <div>dsadsa</div>
                        <div>dsadsa</div>
                        <div>dsadsa</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <NoAuth></NoAuth>
        </div>
    </MainLayout>
</template>
