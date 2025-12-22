<script setup>
import Header from './Header.vue';
</script>
<template>
    <div class="container mx-auto flex flex-col gap-y-10 h-full relative">
        <div class="sticky top-0 z-10 bg-dark"> <!-- bg-white или фон твой -->
            <Header></Header>
        </div>
        <main class="grow shadow-[0_4px_24px_0_rgba(255,255,255,0.4)] rounded-t-[20px] px-2.5 py-5">
            <slot></slot>
        </main>
    </div>
</template>
