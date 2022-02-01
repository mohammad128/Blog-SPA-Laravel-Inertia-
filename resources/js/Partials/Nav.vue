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
                    <vs-button @click="link($event)"
                               :url="route('dashboard')"
                               method="get" flat >
                        Dashboard
                    </vs-button>
                    <vs-button gradient danger
                       @click="link($event)"
                        :url="route('logout')"
                        method="post" >
                        Logout
                        <i class='bx bx-log-out' ></i>
                    </vs-button>
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

export default {
    components: {
        VsmMenuContent,
        Sidebar,
    },
    props: {
        site_menus: Object,
        site_config: Object
    },
    data:() => ({
        activeSidebar: false,
        header_menu: [],
        mobile_menu: [],
    }),
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
        console.log('MobileMenu', this.mobile_menu);
    }
}
</script>



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
