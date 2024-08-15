<script>
import {router} from "@inertiajs/vue3";
import {ArrowUndoCircleSharp as BackIcon} from '@vicons/ionicons5'
import MyLayout from "@/Layouts/MyLayout.vue";
import {defineComponent} from "vue";

export default defineComponent({
    props: {
      user: Object,
    },
    components: {
        MyLayout,
        BackIcon,
    },
    setup(){
        const GoBack = () => {
            router.get(route('user.index'));
        };
        return{
            router,
            GoBack,
        }
    }
});


</script>

<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height:700px;">
            <n-space justify="end" class="mb-4">
                <n-button type="primary" size="large" @click="GoBack">
                    <n-icon size="30">
                        <BackIcon/>
                    </n-icon>
                    Go Back
                </n-button>
            </n-space>
            <n-space vertical :size="12">

            </n-space>
            <n-table :bordered="false" :single-line="false">
                <tbody>
                <tr v-if="user">
                    <th>ID</th>
                    <td>{{ user.id }}</td>
                </tr>
                <tr v-if="user">
                    <th>Name</th>
                    <td>{{ user.name }}</td>
                </tr>
                <tr v-if="user">
                    <th>Email</th>
                    <td>{{ user.email }}</td>
                </tr>
                <tr v-if="user">
                    <th>Role</th>
                    <td>...</td>
                </tr>
                </tbody>
            </n-table>
        </n-layout-content>
    </MyLayout>
</template>
