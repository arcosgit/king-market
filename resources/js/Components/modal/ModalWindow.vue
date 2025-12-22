<script setup>
const props = defineProps({show: Boolean, name: String});
defineEmits(['close']);
</script>
<template>
    <Teleport to="body">
        <Transition name="modal" appear>
            <div v-if="props.show" @click.self="$emit('close')" class="fixed z-100 inset-0 flex items-center justify-center transition duration-300 bg-[rgba(0,0,0,0.5)] bg-opacity-20">
                <div class="modal-window bg-dark w-125 max-w-125 max-h-[90vh] rounded-[20px] transition duration-300 shadow-[0_4px_24px_0_rgba(255,255,255,0.4)]">
                    <div class="p-2.5">
                        <div class="flex justify-between items-center">
                            <div class="fake"></div>
                            <div>{{ props.name }}</div>
                            <img class="block cursor-pointer" @click.prevent="$emit('close')" src="/public/img/close.svg" alt="close modal">
                        </div>
                        <slot></slot>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
