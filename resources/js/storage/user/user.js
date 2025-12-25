import { defineStore } from "pinia";
export const useUserStore = defineStore('user', {
    state: () => ({
        id: null,
        name: null,
        email: null,
        roleId: null,
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
        }
    }
});
