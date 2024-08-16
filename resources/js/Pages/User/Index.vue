<script>
import { defineComponent, ref, onMounted } from 'vue';
import { useUserStore } from '@/Stores/userStore.js';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import {darkTheme, useMessage} from 'naive-ui';
import MyLayout from '@/Layouts/MyLayout.vue';
import { AddCircleSharp as Add } from '@vicons/ionicons5';

export default defineComponent({
    components: {
        MyLayout,
        Add
    },
    setup() {
        const store = useUserStore();
        const pageProps = usePage().props;
        const message = useMessage();
        const showModalRef = ref(false);
        const selectedUserId = ref(null);

        onMounted(() => {
            store.setUsers(pageProps.users || []);
            store.setFlash(pageProps.flash || []);
            store.setPermissions(pageProps.user ? pageProps.user.permissions : []);
        });

        const CreateUser = () => {
            router.get(route('user.create'));
        };

        const ShowUser = (id) => {
            router.get(route('user.show', {user: id}));
        };

        const EditUser = (id) => {
            try {
                router.get(route('user.edit', {user: id}));
            } catch (e) {
                console.log(e);
            }
        };

        const DeleteUser = () => {
            try {
                router.delete(route('user.destroy', {user: selectedUserId.value}));
                showModalRef.value = false;
                message.success('User deleted successfully.');
            } catch (error) {
                message.error('Failed to delete user.');
                console.log(error);
            }
        };

        const openDeleteModal = (id) => {
            selectedUserId.value = id;
            showModalRef.value = true;
        };

        const hasPermission = (permission) => {
            return store.hasPermission(permission);
        };

        return {
            darkTheme,
            theme: ref(null),
            CreateUser,
            ShowUser,
            router,
            EditUser,
            DeleteUser,
            openDeleteModal,
            hasPermission,
            message,
            store,
            showModal: showModalRef,
            onPositiveClick() {
                DeleteUser();
            },
            onNegativeClick() {
                showModalRef.value = false;
            }
        };
    }
});
</script>

<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height:700px;">
            <n-space justify="end" class="mb-4">
                <n-button :disabled="!hasPermission('User Create')" type="primary" size="large" @click="CreateUser">
                    <n-icon size="30">
                        <Add/>
                    </n-icon>
                    Add User
                </n-button>
            </n-space>
            <n-space vertical :size="12">
                <n-alert
                    v-if="store.flash.success"
                    type="success"
                    show-icon
                    closable
                    style="margin-bottom: 16px"
                >
                    {{ store.flash.success }}
                </n-alert>
            </n-space>
            <n-table :bordered="false" :single-line="false">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in store.users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <n-space>
                            <n-badge type="info" v-if="user.roles.length > 0" v-for="role in user.roles" :key="role"
                                     :value="role" :max="15"/>
                        </n-space>
                    </td>
                    <td>
                        <n-space>
                            <n-button :disabled="!hasPermission('User Show')" type="info" class="mr-2"
                                      @click="ShowUser(user.id)">Show
                            </n-button>
                            <n-button :disabled="!hasPermission('User Edit')" type="primary" class="mr-2"
                                      @click="EditUser(user.id)">Edit
                            </n-button>
                            <n-button :disabled="!hasPermission('User Delete')" type="error"
                                      @click="openDeleteModal(user.id)">Delete
                            </n-button>
                        </n-space>
                    </td>
                </tr>
                </tbody>
            </n-table>

            <n-modal
                v-model:show="showModal"
                :mask-closable="false"
                preset="dialog"
                title="Confirm Delete"
                content="Are you sure you want to delete this user?"
                positive-text="Confirm"
                negative-text="Cancel"
                @positive-click="onPositiveClick"
                @negative-click="onNegativeClick"
            />
        </n-layout-content>
    </MyLayout>
</template>
