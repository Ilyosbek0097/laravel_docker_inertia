<script>
import {router} from "@inertiajs/vue3";
import {ArrowUndoCircleSharp as BackIcon} from '@vicons/ionicons5'
import MyLayout from "@/Layouts/MyLayout.vue";
import {defineComponent} from "vue";

export default defineComponent({
    props: {
        permission: Object,
    },
    components: {
        MyLayout,
        BackIcon,
    },
    setup(){
        const GoBack = () => {
            router.get(route('permission.index'));
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
                <tr v-if="permission">
                    <th>ID</th>
                    <td>{{ permission.id }}</td>
                </tr>
                <tr v-if="permission">
                    <th>Name</th>
                    <td>{{ permission.name }}</td>
                </tr>
                <tr v-if="permission">
                    <th>Guarded</th>
                    <td>{{ permission.guard_name }}</td>
                </tr>
                </tbody>
            </n-table>
        </n-layout-content>
    </MyLayout>
</template>
