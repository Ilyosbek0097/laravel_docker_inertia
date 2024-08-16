<script>
import {router, usePage} from "@inertiajs/vue3";
import {ArrowUndoCircleSharp as BackIcon} from '@vicons/ionicons5'
import MyLayout from "@/Layouts/MyLayout.vue";
import {defineComponent, ref} from "vue";

export default defineComponent({
    props: {
      role: Array,
    },
    components: {
        MyLayout,
        BackIcon,
    },
    setup(){
        const GoBack = () => {
            router.get(route('role.index'));
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
                <tr v-if="role">
                    <th>ID</th>
                    <td>{{ role.id }}</td>
                </tr>
                <tr v-if="role">
                    <th>Name</th>
                    <td>{{ role.name }}</td>
                </tr>
                <tr v-if="role">
                    <th>Guarded</th>
                    <td>{{ role.guard_name }}</td>
                </tr>
                <tr>
                    <th>Permissions</th>
                    <td>
                        {{ permissions }}
                    </td>
                </tr>
                </tbody>
            </n-table>
        </n-layout-content>
    </MyLayout>
</template>
