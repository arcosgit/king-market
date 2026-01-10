<script setup>
import { onBeforeMount, ref } from 'vue';
import Header from './Header.vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import {useUserStore} from "@/storage/user/user.js";
import {useUserBalanceStore} from "@/storage/balance/userBalance.js";
import Load from '@/Widgets/icons/Load.vue';
import Basket from '@/Components/basket/Basket.vue';
import Catalog from '@/Components/catalog/Catalog.vue';

const load = ref(true);
const showCatalog = ref(false);

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
        } catch (error) {
            alert('error server');
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
            <Header @showCatalog="showCatalog = !showCatalog"></Header>
        </div>
        <main :class="{'px-2.5': !showCatalog}" class="grow shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] py-5 rounded-t-[20px] mt-32.5">
            <div v-if="!showCatalog">
                <slot></slot>
            </div>
            <div v-else>
                <Catalog></Catalog>
            </div>
        </main>
        <Basket></Basket>
    </div>
</template>
