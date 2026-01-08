<script setup>
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useFavoriteStore} from "@/storage/user/favorite.js";
import {useUserStore} from "@/storage/user/user.js";
import { ref } from "vue";
const props = defineProps({product: Object, flexEnabled: {type: Boolean, default: false}, turnOffFavorite: {type: Boolean, default: false}});
const reactiveFavorite = ref(props.product.is_favorite);

const deleteFavorite = () => {
    reactiveFavorite.value = null;
    useFavoriteStore().deleteFavorite(props.product.id);
}

const addFavorite = async () => {
    reactiveFavorite.value = true;
    const res = useFavoriteStore().addFavorite(props.product.id);
    if(!res) reactiveFavorite.value = null;
}
</script>
<template>
    <div v-if="!props.flexEnabled" class="max-w-65 w-65 min-h-96">
        <Link :href="route('product.show', props.product.id)" :class="{'flex': props.flexEnabled}">
            <div class="relative group">
                <img class="w-full h-65 object-cover object-center rounded-[10px]" :src="props.product.img" alt="product image">
                <div v-if="useUserStore().id != null && !props.turnOffFavorite">
                    <img v-if="reactiveFavorite" @click.prevent="deleteFavorite" class="opacity-0 group-hover:opacity-100 absolute top-0 right-0 p-1 transition duration-150 z-10" src="/public/img/favorite_red_full.svg" alt="favorite delete">
                    <img v-else @click.prevent="addFavorite" class="opacity-0 group-hover:opacity-100 absolute top-0 right-0 p-1 transition duration-150 z-10" src="/public/img/favorite_red.svg" alt="favorite add">
                </div>
            </div>
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
            <div class="relative group">
                <img class="w-25 h-25 object-cover object-center rounded-[10px]" :src="props.product.images ? props.product.images[0].img : props.product.img" alt="product image">
                <div v-if="useUserStore().id != null && !props.turnOffFavorite">
                    <img v-if="reactiveFavorite" @click.prevent="deleteFavorite" class="opacity-0 group-hover:opacity-100 absolute top-0 right-0 p-1 transition duration-150 z-10" src="/public/img/favorite_red_full.svg" alt="favorite delete">
                    <img v-else @click.prevent="addFavorite" class="opacity-0 group-hover:opacity-100 absolute top-0 right-0 p-1 transition duration-150 z-10" src="/public/img/favorite_red.svg" alt="favorite add">
                </div>
            </div>
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
