<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import { defineComponent, ref } from 'vue';
import { darkTheme } from 'naive-ui';
import { AddCircleSharp as Add } from '@vicons/ionicons5';
import {router, usePage} from "@inertiajs/vue3";
import { useMessage } from "naive-ui";
import permission from "./Permission.vue";

export default defineComponent({
    computed: {
        permission() {
            return permission
        }
    },
    props: {
        roles: Array,
        flash: Object
    },
    components: {
        MyLayout,
        Add
    },
    setup(props) {

        const pageProps = usePage().props;
        const permissions = ref(pageProps.user ? pageProps.user.permissions : []);
        const hasPermission = (permission) => {
            return permissions.value.includes(permission);
        };



        const showModalRef = ref(false);
        const selectRoleId = ref(null);
        const message = useMessage();

        const DeleteUser = () => {
            try {
                router.delete(route('role.destroy', { role: selectRoleId.value }));
                message.success('User deleted successfully.');
                showModalRef.value = false;
            } catch (error) {
                message.error('Failed to delete user.');
                console.log(error);
            }
        };

        const CreateRole = () => {
            router.get(route('role.create'));
        };

        const ShowRole = (id) => {
            router.get(route('role.show', { role: id }));
        };

        const EditRole = (id) => {
            router.get(route('role.edit', { role: id }));
        };

        const Permission = (id) => {
            router.get(route('role.permission', { role: id }));
        };

        const openDeleteModal = (id) => {
            selectRoleId.value = id; // O'chirilishi kerak bo'lgan ro'lni saqlash
            showModalRef.value = true;
        };

        return {
            darkTheme,
            CreateRole,
            ShowRole,
            EditRole,
            DeleteUser,
            openDeleteModal,
            Permission,
            hasPermission,
            router,
            message,
            showModal: showModalRef,
            onPositiveClick() {
                DeleteUser(); // Tasdiqlash tugmasi bosilganda ro'lni o'chirish
            },
            onNegativeClick() {
                showModalRef.value = false;
            },
            theme: ref(null)
        };
    }
});
</script>

<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height: 700px;">
            <n-space justify="end" class="mb-4">
                <n-button :disabled="!hasPermission('Role Create')" type="primary" size="large" @click="CreateRole">
                    <n-icon size="30"><Add /></n-icon> Add Role
                </n-button>
            </n-space>
            <n-space vertical :size="12">
                <!-- Xabarni ko'rsatish -->
                <n-alert
                    v-if="flash.success"
                    type="success"
                    show-icon
                    closable
                    style="margin-bottom: 16px"
                >
                    {{ flash.success }}
                </n-alert>
            </n-space>
            <n-table :bordered="false" :single-line="false">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td>{{ role.id }}</td>
                    <td style="width: 600px">{{ role.name }}</td>
                    <td style="width: 300px;">
                        <n-space vertical size="small">
                            <div v-if="role.permissions.length > 0">
                                <n-space wrap>
<!--                                    <n-badge-->
<!--                                        v-for="permission in role.permissions"-->
<!--                                        :key="permission.id"-->
<!--                                        :value="permission.name"-->
<!--                                        type="info"-->
<!--                                        style="font-size: 14px;"-->
<!--                                    />-->
                                    <n-tag  v-for="permission in role.permissions" :key="permission.id"
                                            :bordered="false" type="info">
                                        {{ permission.name }}

                                    </n-tag>
                                </n-space>
                            </div>
                            <div v-else>
                                <n-tag :bordered="false" type="error">
                                    No Permission
                                </n-tag>
<!--                                <n-badge-->
<!--                                    type="error"-->
<!--                                    :value="'No permissions'"-->
<!--                                    style="font-size: 14px;"-->
<!--                                />-->
                            </div>
                        </n-space>
                    </td>
                    <td>
                        <n-flex size="small" justify="center">
                            <n-button :disabled="!hasPermission('Role addPermission')" size="small" type="warning" class="mr-2" @click="Permission(role.id)">+/- Permission</n-button>
                            <n-button :disabled="!hasPermission('Role Show')" size="small" type="info" class="mr-2" @click="ShowRole(role.id)">Show</n-button>
                            <n-button :disabled="!hasPermission('Role Edit')" size="small" type="primary" class="mr-2" @click="EditRole(role.id)">Edit</n-button>
                            <n-button :disabled="!hasPermission('Role Delete')" size="small" type="error" @click="openDeleteModal(role.id)">Delete</n-button>
                        </n-flex>
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
