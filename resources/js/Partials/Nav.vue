<template>
    <div >
        <vs-navbar shadow fixed  square center-collapsed paddingScroll  v-model="active">
            <template #left>
                <vs-button @click="activeSidebar = !activeSidebar" flat icon>
                    <i class='bx bx-menu'></i>
                </vs-button>
            </template>


            <vsm-menu
                :menu="header_menu"
                element="header"
                handler="hover"
                align="center"
                :screen-offset="10"
                :dropdown-offset="0"
            >
                <template #default="{ item }">
                    <div style="width: 300px; padding: 30px">
                        Header: {{ item }}
                    </div>
                    <div style="padding: 30px">
                        Second element
                    </div>
                </template>
                <template #title="data">
                    <span v-if="data.item.href && data.item.href!='#'" @click.stop="link($event)" method="get" :url="data.item.href" class="text-gray-600 hover:text-gray-900 text-md cursor-pointer">
                        {{ data.item.title }}<i class="bx bxs-down-arrow text-xs pl-2"></i>
                    </span>
                    <span v-else class="text-gray-600 hover:text-gray-900 text-md cursor-pointer">
                        {{ data.item.title }}
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
        <vs-sidebar
            fixed
            v-model="active"
            :open.sync="activeSidebar"
        >
            <template #logo>
                <!-- ...img logo -->
            </template>
            <vs-sidebar-item id="home">
                <template #icon>
                    <i class='bx bx-home'></i>
                </template>
                Home
            </vs-sidebar-item>
            <vs-sidebar-item id="market">
                <template #icon>
                    <i class='bx bx-grid-alt'></i>
                </template>
                Market Overview
            </vs-sidebar-item>
            <vs-sidebar-item id="Music">
                <template #icon>
                    <i class='bx bxs-music'></i>
                </template>
                Music
            </vs-sidebar-item>
            <vs-sidebar-group>
                <template #header>
                    <vs-sidebar-item arrow>
                        <template #icon>
                            <i class='bx bx-group'></i>
                        </template>
                        Social media
                    </vs-sidebar-item>
                </template>

                <vs-sidebar-item id="Instagram">
                    <template #icon>
                        <i class='bx bxl-instagram'></i>
                    </template>
                    Instagram
                </vs-sidebar-item>
                <vs-sidebar-item id="twitter">
                    <template #icon>
                        <i class='bx bxl-twitter' ></i>
                    </template>
                    Twitter
                </vs-sidebar-item>
                <vs-sidebar-item id="Facebook">
                    <template #icon>
                        <i class='bx bxl-facebook' ></i>
                    </template>
                    Facebook
                </vs-sidebar-item>
            </vs-sidebar-group>
            <vs-sidebar-group>
                <template #header>
                    <vs-sidebar-item arrow>
                        <template #icon>
                            <i class='bx bx-code-alt' ></i>
                        </template>
                        Coding
                    </vs-sidebar-item>
                </template>

                <vs-sidebar-item id="github">
                    <template #icon>
                        <i class='bx bxl-github' ></i>
                    </template>
                    Github
                </vs-sidebar-item>
                <vs-sidebar-item id="codepen">
                    <template #icon>
                        <i class='bx bxl-codepen'></i>
                    </template>
                    Codepen
                </vs-sidebar-item>
                <vs-sidebar-item id="discord">
                    <template #icon>
                        <i class='bx bxl-discord'></i>
                    </template>
                    Discord
                </vs-sidebar-item>
                <vs-sidebar-item id="Javascript">
                    <template #icon>
                        <i class='bx bxl-javascript' ></i>
                    </template>
                    Javascript
                </vs-sidebar-item>
                <vs-sidebar-item id="git">
                    <template #icon>
                        <i class='bx bxl-git' ></i>
                    </template>
                    Git
                </vs-sidebar-item>
            </vs-sidebar-group>
            <vs-sidebar-item id="donate">
                <template #icon>
                    <i class='bx bxs-donate-heart' ></i>
                </template>
                Donate
            </vs-sidebar-item>
            <vs-sidebar-item id="drink">
                <template #icon>
                    <i class='bx bx-drink'></i>
                </template>
                Drink
            </vs-sidebar-item>
            <vs-sidebar-item id="shopping">
                <template #icon>
                    <i class='bx bxs-shopping-bags'></i>
                </template>
                Shopping
            </vs-sidebar-item>
            <vs-sidebar-item id="chat">
                <template #icon>
                    <i class='bx bx-chat' ></i>
                </template>
                Chat
            </vs-sidebar-item>
            <template #footer>
                <vs-row justify="space-between">
                    <vs-avatar>
                        <img src="/uploads/avatars/avatar-5.png" alt="">
                    </vs-avatar>

                    <vs-avatar badge-color="danger" badge-position="top-right">
                        <i class='bx bx-bell' ></i>

                        <template #badge>
                            28
                        </template>
                    </vs-avatar>
                </vs-row>
            </template>
        </vs-sidebar>

    </div>
</template>
<script>
export default {
    props: {
        site_menus: Object,
        site_config: Object
    },
    data:() => ({
        active: '',
        activeSidebar: false,
        header_menu: [],
    }),
    beforeMount() {
        let convertMenu = function (obj) {
            return obj.map(function (item) {
                let tmp = {};
                if(item.children && item.children.length) {
                    tmp['dropdown'] = "item"+item.id;
                    tmp['children'] = item.children;
                    tmp['href'] = item.href;
                    tmp['id'] = item.id;
                    convertMenu(item.children);
                }
                tmp['title'] = item.text;
                return tmp;
            });
        }
        this.header_menu = convertMenu(this.$page.props.site_menus.header_menu);

        console.log(this.header_menu);
    },
    setup() {
        alert()
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
}
</style>
