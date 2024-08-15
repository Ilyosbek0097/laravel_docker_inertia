<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import { defineComponent, ref, watch } from 'vue';
import { ArrowUndoCircleSharp as BackIcon } from '@vicons/ionicons5';
import {router, useForm} from "@inertiajs/vue3";
import { useMessage } from 'naive-ui';

export default defineComponent({
    props: {
      role: Object
    },
    components: {
        MyLayout,
        BackIcon
    },
    setup(props) {
        const formRef = ref(null);
        const message = useMessage();
        const GoBack = () => {
            router.get(route('role.index'));
        };
        const isSubmitDisabled = ref(true);

        const validateForm = () => {
            formRef.value?.validate((errors) => {
                isSubmitDisabled.value = !!errors;
            });
        };
        const formValue = useForm({
            name: props.role.name || ""
        });

        watch(formValue, validateForm, { deep: true });

        const rules = {
            name: {
                required: true,
                message: "Please input your name",
                trigger: "blur"
            }
        };

        const handleValidateClick = (e) => {
            e.preventDefault();
            formRef.value?.validate((errors) => {
                if (!errors) {
                    router.put(route('role.update', props.role.id), formRef.value.model);
                } else {
                    console.log('Validation errors:', errors);
                    message.error("Invalid form input");
                }
            });
        };

        return {
            formRef,
            GoBack,
            isSubmitDisabled,
            formValue,
            rules,
            message,
            router,
            handleValidateClick
        };
    }
});
</script>

<template>
    <MyLayout>
        <n-layout-content content-style="padding: 30px; height: 700px;">
            <n-space justify="end" class="mb-4">
                <n-button type="primary" size="large" @click="GoBack">
                    <n-icon size="30">
                        <BackIcon />
                    </n-icon>
                    Go Back
                </n-button>
            </n-space>
            <n-form
                ref="formRef"
                autocomplete="off"
                :label-width="80"
                :model="formValue"
                :rules="rules"
                :size="'medium'"
               >
                <n-form-item label="Name" path="name" class="mb-4">
                    <n-input v-model:value="formValue.name" placeholder="Input Name"/>
                </n-form-item>
                <n-form-item>
                    <n-button type="primary"  @click="handleValidateClick" :disabled="isSubmitDisabled">
                        Submit
                    </n-button>
                </n-form-item>
            </n-form>
        </n-layout-content>
    </MyLayout>
</template>
