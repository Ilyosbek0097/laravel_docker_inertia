<script>
import MyLayout from "@/Layouts/MyLayout.vue";
import {defineComponent, ref, watch} from "vue";
import {ArrowUndoCircleSharp as BackIcon} from '@vicons/ionicons5'
import {router} from "@inertiajs/vue3";
import {useMessage} from "naive-ui";

export default defineComponent({
    props: {
        errors: Object,
        success: Object,
        roles: Object,
    },
    components: {
        MyLayout,
        BackIcon,
    },
    setup(props) {
        // console.log(props.roles)
        const message = useMessage();
        const formRef = ref(null);
        const GoBack = () => {
            router.get(route('user.index'));
        };
        const isSubmitDisabled = ref(true);
        const validateForm = () => {
            formRef.value?.validate((errors) => {
                isSubmitDisabled.value = !!errors;
            });
        };

        const formValue = ref({
            name: "",
            email: "",
            rolesValue: "",
            password: "",
            password_confirmation: "",

        });

        watch(formValue, validateForm, { deep: true });

        return {
            formRef,
            GoBack,
            isSubmitDisabled,
            formValue,
            message,
            generalOptions: props.roles.map(
                (role) => ({
                    label: role.name,
                    value: role.name
                })
            ),
            rules: {
                    name: {
                        required: true,
                        message: "Please input your name",
                        trigger: "blur"
                    },
                    email: {
                        required: true,
                        message: "Please input your email",
                        trigger: "blur",
                        type: "email",
                    },
                    password: {
                        required: true,
                        message: "Please input your password",
                        trigger: "blur",
                    },
                    rolesValue: {
                        type: "array",
                        required: true,
                        trigger: ["blur", "change"],
                        message: "Please select rolesValue"
                    },
                    password_confirmation: {
                        required: true,
                        message: "Please confirm your password",
                        trigger: "blur",
                        validator(rule, value) {
                            if (!value) {
                                return new Error("Password confirmation cannot be empty");
                            } else if (value !== formRef.value.model.password) {
                                return new Error("Passwords do not match");
                            }
                            return true;
                        }

                    },
            },
            handleValidateClick(e) {
                e.preventDefault();
                formRef.value?.validate((errors) => {
                    if (!errors) {
                        router.post(route('user.store'),  formRef.value.model)
                    } else {
                        console.log('Validation errors:', errors)
                       message.error("Invalid");
                    }
                });
            }
        }
    }
})
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
            <n-form
                ref="formRef"

                :label-width="80"
                :model="formValue"
                :rules="rules"
                :size="'medium'">

                <n-form-item label="Name" path="name" class="mb-4">
                    <n-input v-model:value="formValue.name" placeholder="Input Name"/>
                </n-form-item>
                <n-form-item label="Email" path="email"  class="mb-4">
                    <n-input  v-model:value="formValue.email" placeholder="Input Email"/>
                </n-form-item>
                <n-form-item class="mb-4"
                    :span="12"
                    label="Multiple Select"
                    path="rolesValue"
                >
                    <n-select
                        v-model:value="formValue.rolesValue"
                        placeholder="Select"
                        :options="generalOptions"
                        multiple
                    />
                </n-form-item>
                <n-form-item label="Password" path="password" class="mb-4 mt-4">
                    <n-input type="password" v-model:value="formValue.password" placeholder="Input Password"/>
                </n-form-item>
                <n-form-item label="Password" path="password_confirmation" class="mb-4">
                    <n-input type="password" v-model:value="formValue.password_confirmation" placeholder="Input Password Confirm"/>
                </n-form-item>
                <n-form-item>
                    <n-button @click="handleValidateClick" :disabled="isSubmitDisabled">
                        Submit
                    </n-button>
                </n-form-item>
            </n-form>
        </n-layout-content>
    </MyLayout>
</template>
