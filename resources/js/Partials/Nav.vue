<template>
    <div >
        <vs-navbar shadow fixed square center-collapsed paddingScroll>

            <template #left>
                <vs-button @click="activeSidebar = !activeSidebar" flat icon>
                    <i class='bx bx-menu'></i>
                </vs-button>
            </template>


            <vsm-menu
                :menu="header_menu"
                element="div"
                handler="hover"
                align="center"
                :screen-offset="100"
                :dropdown-offset="0"
            >
                <template #default="{ item }">

                    <div style="min-width: 300px" class="py-6 px-4">
                        <VsmMenuContent :data="item.children"></VsmMenuContent>
                    </div>
                </template>
                <template #title="data">
                    <span v-if="data.item.href && data.item.href!='#'" @click.stop="link($event)" method="get" :url="data.item.href"
                          class="text-gray-600 hover:text-gray-900 text-ssm font-bold cursor-pointer">
                        <i v-if="data.item.icon" class="bx" :class="data.item.icon"></i>&nbsp;{{ data.item.title }}<i v-if="data.item.children" class="bx bxs-down-arrow text-gray-400 text-xs pl-2"></i>
                    </span>
                    <span v-else class="text-gray-500 hover:text-gray-900 text-ssm font-bold cursor-pointer">
                        <i v-if="data.item.icon" class="bx" :class="data.item.icon"></i>&nbsp;{{ data.item.title }}<i v-if="data.item.children" class="bx bxs-down-arrow text-gray-400 text-xs pl-2"></i>
                    </span>
                </template>
            </vsm-menu>


            <template v-if="$page.props.canLogin" #right>
                <template v-if="$page.props.user">
                    <div class="relative flex flex-row-reverse gap-4">
                        <popper
                            trigger="clickToToggle"
                            :options="{
                                  placement: 'top',
                                  modifiers: { offset: { offset: '0,10px' } }
                                }">
                            <div class="bg-white popper rounded-lg shadow-2xl flex flex-col gap-4 p-4 divide-y-2 divide-gray-100">
                                <div class="flex flex-col justify-center items-center gap-2 mb-4">
                                    <vs-avatar circle class="cursor-pointer" size="60">
                                        <img :src="$page.props.user.profile_photo_url" alt="">
                                    </vs-avatar>
                                    <span class="text-gray-600 text-xs">{{$page.props.user.name}}</span>
                                </div>
                                <div>
                                    <vs-button @click="link($event)" :url="route('dashboard')" method="get"
                                               transparent block dark>
                                        <div class="w-full flex flex-row justify-start items-center gap-3">
                                            <i class='bx bxs-dashboard' ></i>
                                            <span>Dashboard</span>
                                        </div>
                                    </vs-button>
                                </div>
                                <div>
                                    <vs-button @click="link($event)" :url="route('dashboard.user.profile')" method="get"
                                               transparent block dark>
                                        <div class="w-full flex flex-row justify-start items-center gap-3">
                                            <i class='bx bx-user' ></i>
                                            <span>Profile</span>
                                        </div>
                                    </vs-button>
                                </div>
                                <div>
                                    <vs-button @click="link($event)" :url="route('logout')" method="post"
                                               danger transparent block >
                                        <div class="w-full flex flex-row justify-start items-center gap-3">
                                            <i class='bx bx-log-out' ></i>
                                            <span>Logout</span>
                                        </div>
                                    </vs-button>
                                </div>
                            </div>
                            <div slot="reference">
                                <vs-avatar circle class="cursor-pointer" size="40">
                                    <img :src="$page.props.user.profile_photo_url" alt="">
                                </vs-avatar>
                            </div>
                        </popper>

                        <popper
                            trigger="clickToToggle"
                            @show="showNotifications=true"
                            @hide="showNotifications=false"
                            :options="{
                                  placement: 'top',
                                  modifiers: { offset: { offset: '0,10px' } }
                                }">
                            <div class="min-h-full  bg-white popper rounded-lg shadow-2xl flex flex-col gap-4 p-4 divide-y-2 divide-gray-100">
                                <div class="NotificationsConatiner w-96 min-h-full overflow-y-auto">
                                    <Notifications v-if="showNotifications"/>
                                </div>
                            </div>
                            <div slot="reference" class="cursor-pointer">
                                <vs-avatar badge-color="danger" badge-position="top-right">
                                    <i class='bx bx-bell cursor-pointer' ></i>

                                    <template v-if="$page.props.user.new_notifications_count" #badge>
                                        {{ $page.props.user.new_notifications_count }}
                                    </template>
                                </vs-avatar>
                            </div>
                        </popper>
                    </div>
                </template>

                <template v-else>
                    <vs-button flat @click="link($event)"
                               :url="route('login')"
                               method="get" >Login</vs-button>
                    <vs-button @click="link($event)"
                               :url="route('register')"
                               method="get">Register</vs-button>
                </template>
            </template>
        </vs-navbar>

        <Sidebar v-model="activeSidebar" :data="mobile_menu"></Sidebar>

    </div>
