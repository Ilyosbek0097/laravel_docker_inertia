<script>
import {NConfigProvider, useMessage} from 'naive-ui'
import {defineComponent, h, ref} from "vue";
import {darkTheme} from "naive-ui";
import Navbar from "@/Components/Naivei/Navbar.vue";
import { NIcon } from "naive-ui";
import {
    BookOutline as BookIcon,
    PersonOutline as PersonIcon,
    ShieldCheckmarkOutline as PermissionIcon,
    SettingsOutline as RoleIcon,
    Pencil as EditIcon,
    LogOutOutline as LogoutIcon,
    PersonCircleOutline as UserIcon,
    ChatboxEllipsesSharp as ChatIcon,
    WineOutline as WineIcon,
} from "@vicons/ionicons5";
import Logo from "@/Shared/Logo.vue";
// import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ApplicationLogo from "@/Components/Naivei/ApplicationLogo.vue";
import {Link, router, usePage} from "@inertiajs/vue3";



export default defineComponent({
    components: {Link, ApplicationLogo, Logo, Navbar},
    props: {
    },
    setup(props) {
        const {auth} = usePage().props;
        const user = auth.user;

        const renderIcon = (icon) => {
            return () => h(NIcon, null, { default: () => h(icon) });
        }

        const menuOptions = [
            {
                label: "Users",
                key: "users",
                icon: renderIcon(PersonIcon)
            },
            {
                label: "Roles",
                key: "roles",
                icon: renderIcon(RoleIcon)
            },
            {
                label: "Permissions",
                key: 'permissions',
                icon: renderIcon(PermissionIcon)
            },
            {
                label: "Chat",
                key: 'chat',
                icon: renderIcon(ChatIcon)
            }
        ];
        const  options = [
            {
                label: () => h(
                    'div',
                    [
                        h('span', { style: 'color: #007BFF; font-weight: bold;' }, user ? user.name : 'Guest'), // Username in bold and blue color
                    ]
                ),
            },
            {
                label: 'Profile',
                key: 'profile',
                icon: renderIcon(UserIcon)
            },
            {
                label: 'Edit Profile',
                key: 'editProfile',
                icon: renderIcon(EditIcon)
            },
            {
                label: 'Logout',
                key: 'logout',
                icon: renderIcon(LogoutIcon)
            }
        ];
        const handleMenuClick = (key) => {
            switch (key) {
                case 'users':
                    router.visit(route('user.index'))
                    break;
                case 'roles':
                   router.visit(route('role.index'))
                    break;
                case 'permissions':
                    router.visit(route('permission.index'))
                    break;
                case 'chat':
                    router.visit(route('chat.index'))
                    break;
            }
        };
        const handleMenuUserClick = (key) => {
          switch (key) {
              case 'profile':
                  router.visit(route('dashboard'));
                  break;
              case 'editProfile':
                  router.visit(route('profile.edit'));
                  break;
              case 'logout':
                  router.post(route('logout'));
                break;
          }
        };

        return {
            darkTheme,
            inverted: ref(false),
            menuOptions,
            options,
            renderIcon,
            theme: ref(null),
            handleMenuClick,
            handleMenuUserClick,
        }
    },
})



// ...
</script>

<template>
    <n-config-provider :theme="theme" style="height: 100vh;">
        <n-card>
            <n-layout-header>
                <n-space justify="space-between" align="center" style="width: 100%;">
                    <!-- Chap tomonda logotip -->
                    <Link href="/">
                        <ApplicationLogo class="w-30 h-10  fill-current text-gray-500" />
                    </Link>
                    <!-- O'ng tomonda menyular -->
                    <n-space>
                        <n-space justify="center">
                            <n-button @click="theme = darkTheme">Dark</n-button>
                            <n-button @click="theme = null">Light</n-button>
                            <!--                        <n-switch/>-->
                        </n-space>
                        <n-space>
                            <n-dropdown :options="options" @select="handleMenuUserClick">
                                <n-avatar
                                        round
                                        :size="36"
                                        src="https://cdn.pixabay.com/photo/2024/03/06/17/30/ai-generated-8616849_960_720.jpg"
                                    />
                            </n-dropdown>
                        </n-space>
                    </n-space>
                </n-space>
            </n-layout-header>
        </n-card>
        <n-space vertical style="max-height: 1000px" >
<!--            <n-space>-->
<!--                <n-switch v-model:value="inverted" />inverted-->
<!--            </n-space>-->
            <n-layout has-sider>
                <n-layout-sider
                    bordered
                    collapse-mode="width"
                    :collapsed-width="64"
                    :width="240"
                    show-trigger
                    :inverted="inverted"
                >
                    <n-menu
                        :inverted="inverted"
                        :collapsed-width="64"
                        :collapsed-icon-size="22"
                        :options="menuOptions"
                        @update:value="handleMenuClick"
                    />


                </n-layout-sider>
                <n-layout-content>
                    <slot />
                </n-layout-content>
            </n-layout>
        </n-space>

    </n-config-provider>
</template>
