import { defineStore } from "pinia";
export const useUserBalanceStore = defineStore('userBalance', {
    state: () => ({
        balance: 0,
    }),
});
