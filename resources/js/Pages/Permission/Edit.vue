<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import { ArrowUndoCircleSharp as BackIcon } from '@vicons/ionicons5';
import { router, useForm } from "@inertiajs/vue3";
import { defineComponent, ref, watch } from 'vue';
import { useMessage } from "naive-ui";

export default defineComponent({
    components: {
        MyLayout,
        BackIcon
    },
    props: {
        permission: Object
    },
    setup(props) {
        const message = useMessage();
        const form = useForm({
            name: props.permission.name || ''
        });
        const formRef = ref(null);
        const isDisable = ref(true);

        // Validatsiya funksiyasi
        const validateForm = () => {
            formRef.value?.validate((errors) => {
                isDisable.value = !!errors;
            });
        };
        watch(form, validateForm, { deep: true });

        // Validatsiya qoidalari
        const rules = {
            name: {
                required: true,
                message: "Please input your name",
                trigger: ["blur", "input"]
            }
        };

        // Go Back funksiyasi
        const GoBack = () => {
            router.get(route('permission.index'));
        };

        // Formani yuborish funksiyasi
        const submit = (e) => {
            e.preventDefault();
            formRef.value?.validate((errors) => {
                if (!errors) {
                    router.put(route('permission.update', props.permission.id), formRef.value.model);
                } else {
                    console.log('Validation errors:', errors);
                    message.error("Invalid form input");
                }
            });
        };


        // Form ma'lumotlarini kuzatish


        return {
            GoBack,
            form,
            formRef,
            router,
            submit,
            rules,
            isDisable,
            message,
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
                        <BackIcon/>
                    </n-icon>
                    Go Back
                </n-button>
            </n-space>
            <n-form
                ref="formRef"
                :rules="rules"
                :model="form"
            >
                <n-form-item label="Permission Edit Name" path="name" class="mb-4">
                    <n-input
                        v-model:value="form.name"
                        placeholder="Input Name"
                    />
                </n-form-item>
                <n-form-item>
                    <n-button type="primary" @click="submit" :disabled="isDisable">
                        Submit
                    </n-button>
                </n-form-item>
            </n-form>
        </n-layout-content>
    </MyLayout>
</template>
