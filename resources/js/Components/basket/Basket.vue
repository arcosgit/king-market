<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBasketStore} from "@/storage/basket/basket.js";
import Card from '../product/Card.vue';

const checkQuantity = (quantity) => {
    if (quantity <= 0){
        return 1;
    }
    if(quantity > 100){
        return 100;
    }
    return quantity;
}
</script>
<template>
    <Teleport to="body">
        <div :class="{ 'translate-x-full': !useBasketStore().isOpen, 'shadow-[0_0px_15px_0_rgba(255,255,255,0.4)]': useBasketStore().isOpen }" class="fixed py-1.25 px-2.5 right-0 top-0 bottom-0 w-75 max-w-75 h-screen overflow-y-auto bg-dark z-999 rounded-l-[20px] transition duration-300 overflow-x-hidden">
            <div class="relative">
                <div class="text-xl text-center">{{ useTranslateStore().t('basket') }}</div>
                <img @click.prevent="useBasketStore().isOpen = !useBasketStore().isOpen" class="cursor-pointer absolute top-0 right-0 mt-1.25" src="/public/img/close.svg" alt="close">
            </div>
            <div v-if="useBasketStore().products.length > 0" class="flex flex-col gap-y-3.75">
                <div v-for="(product, index) in useBasketStore().products" :key="index">
                    <Card :product="product.product" :flexEnabled="true">
                        <div class="flex text-sm items-center gap-x-1.25">
                            <div>{{ useTranslateStore().t('quantity') }}</div>
                            <input v-model="product.quantity" @change.prevent="product.quantity = checkQuantity(product.quantity)" max="100" min="1" type="number" class="h-3.75 w-7.5 focus:outline-none border border-white no-spinner py-2 px-0.5">
                        </div>
                        <button class="border text-sm w-full h-7.5 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer mt-1">{{ useTranslateStore().t('deleteСart') }}</button>
                    </Card>
                </div>
            </div>
        </div>
    </Teleport>
</template>
