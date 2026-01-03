<script setup>
const props = defineProps({product: Object});
</script>
<template>
    <div class="max-w-65 w-65">
        <img class="w-full h-65 object-cover object-center rounded-[10px]" :src="product.img" alt="product image">
        <div class="flex items-center text-lime-500 text-xl">
            <div class="truncate">{{ product.price }}</div>
            <div>₽</div>
        </div>
        <div class="text-[16px] line-clamp-2">{{ product.name }}</div>
        <div class="flex gap-1.25">
            <img src="/public/img/star_gold.svg" alt="rating">
            <div>4.5</div>
            <img src="/public/img/comment.svg" alt="reviews">
            <div class="text-gray">2 отзыва</div>
        </div>
        <slot></slot>
    </div>
</template>
