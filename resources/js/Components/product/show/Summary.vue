<script setup>
import { onMounted, ref, watch } from 'vue';
import { route } from 'ziggy-js';
import TopNotification from '@/Widgets/notification/TopNotification.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useBasketStore} from "@/storage/basket/basket.js";
import {useFavoriteStore} from "@/storage/user/favorite.js";
import {useUserStore} from "@/storage/user/user.js";

const props = defineProps({product: Object, preview: {type: Boolean, default: false}});
const copyText = ref('');
const isProductCart = ref(false);
const reactiveFavorite = ref(props.product.is_favorite);


const copy = async (isArticle = false) => {
    if(isArticle){
        await navigator.clipboard.writeText(props.product.id);
        copyText.value = useTranslateStore().t('articleСopied');
    } else {
        await navigator.clipboard.writeText(route('product.show', props.product.id));
        copyText.value = useTranslateStore().t('linkCopied');
    }
}

const deleteFavorite = () => {
    reactiveFavorite.value = null;
    useFavoriteStore().deleteFavorite(props.product.id);
}

const addFavorite = async () => {
    reactiveFavorite.value = true;
    const res = useFavoriteStore().addFavorite(props.product.id);
    if(!res) reactiveFavorite.value = null;
}

watch(()=>useBasketStore().products, () =>{
    const productCart = useBasketStore().products.find(product => product.product.id === props.product.id) ?? null;
    if(productCart == null) isProductCart.value = false;
});

onMounted(() => {
    const productCart = useBasketStore().products.find(product => product.product.id === props.product.id) ?? null;
    if(productCart != null) isProductCart.value = true;
});
</script>
<template>
    <Teleport to="body">
        <div v-if="copyText != ''">
            <TopNotification :text="copyText" textParam="text-lime-500" :hideAfter="1200" @close="copyText = ''"></TopNotification>
        </div>
    </Teleport>
    <div class="min-w-90 max-w-90 rounded-[20px] shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2.5">
        <div class="flex justify-between items-center">
            <div @click.prevent="props.preview ? '': copy(true)" class="flex gap-x-1 items-center cursor-pointer">
                <img src="/public/img/copy.svg" alt="copy">
                <div class="text-gray text-[12px]">{{ useTranslateStore().t('article') }}: {{ props.product.id ?? 'preview' }}</div>
            </div>
            <div @click.prevent="props.preview ? '': copy(false)" class="flex gap-x-1 items-center cursor-pointer">
                <img src="/public/img/share.svg" alt="share">
                <div class="text-gray text-[12px]">{{ useTranslateStore().t('share') }}</div>
            </div>
        </div>
        <div class="text-xl font-bold mt-1 wrap-break-word">{{ props.product.name }}</div>
        <div v-if="props.preview || props.product.reviews_count == null" class="flex gap-1.25 mt-1">
            <div class="text-gray">{{ useTranslateStore().t('no_reviews') }}</div>
        </div>
        <div v-if="!props.preview && props.product.reviews_count != null" class="flex gap-x-1.25 items-center">
            <img src="/public/img/star_gold.svg" alt="rating">
            <div>{{ props.product.rating_average }}</div>
            <img src="/public/img/comment.svg" alt="reviews">
            <div class="text-gray">{{ props.product.reviews_count }}</div>
        </div>
        <div v-if="!props.preview" class="text-base mt-1">{{ useTranslateStore().t('seller') }}: <span class="text-violet-800">{{ props.product.brand_name}}</span></div>
        <div v-else class="text-base mt-1">{{ useTranslateStore().t('seller') }}: <span class="text-violet-800">{{ useTranslateStore().t('yourBrand')}}</span></div>
        <div class="text-xl text-lime-500">{{ props.product.price }} ₽</div>
        <div v-if="!props.preview" class="flex gap-x-2.5 items-center mt-1">
            <button v-if="!isProductCart" @click.prevent="useBasketStore().products.push({quantity: 1, product: props.product}), isProductCart = true" class="btn-blue w-full h-10">{{ useTranslateStore().t('addСart') }}</button>
            <button v-if="isProductCart" @click.prevent="useBasketStore().deleteFromCart(props.product.id), isProductCart = false" class="border w-full h-10 p-2.5 flex justify-center items-center border-red-500 bg-red-500 rounded-[10px] hover:bg-inherit hover:text-red-500 transition duration-300 cursor-pointer">{{ useTranslateStore().t('deleteСart') }}</button>
            <div v-if="useUserStore().id != null">
                <img v-if="reactiveFavorite" @click.prevent="deleteFavorite" class="cursor-pointer rounded-[10px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150 min-w-10 min-h-10" src="/public/img/favorite_red_full.svg" alt="delete favorite">
                <img v-else @click.prevent="addFavorite" class="cursor-pointer rounded-[10px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150 min-w-10 min-h-10" src="/public/img/favorite_red.svg" alt="add favorite">
            </div>
        </div>
        <div v-else class="flex gap-x-2.5 items-center mt-1">
            <button class="btn-blue w-full h-10">{{ useTranslateStore().t('addСart') }}</button>
            <img class="cursor-pointer rounded-[10px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/favorite_red.svg" alt="add favorite">
        </div>
    </div>
</template>
