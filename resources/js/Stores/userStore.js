import { defineStore} from "pinia";

export const useUserStore = defineStore('userStore', {
    state: () => {
        return {
            users:  [],
            permissions:  [],
            flash: [],
        };
    },
    getters: {
        hasPermission: (state) => (permission) => {
            return state.permissions.includes(permission);
        }
    },
    actions: {
        setUsers(users) {
            this.users = users;
        },

        setFlash(flash) {
            this.flash = flash;
        },

        setPermissions(permissions) {
            this.permissions = permissions;
        }


    }


})
