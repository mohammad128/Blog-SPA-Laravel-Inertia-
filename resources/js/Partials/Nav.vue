<template>
    <div >
        <vs-navbar shadow fixed  square center-collapsed paddingScroll  v-model="active">
            <template #left>
                <vs-button @click="activeSidebar = !activeSidebar" flat icon>
                    <i class='bx bx-menu'></i>
                </vs-button>
            </template>
            <vs-navbar-item :active="active == 'Home'" id="Home" @click="link($event)" :url="route('index')" method="get">
                Home
            </vs-navbar-item>
            <vs-navbar-item :active="active == 'Post'" id="Posts" @click="link($event)" :url="route('Post.index')" method="get">
                Posts
            </vs-navbar-item>
            <vs-navbar-item :active="active == 'Videos'" id="Videos">
                Videos
            </vs-navbar-item>
            <vs-navbar-item :active="active == 'About'" id="About">
                About
            </vs-navbar-item>
            <vs-navbar-item :active="active == 'Contact'" id="Contact">
                Contact
            </vs-navbar-item>
            <template v-if="$page.props.canLogin" #right>
<!--                        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">-->
<!--                            <inertia-link v-if="$page.props.user" href="/dashboard" class="text-sm text-gray-700 underline">-->
<!--                                Dashboard-->
<!--                            </inertia-link>-->

<!--                            <template v-else>-->
<!--                                <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">-->
<!--                                    Login-->
<!--                                </inertia-link>-->

<!--                                <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">-->
<!--                                    Register-->
<!--                                </inertia-link>-->
<!--                            </template>-->
<!--                        </div>-->
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
    data:() => ({
        active: '',
        activeSidebar: false
    }),
    mounted() {
    },
    setup() {
        alert()
    }
}
</script>


