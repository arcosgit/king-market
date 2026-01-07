<script setup>
import ModalWindow from '../../modal/ModalWindow.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { ref, reactive, watch } from 'vue';
import axios from 'axios';
import { route } from 'ziggy-js';
import Load from '@/Widgets/icons/Load.vue';

const props = defineProps({show: Boolean, product: Object});
const emit = defineEmits(['close', 'new_review']);
const review = reactive({text: '', rating: 5});
const load = ref(false);
const errors = reactive({text: '', rating: ''});
const success = ref(null);

watch(() => props.show, (newValue) => {
    if(newValue && props.product){
        if(props.product.review_text != null){
            review.text = props.product.review_text;
            review.rating = props.product.review_rating;
        } else {
            review.text = '';
            review.rating = 5;
        }
    }
});

const submitReview = async () => {
    errors.text = '';
    errors.rating = '';
    if(!review.text.trim()){
        errors.text = useTranslateStore().t('reviewRequired');
        return;
    }
    if(!review.rating || review.rating < 1 || review.rating > 5){
        errors.rating = useTranslateStore().t('ratingRequired');
        return;
    }
    load.value = true;
    try{
        await axios.post(route('product.create.update.review'), {product_id: props.product.id, rating: review.rating, review: review.text});
        setTimeout(() => {
            load.value = false;
            success.value = useTranslateStore().t('success');
            emit('new_review', {product_id: props.product.id, review_text: review.text, review_rating: review.rating});
            setTimeout(()=>{success.value = null}, 3000);
        }, 1000);
    } catch (error){
        alert(error.response.data.errors.product_id[0]);
    }
    
};

</script>
<template>
    <ModalWindow :show="props.show" :name="useTranslateStore().t('review')" :hideTop="load" @close="$emit('close')">
        <div v-if="props.product" class="flex flex-col gap-y-5">
            <div class="flex gap-x-2.5 items-center">
                <img :src="props.product.img" :alt="props.product.name"class="w-25 h-25 object-cover object-center rounded-[10px]">
                <div class="flex flex-col">
                    <div class="text-[16px] wrap-break-word">{{ props.product.name }}</div>
                    <div class="flex items-center text-lime-500 text-xl mt-1">
                        <div class="truncate">{{ props.product.price }}</div>
                        <div>₽</div>
                    </div>
                </div>
            </div>
            <form v-if="!load" @submit.prevent="submitReview" class="flex flex-col gap-y-5" >
                <div>
                    <label class="block mb-2.5">{{ useTranslateStore().t('rating') || 'Оценка' }}</label>
                    <select v-model="review.rating" class="bg-[#263646] w-full rounded-[10px] h-10 focus:outline-none cursor-pointer border-2 border-lime-500">
                        <option :value="5">⭐⭐⭐⭐⭐</option>
                        <option :value="4">⭐⭐⭐⭐</option>
                        <option :value="3">⭐⭐⭐</option>
                        <option :value="2">⭐⭐</option>
                        <option :value="1">⭐</option>
                    </select>
                    <div v-if="errors.rating" class="mt-2.5 text-red-500">{{ errors.rating }}</div>
                </div>
                <div>
                    <label class="block mb-2.5">{{ useTranslateStore().t('review') }}</label>
                    <textarea v-model="review.text"maxlength="2000" class="p-2.25 w-full min-h-22.5 resize-y max-h-40 border-2 border-lime-500 rounded-[10px] focus:outline-none" :placeholder="useTranslateStore().t('enterReview')" required></textarea>
                    <div v-if="errors.text" class="mt-2.5 text-red-500">{{ errors.text }}</div>
                    <div v-if="success" class="mt-2.5 text-lime-500">{{ success }}</div>
                </div>
                <button type="submit" class="btn-purple h-10 w-full" >{{ useTranslateStore().t('send') || 'Отправить' }}</button>
            </form>
            <div v-if="load" class="flex justify-center items-center h-40">
                <Load :text="'dataProcessing'"></Load>
            </div>
        </div>
    </ModalWindow>
</template>