</template>
<script>
import VsmMenuContent from "@/Partials/VsmMenuContent";
import Sidebar from "@/Partials/Sidebar/Sidebar";
import Popper from 'vue-popperjs';
import Notifications from "@/Pages/Dashboard/User/Notifications/Components/Notifications";

export default {
    components: {
        VsmMenuContent,
        Sidebar,
        Popper,
        Notifications
    },
    props: {
        site_menus: Object,
        site_config: Object
    },
    data:() => ({
        activeSidebar: false,
        header_menu: [],
        mobile_menu: [],
        showNotifications: false
    }),
    methods: {

    },
    beforeMount() {
        let convertMenu = function (obj) {
            return obj.map(function (item) {
                let tmp = {};
                if(item.children !== undefined && item.children.length ) {
                    tmp['dropdown'] = "item" + item.id;
                    tmp['children'] = convertMenu(item.children);
                }
                tmp['title'] = item.text;
                tmp['href'] = item.href;
                tmp['id'] = item.id;
                tmp['icon'] = item.icon;
                return tmp;
            });
        }
        this.header_menu = convertMenu(this.$page.props.site_menus.header_menu);
        this.mobile_menu = convertMenu(this.$page.props.site_menus.mobile_menu);
    }
}
</script>


<style>
.NotificationsConatiner{
    min-height: 200px;
    max-height: 500px;
}
.popper {
    width: auto;
    display: inline-block;
    position: absolute;
    z-index: 200000;
}
.popper .popper__arrow {
    width: 0;
    height: 0;
    border-style: solid;
    position: absolute;
    margin: 5px;
}
.popper[x-placement^="top"] {
    margin-bottom: 5px;
}
.popper[x-placement^="top"] .popper__arrow {
    border-width: 5px 5px 0 5px;
    border-color: #fafafa transparent transparent transparent;
    bottom: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
}
.popper[x-placement^="bottom"] {
    margin-top: 5px;
}
.popper[x-placement^="bottom"] .popper__arrow {
    border-width: 0 5px 5px 5px;
    border-color: transparent transparent #fafafa transparent;
    top: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
}
.popper[x-placement^="right"] {
    margin-left: 5px;
}
.popper[x-placement^="right"] .popper__arrow {
    border-width: 5px 5px 5px 0;
    border-color: transparent #fafafa transparent transparent;
    left: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
}
.popper[x-placement^="left"] {
    margin-right: 5px;
}
.popper[x-placement^="left"] .popper__arrow {
    border-width: 5px 0 5px 5px;
    border-color: transparent transparent transparent #fafafa;
    right: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
}

</style>
<style lang="scss">
.vsm-section_menu a{
    display: flex;
    align-items: center;
}
.vsm-menu {
    ul {
        max-width: 1024px;
        margin: 0 auto;
    }
}
.vsm-root {
    display: flex;
    align-items: center;
    justify-content: center;
}
.logo-section {
    flex: 1 1 auto;
    img {
        user-select: none;
        max-width: 40px;
    }
}

.vsm-section_menu {
    > * {
        padding: 0 25px;
        font-weight: 500;
        font-family: inherit;
    }
}
.wrap-content {
    padding: 30px;
    width: 100%;
}
.wrap-content__block {
    font-weight: bold;
}
.wrap-content__item {
    font-style: italic;
    font-size: .8rem;
}
.vsm-background {
    background: #ffffff82;
    backdrop-filter: blur(30px);
    border-radius: 1em !important;
}

</style>
