<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import { defineComponent, ref } from 'vue'
import { darkTheme } from 'naive-ui'
import {router, usePage} from "@inertiajs/vue3";
import { AddCircleSharp as Add } from '@vicons/ionicons5';
import { useMessage} from "naive-ui";

export default defineComponent({
    components: {
        MyLayout,
        Add

    },
    props: {
        permissions: Object,
        flash: Object
    },
    setup() {
        const pageProps = usePage().props;
        const permissions = ref(pageProps.user ? pageProps.user.permissions : []);
        const hasPermission = (permission) => {
            return permissions.value.includes(permission);
        };


        const message = useMessage();
        const showModalRef = ref(false);
        const selectPermissionId = ref(null);
        const CreatePermission = () => {
            router.get(route('permission.create'));
        };

        const ShowPermission = (id) =>{
            router.get(route('permission.show', { permission: id}));
        };

        const EditPermission = (id) => {
            router.get(route('permission.edit', { permission: id}))
        }

        const DeletePermission = (id) => {
            try {
                // HTTP so'rov yuborish
                router.delete(route('permission.destroy', { permission: selectPermissionId.value }));
                message.success('User deleted successfully.');
                showModalRef.value = false;
            } catch (error) {
                message.error('Failed to delete user.');
                console.log(error)
            }
        };
        const openDeleteModal = (id) => {
            selectPermissionId.value = id; // O'chirilishi kerak bo'lgan foydalanuvchini saqlash
            showModalRef.value = true;
        };
        return {
            darkTheme,
            router,
            CreatePermission,
            ShowPermission,
            DeletePermission,
            EditPermission,
            openDeleteModal,
            hasPermission,
            showModal: showModalRef,
            theme: ref(null),
            onPositiveClick() {
                DeletePermission();  // Tasdiqlash tugmasi bosilganda foydalanuvchini o'chirish
            },
            onNegativeClick() {
                showModalRef.value = false;
            },
        }
    }
})
</script>
<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height:700px;">
            <n-space justify="end" class="mb-4">
                <n-button :disabled="!hasPermission('Permission Create')" type="primary" size="large" @click="CreatePermission">
                    <n-icon size="30"><Add/></n-icon> Add Permission
                </n-button>
            </n-space>
            <n-alert
                v-if="flash.success"
                type="success"
                show-icon
                closable
                style="margin-bottom: 16px"
            >
                {{ flash.success }}
            </n-alert>
            <n-table :bordered="false" :single-line="false">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Permission Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="permission in permissions" :key="permission.id">
                    <td>{{ permission.id}}</td>
                    <td>{{ permission.name}}</td>
                    <td>
                        <n-space size="small" >
                            <n-button :disabled="!hasPermission('Permission Show')" type="info" class="mr-2" @click="ShowPermission(permission.id)">Show</n-button>
                            <n-button :disabled="!hasPermission('Permission Edit')" type="primary" class="mr-2" @click="EditPermission(permission.id)">Edit</n-button>
                            <n-button :disabled="!hasPermission('Permission Delete')" type="error" @click="openDeleteModal(permission.id)">Delete</n-button>
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


