<script setup>
import { onBeforeMount, ref } from 'vue';
import Header from './Header.vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import {useUserStore} from "@/storage/user/user.js";
import {useUserBalanceStore} from "@/storage/balance/userBalance.js";
import {useFindProductStore} from "@/storage/product/find.js";
import {useCatalogStore} from "@/storage/catalog/catalog.js";
import Load from '@/Widgets/icons/Load.vue';
import Basket from '@/Components/basket/Basket.vue';
import Catalog from '@/Components/catalog/Catalog.vue';
import FindProduct from '@/Components/product/find/FindProduct.vue';
import Nav from './Nav.vue';

const load = ref(true);

onBeforeMount(async () => {
    if(useUserStore().id == null && !useUserStore().isLoginAttempt){
        try{
            const user = await axios.post(route('user.get'));
            if(user.data.error_auth){
                useUserStore().isLoginAttempt = true;
                useUserStore().resetUser();
            }
            if(user.data.user){
                useUserBalanceStore().balance = user.data.balance;
                useUserStore().setUser(user.data.user);
            }
            setTimeout(() => {
                load.value = false;
            }, 1000);
        } catch  {
            //
        }
    } else {
        load.value = false;
    }
})
</script>
<template>
    <div v-if="load" class="flex flex-col h-full justify-center items-center">
        <Load text="load"></Load>
    </div>
    <div v-else class="container mx-auto flex flex-col gap-y-10 h-full relative animate-opacity-in">
        <div class="fixed container z-50 bg-dark">
            <Header></Header>
        </div>
        <main :class="{'px-2.5': !useCatalogStore().show}" class="grow shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] py-5 rounded-[20px] mt-32.5">
            <div v-if="!useCatalogStore().show && !useFindProductStore().show" class="max-lg:mb-15">
                <slot></slot>
            </div>
            <div v-show="useCatalogStore().show">
                <Catalog></Catalog>
            </div>
            <div v-if="useFindProductStore().show && !useCatalogStore().show">
                <FindProduct></FindProduct>
            </div>
        </main>
        <div class="fixed left-0 right-0 bottom-0 z-40 max-lg:block hidden">
            <div class="container mx-auto">
                <div class="bg-dark rounded-t-[20px] shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2.5">
                    <Nav></Nav>
                </div>
            </div>
        </div>
        <Basket></Basket>
    </div>
</template>
