<script setup>
import MainLayout from '@/Layout/MainLayout.vue';
import {useTranslateStore} from "@/storage/lang/translate.js";
import {useUserStore} from "@/storage/user/user.js";
import { Head } from '@inertiajs/vue3';
import UserProfile from '@/Components/user/profile/UserProfile.vue';
import NoAuth from '@/Components/user/helpers/NoAuth.vue';
import { ref } from 'vue';
import BusinessProfile from '@/Components/user/profile/BusinessProfile.vue';
import AdminProfile from '@/Components/user/profile/AdminProfile.vue'

const profile = ref('user');

</script>
<template>
    <Head>
        <title>{{ useTranslateStore().t('titleProfile') }}</title>
        <meta name="description" :content="useTranslateStore().t('descriptionProfile')">
    </Head>
    <MainLayout>
        <div v-if="useUserStore().id != null">
            <div class="w-full flex justify-center items-center">
                <select v-model="profile" class="rounded-[10px] bg-violet-800  p-2 cursor-pointer focus:outline-none">
                    <option value="user">{{ useTranslateStore().t('userProfile') }}</option>
                    <option value="business">{{ useTranslateStore().t('businessProfile') }}</option>
                    <option v-if="useUserStore().roleId == 2" value="admin">{{ useTranslateStore().t('adminPanel') }}</option>
                </select>
            </div>
            <div v-if="profile == 'user'">
                <UserProfile></UserProfile>
            </div>
            <div class="relative" v-if="profile == 'business'">
                <BusinessProfile></BusinessProfile>
            </div>
            <div class="relative" v-if="profile == 'admin'">
                <AdminProfile></AdminProfile>
            </div>
        </div>
        <div v-else>
            <NoAuth></NoAuth>
        </div>
    </MainLayout>
</template>
