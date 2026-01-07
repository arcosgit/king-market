<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
const props = defineProps({product: Object, flexEnabled: {type: Boolean, default: false}});
</script>
<template>
    <div v-if="!props.flexEnabled" class="max-w-65 w-65 min-h-96">
        <Link :href="route('product.show', props.product.id)" :class="{'flex': props.flexEnabled}">
            <img class="w-full h-65 object-cover object-center rounded-[10px]" :src="props.product.img" alt="product image">
            <div class="flex items-center text-lime-500 text-xl">
                <div class="truncate">{{ props.product.price }}</div>
                <div>₽</div>
            </div>
            <div class="text-[16px] line-clamp-2 wrap-break-word">{{ props.product.name }}</div>
            <div v-if="props.product.reviews_count != null" class="flex items-center gap-1.25">
                <img class="w-5 h-5" src="/public/img/star_gold.svg" alt="rating">
                <div>{{ props.product.rating_average }}</div>
                <img class="w-4 h-4" src="/public/img/comment.svg" alt="reviews">
                <div class="text-gray">{{ props.product.reviews_count }}</div>
            </div>
            <div v-else class="text-gray">{{ useTranslateStore().t('no_reviews') }}</div>
        </Link>
        <slot></slot>
    </div>
    <div v-else class="flex gap-x-2.5 mt-2.5">
        <Link :href="route('product.show', props.product.id)">
            <img class="w-25 h-25 object-cover object-center rounded-[10px]" :src="props.product.images ? props.product.images[0].img : props.product.img" alt="product image">
        </Link>
        <div>
            <Link :href="route('product.show', props.product.id)">
                <div class="text-[14px] line-clamp-2 wrap-break-word max-w-42.75">{{ props.product.name }}</div>
                <div v-if="props.product.reviews_count != null" class="flex items-center gap-1.25">
                    <img class="w-5 h-5" src="/public/img/star_gold.svg" alt="rating">
                    <div>{{ props.product.rating_average }}</div>
                    <img class="w-4 h-4" src="/public/img/comment.svg" alt="reviews">
                    <div class="text-gray">{{ props.product.reviews_count }}</div>
                </div>
                <div v-else class="text-gray">{{ useTranslateStore().t('no_reviews') }}</div>
                <div class="flex items-center text-lime-500 text-base">
                    <div class="truncate max-w-41">{{ props.product.price }}</div>
                    <div>₽</div>
                </div>
            </Link>
            <slot></slot>
        </div>
    </div>
</template>
