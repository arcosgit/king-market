import { defineStore } from "pinia";
import {useUserBalanceStore} from "@/storage/balance/userBalance.js";
import {useBasketStore} from "@/storage/basket/basket.js";
import {useBusinessStore} from "@/storage/business/business.js";
import {useCreateProductStore} from "@/storage/product/create.js";
export const useUserStore = defineStore('user', {
    state: () => ({
        id: null,
        name: null,
        email: null,
        roleId: null,
        profile: 'user',
        isLoginAttempt: false,
    }),
    actions: {
        setUser(user){
            this.id = user.id;
            this.name = user.name;
            this.email = user.email;
            this.roleId = user.role_id;
        },

        resetUser(){
            this.id = null;
            this.name = null;
            this.email = null;
            this.roleId = null;
            this.profile = 'user';
            useUserBalanceStore().balance = 0;
            useBasketStore().resetData();
            useBusinessStore().resetData();
            useCreateProductStore().resetData();
        }
    }
});
