<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({images: Object});
const currentImgIndex = ref(0);
const isFullscreen = ref(false);

const currentImg = computed(() => props.images[currentImgIndex.value]?.img);

const openFullscreen = () => {
    isFullscreen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeFullscreen = () => {
    isFullscreen.value = false;
    document.body.style.overflow = '';
};

const nextImage = () => {
    currentImgIndex.value = (currentImgIndex.value + 1) % props.images.length;
};

const prevImage = () => {
    currentImgIndex.value = currentImgIndex.value === 0 ? props.images.length - 1 : currentImgIndex.value - 1;
};

const selectImage = (index) => {
    currentImgIndex.value = index;
};

const handleKeydown = (e) => {
    if (!isFullscreen.value) return;
    if (e.key === 'Escape') closeFullscreen();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
    document.body.style.overflow = '';
});
</script>
<template>
    <div class="flex gap-2.5 min-w-152.5 max-w-152.5 max-[660px]:flex-col max-[660px]:min-w-0 max-[660px]:w-full max-[660px]:max-w-full">
        <div class="flex flex-col gap-2.5 overflow-auto custom-scrollbar max-[660px]:w-full max-h-125 max-[660px]:flex-row max-[660px]:overflow-y-hidden">
            <div v-for="(image, index) in props.images" :key="index" class="max-[660px]:shrink-0">
                <img @click.prevent="selectImage(index)" class="w-25 h-25 rounded-[10px] object-cover object-center hover:border-2 hover:border-[#2980B9] cursor-pointer" :src="image.img" alt="product image">
            </div>
        </div>
        <img @click="openFullscreen" class="w-125 h-125 rounded-[10px] object-cover object-center cursor-pointer" :src="currentImg" alt="product image">
    </div>
    <div v-if="isFullscreen" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
        <button @click.prevent="closeFullscreen" class="absolute top-4 right-4 z-60 p-2">
            <img src="/public/img/close.svg" alt="close" class="w-6 h-6 cursor-pointer">
        </button>
        <div class="relative flex items-center justify-center w-full h-full px-5">
            <button @click="prevImage" class="absolute left-4 p-3 z-10">
                <img src="/public/img/arrow.svg" alt="previous" class="w-8 h-8 transform rotate-90 cursor-pointer">
            </button>
            <img :src="currentImg" alt="product image" class="max-h-[95vh] max-w-[calc(100%-120px)] object-contain">
            <button @click="nextImage" class="absolute right-4 p-3 z-10">
                <img src="/public/img/arrow.svg" alt="next" class="w-8 h-8 transform -rotate-90 cursor-pointer">
            </button>
        </div>
    </div>
</template>
