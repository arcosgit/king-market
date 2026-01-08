import { defineStore } from "pinia";
import {useTranslateStore} from "@/storage/lang/translate.js";
import axios from "axios";
import { route } from "ziggy-js";
export const useFavoriteStore = defineStore('favorite', {
    actions: {
        async addFavorite(productId){
            try{
                await axios.post(route('user.favorite.product.action'), {product_id: productId});
                return true;
            } catch(e){
                alert(useTranslateStore().t('productNotFound'));
                return false;
            }
        },

        async deleteFavorite(productId){
            await axios.post(route('user.favorite.product.action'), {product_id: productId});
        }

    }
});
