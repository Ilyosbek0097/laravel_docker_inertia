import { usePage } from '@inertiajs/vue3';
import {ref} from "vue";

export function usePermissions() {
    const pageProps = usePage().props;

    const permissions = ref(pageProps.user ? pageProps.user.permissions : []);

    const hasPermission = (permission) => {
        return permissions.value.includes(permission);
    };
}
