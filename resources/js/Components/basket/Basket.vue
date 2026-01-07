<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBasketStore} from "@/storage/basket/basket.js";
import {useUserBalanceStore} from "@/storage/balance/userBalance.js";
import {useUserStore} from "@/storage/user/user.js";
import Card from '../product/Card.vue';
import { onMounted, reactive, ref, watch } from "vue";
import axios from "axios";
import { route } from "ziggy-js";
import NoAuth from '../user/helpers/NoAuth.vue';

const amount = ref(null);
const isDisabledPayBtn = ref(false);
const load = ref(false);
const successPayment = ref(false);
const errors = reactive({notEnoughMoney: null});

const checkQuantity = (quantity) => {
    if (quantity <= 0){
        return 1;
    }
    if(quantity > 100){
        return 100;
    }
    return quantity;
}
watch(() => useBasketStore().products, ()=>{
    totalAmount();
}, {deep: true});

const totalAmount = () => {
    if(useBasketStore().products.length > 0){
        amount.value = 0;
        for(let i = 0; i < useBasketStore().products.length; i++){
            amount.value += useBasketStore().products[i].product.price * useBasketStore().products[i].quantity;
            amount.value = Number(amount.value.toFixed(2));
        }
    }
}

const buy = async () => {
    errors.notEnoughMoney = null;
    if(amount.value > useUserBalanceStore().balance){
        errors.notEnoughMoney = useTranslateStore().t('notEnoughMoney');
        return;
    }
    load.value = true;
    try{
        const res = await axios.post(route('product.buy'), {products: useBasketStore().products});
        setTimeout(()=>{
            load.value = false;
            successPayment.value = true;
            useBasketStore().products = [];
            useUserBalanceStore().balance = Number(res.data.balance.toFixed(2));
            setTimeout(()=>{successPayment.value = false},2000);
        },1000);
    } catch (error){
        load.value = false;
        alert('error pay');
    }
}

onMounted(() => {
    totalAmount();
})
</script>
<template>
    <Teleport to="body">
        <div :class="{ 'translate-x-full': !useBasketStore().isOpen, 'shadow-[0_0px_15px_0_rgba(255,255,255,0.4)]': useBasketStore().isOpen }" class="fixed py-1.25 px-2.5 right-0 top-0 bottom-0 w-75 max-w-75 h-screen overflow-y-auto bg-dark z-999 rounded-l-[20px] transition duration-300 overflow-x-hidden custom-scrollbar">
            <div class="relative">
                <div class="text-xl text-center">{{ useTranslateStore().t('basket') }}</div>
                <img @click.prevent="useBasketStore().isOpen = !useBasketStore().isOpen" class="cursor-pointer absolute top-0 right-0 mt-1.25" src="/public/img/close.svg" alt="close">
            </div>
            <div v-if="successPayment" class="text-lime-500 mt-2.5 text-center text-lg">{{ useTranslateStore().t('paymentSuccessful') }}</div>
            <div v-if="useBasketStore().products.length > 0" class="flex flex-col gap-y-3.75">
                <div v-for="(product, index) in useBasketStore().products" :key="index">
                    <Card :product="product.product" :flexEnabled="true">
                        <div class="flex text-sm items-center gap-x-1.25">
                            <div>{{ useTranslateStore().t('quantity') }}</div>
                            <input @focus.prevent="isDisabledPayBtn = true" @blur.prevent="isDisabledPayBtn = false" v-model="product.quantity" @change.prevent="product.quantity = checkQuantity(product.quantity)" max="100" min="1" type="number" class="h-3.75 w-7.5 focus:outline-none border border-white no-spinner py-2 px-0.5">
                        </div>
                        <button @click.prevent="useBasketStore().deleteFromCart(product.product.id)" class="border text-sm w-full h-7.5 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer mt-1">{{ useTranslateStore().t('deleteСart') }}</button>
                    </Card>
                </div>
            </div>
            <div v-if="useBasketStore().products.length > 0">
                <div class="text-base mt-2.5">{{ useTranslateStore().t('amountPaid') }}: <span :class="amount > useUserBalanceStore().balance ? 'text-orange-300' : 'text-lime-500'" class="wrap-break-word">{{ amount }}₽</span></div>
                <div v-if="useUserStore().id != null">
                    <div class="text-base mt-2.5">{{ useTranslateStore().t('balance') }}: <span class="wrap-break-word text-blue">{{ useUserBalanceStore().balance }}₽</span></div>
                    <div v-if="errors.notEnoughMoney" class="text-red-500 mt-2.5">{{ errors.notEnoughMoney }}</div>
                    <div class="w-full flex mt-2.5" :class="!load ? 'justify-end' : 'justify-center'">
                        <button v-if="!load && !isDisabledPayBtn" @click.prevent="buy" class="btn-green h-10">{{ useTranslateStore().t('pay') }}</button>
                        <div v-if="load" class="w-7.5 h-7.5 border-3 text-blue-400 text-4xl animate-spin border-gray-300 flex items-center justify-center border-t-blue-400 rounded-full"></div>
                    </div>
                </div>
                <div v-else class="mt-2.5">
                    <div class="text-center text-lg text-purple text-violet-800">{{ useTranslateStore().t('authForPay') }}</div>
                    <div class="mt-1">
                        <NoAuth :showText="false"></NoAuth>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>
