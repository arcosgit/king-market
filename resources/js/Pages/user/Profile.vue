<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import { Head } from '@inertiajs/vue3';
import UserProfile from '@/Components/user/profile/UserProfile.vue'

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('titleProfile') }}</title>
        <meta name="description" :content="useTranslateStore().t('descriptionProfile')">
    </Head>
    <MainLayout>
        <div class="w-full flex justify-center items-center">
            <select class="rounded-[10px] bg-violet-800  p-2 cursor-pointer focus:outline-none">
                <option value="">{{ useTranslateStore().t('userProfile') }}</option>
                <option value="">{{ useTranslateStore().t('businessProfile') }}</option>
                <option value="">{{ useTranslateStore().t('adminPanel') }}</option>
            </select>
        </div>
        <UserProfile></UserProfile>
    </MainLayout>
</template>
