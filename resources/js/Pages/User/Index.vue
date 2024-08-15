<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import { defineComponent, h, ref } from 'vue';
import { darkTheme, NIcon } from 'naive-ui';
import { AddCircleSharp as Add } from '@vicons/ionicons5';
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { useMessage } from "naive-ui";


export default defineComponent({
    props: {
        users: Object,
        userRoles: Object,
        flash: Object,
        permissions: Object,
    },

    components: {
        MyLayout,
        Add,
    },
    setup(props) {
        // const { hasPermission } = usePermissions();
        const message = useMessage();
        const showModalRef = ref(false);
        const selectedUserId = ref(null);



        const pageProps = usePage().props;
        const permissions = ref(pageProps.user ? pageProps.user.permissions : []);
        const hasPermission = (permission) => {
            return permissions.value.includes(permission);
        };




        const { flash } = usePage().props;

        const CreateUser = () => {
            router.get(route('user.create'));
        };

        const ShowUser = (id) => {
            router.get(route('user.show', { user: id }));
        };

        const EditUser = (id) => {
            try {
                router.get(route('user.edit', { user: id }));
            } catch (e){
                console.log(error)
            }


        };

        const DeleteUser = () => {
            try {
                // HTTP so'rov yuborish
                 router.delete(route('user.destroy', { user: selectedUserId.value }));
                // message.success('User deleted successfully.');
                showModalRef.value = false;
            } catch (error) {
                // message.error('Failed to delete user.');
                console.log(error)
            }
        };

        const openDeleteModal = (id) => {
            selectedUserId.value = id; // O'chirilishi kerak bo'lgan foydalanuvchini saqlash
            showModalRef.value = true;
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
            permissions,
            hasPermission,
            flash,
            message,
            showModal: showModalRef,
            onPositiveClick() {
                DeleteUser();  // Tasdiqlash tugmasi bosilganda foydalanuvchini o'chirish
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
                <n-button  :disabled="!hasPermission('User Create')" type="primary" size="large" @click="CreateUser">
                    <n-icon size="30"><Add /></n-icon> Add User
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
<!--            {{ $page.props }}-->
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
<!--                    <pre>-->
<!--                        {{ JSON.stringify($page.props.permissions, null, 2)}}-->
<!--                    </pre>-->
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            <n-space>
                                <n-badge  type="info"  v-if="user.roles.length > 0" v-for="role in user.roles" :key="role" :value="role" :max="15" />
                            </n-space>
                        </td>
                        <td>
                            <n-space>
                                <n-button :disabled="!hasPermission('User Show')" type="info" class="mr-2" @click="ShowUser(user.id)">Show</n-button>
                                <n-button :disabled="!hasPermission('User Edit')" type="primary" class="mr-2" @click="EditUser(user.id)">Edit</n-button>
                                <n-button :disabled="!hasPermission('User Delete')" type="error" @click="openDeleteModal(user.id)">Delete</n-button>
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
