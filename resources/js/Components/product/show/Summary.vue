<script setup>
import { ref } from 'vue';
import { route } from 'ziggy-js';
import TopNotification from '@/Widgets/notification/TopNotification.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";

const props = defineProps({product: Object});
const copyText = ref('');
const copy = async (isArticle = false) => {
    if(isArticle){
        await navigator.clipboard.writeText(props.product.id);
        copyText.value = useTranslateStore().t('articleСopied');
    } else {
        await navigator.clipboard.writeText(route('product.show', props.product.id));
        copyText.value = useTranslateStore().t('linkCopied');
    }
}
</script>
<template>
    <Teleport to="body">
        <div v-if="copyText != ''">
            <TopNotification :text="copyText" textParam="text-lime-500" :hideAfter="1200" @close="copyText = ''"></TopNotification>
        </div>
    </Teleport>
    <div class="min-w-90 max-w-90 rounded-[20px] shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2.5">
        <div class="flex justify-between items-center">
            <div @click.prevent="copy(true)" class="flex gap-x-1 items-center cursor-pointer">
                <img src="/public/img/copy.svg" alt="copy">
                <div class="text-gray text-[12px]">{{ useTranslateStore().t('article') }}: {{ props.product.id }}</div>
            </div>
            <div @click.prevent="copy(false)" class="flex gap-x-1 items-center cursor-pointer">
                <img src="/public/img/share.svg" alt="share">
                <div class="text-gray text-[12px]">{{ useTranslateStore().t('share') }}</div>
            </div>
        </div>
        <div class="text-xl font-bold mt-1 wrap-break-word">{{ props.product.name }}</div>
        <div class="flex gap-1.25 mt-1">
            <img src="/public/img/star_gold.svg" alt="rating">
            <div>4.5</div>
            <img src="/public/img/comment.svg" alt="reviews">
            <div class="text-gray">2 отзыва</div>
        </div>
        <div class="text-base mt-1">Продавец: <span class="text-violet-800">{{ props.product.brand_name }}</span></div>
        <div class="text-xl text-lime-500">{{ props.product.price }} ₽</div>
        <div class="flex gap-x-2.5 items-center mt-1">
            <button class="btn-blue w-full h-10">{{ useTranslateStore().t('addСart') }}</button>
            <img class="cursor-pointer rounded-[10px] hover:shadow-[0_0px_15px_0_rgba(255,0,0,1)] transition duration-150" src="/public/img/favorite_red.svg" alt="add favorite">
        </div>
    </div>
</template>
