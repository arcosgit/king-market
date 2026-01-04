<script setup>
import { ref } from 'vue';

const props = defineProps({images: Object});
const currentImg = ref(props.images[0].img);
</script>
<template>
    <div class="flex gap-x-2.5 min-w-152.5 max-w-152.5">
        <div class="flex flex-col gap-y-2.5 overflow-y-auto custom-scrollbar">
            <div v-for="(image, index) in props.images">
                <img @click.prevent="currentImg = image.img" class="w-25 h-25 rounded-[10px] object-cover object-center hover:border-2 hover:border-[#2980B9] cursor-pointer" :src="image.img" alt="product image">
            </div>
        </div>
        <img class="w-125 h-125 rounded-[10px] object-cover object-center" :src="currentImg" alt="product image">
    </div>
</template>
