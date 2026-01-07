<script setup>
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
            <div class="flex gap-1.25">
                <img src="/public/img/star_gold.svg" alt="rating">
                <div>4.5</div>
                <img src="/public/img/comment.svg" alt="reviews">
                <div class="text-gray">2 отзыва</div>
            </div>
        </Link>
        <slot></slot>
    </div>
    <div v-else class="flex gap-x-2.5 mt-2.5">
        <Link :href="route('product.show', props.product.id)">
            <img class="w-25 h-25 object-cover object-center rounded-[10px]" :src="props.product.images[0].img" alt="product image">
        </Link>
        <div>
            <Link :href="route('product.show', props.product.id)">
                <div class="text-[14px] line-clamp-2 wrap-break-word max-w-42.75">{{ props.product.name }}</div>
                <div class="flex gap-1.25 text-sm">
                    <img src="/public/img/star_gold.svg" alt="rating">
                    <div>4.5</div>
                    <img src="/public/img/comment.svg" alt="reviews">
                    <div class="text-gray">2 отзыва</div>
                </div>
                <div class="flex items-center text-lime-500 text-base">
                    <div class="truncate max-w-41">{{ props.product.price }}</div>
                    <div>₽</div>
                </div>
            </Link>
            <slot></slot>
        </div>
    </div>
</template>
