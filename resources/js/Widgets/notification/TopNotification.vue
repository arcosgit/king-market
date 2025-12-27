<script setup>
import { onMounted } from 'vue';

const props = defineProps({text: String, textParam: String, hideAfter: Number});
const emit = defineEmits(['close']);

onMounted(() => {
    if(props.hideAfter){
        setTimeout(()=>{
            emit('close');
        }, props.hideAfter);
    }
});
</script>
<template>
    <Teleport to="body">
        <div class="fixed z-100 inset-0 transition duration-300 bg-[rgba(0,0,0,0.5)] bg-opacity-20 max-[510px]:px-2.5">
            <div class="flex justify-center">
                <Transition name="slide-down" appear>
                    <div class="flex justify-between items-center bg-dark shadow-[0_0px_15px_0_rgba(255,255,255,0.4)] p-2 rounded-[10px] max-w-125 w-full mt-5">
                        <div :class="props.textParam" class="wrap-break-word">{{ props.text }}</div>
                        <img @click.prevent="$emit('close')" class="cursor-pointer" src="/public/img/close.svg" alt="close">
                    </div>
                </Transition>
            </div>
        </div>
    </Teleport>
</template>
