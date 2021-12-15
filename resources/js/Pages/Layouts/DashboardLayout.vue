<template>
    <div class="pt-10 min-h-screen">

        <vs-navbar class="z-10" color="#101827"  fixed text-white square center-collapsed v-model="active">
            <template #left>

                <span class="md:hidden" >
                    <vs-button @click="activeSidebar = !activeSidebar" flat icon>
                        <i class='bx bx-menu'></i>
                    </vs-button>
                </span>

                <vs-button color="#fff" @click="link($event)"
                           :url="route('index')"
                           method="get" flat circle  >
                    <i class='bx bx-home-alt'></i>
                </vs-button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <vs-navbar-group>
                    <i class='bx bx-add-to-queue'></i> New
                    <template #items>
                        <vs-navbar-item :active="active == 'Post'" id="Post">
                            Post
                        </vs-navbar-item>
                        <vs-navbar-item :active="active == 'Page'" id="Page">
                            Page
                        </vs-navbar-item>
                        <vs-navbar-item :active="active == 'Video'" id="Video">
                            Video
                        </vs-navbar-item>
                        <vs-navbar-item :active="active == 'Media'" id="Media">
                            Media
                        </vs-navbar-item>
                    </template>
                </vs-navbar-group>
            </template>

            <template #right>
                <vs-tooltip interactivity left bottom>
                    <vs-avatar circle size="30">
                        <img :src="$page.props.user.profile_photo_url" alt="">
                    </vs-avatar>
                    <template #tooltip>
                        <div class="content-tooltip">
                            <div class="body">
                                <div class="text">
                                    Cosed Tasks
                                    <span>
                                89
                                </span>
                                </div>
                                <vs-avatar circle size="80" @click="activeTooltip1=!activeTooltip1">
                                    <img :src="$page.props.user.profile_photo_url" alt="">
                                </vs-avatar>
                                <div class="text">
                                    Open Tasks
                                    <span>
                                8
                                </span>
                                </div>
                            </div>
                            <footer>
                                <vs-button circle icon border>
                                    <i class='bx bxs-share-alt'></i>
                                </vs-button>
                                <vs-button circle>
                                    Message
                                </vs-button>
                                <vs-button circle icon border>
                                    <i class='bx bx-like' ></i>
                                </vs-button>
                            </footer>
                        </div>
                    </template>
                </vs-tooltip>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <vs-button
                    danger
                    color="#FFF"
                    transparent
                    animation-type="scale"
                    @click="link($event)"
                    :url="route('logout')"
                    method="post" >
                    Logout
                    <template #animate >
                        <i class='bx bx-log-out' ></i>
                    </template>
                </vs-button>

            </template>
        </vs-navbar>

        <div class="w-full">
            <div class="flex flex-row justify-between">
                <aside :style="{width: sideBarWidht+'px'}" class="sticky top-10 w-full sm:w-0 md:w-3/12 sm:relative relative z-0">
                    <div class="w-full h-full">
                        <vs-sidebar
                            id="sideBar"
                            ref="sideBar"
                            :class="{'open': openSideBar}"
                            class="pt-14"
                            :hover-expand="hover_expand"
                            :reduce="hover_expand"
                            background="#101827"
                            v-model="active"
                            square
                            textWhite
                            open
                            :open.sync="activeSidebar"
                        >
                            <template #logo>
                                <vs-avatar dark color="#FFF" circle size="32">
                                    <i class="bx bx-atom"></i>
                                </vs-avatar>
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
                            <template #footer>
                                <vs-row justify="space-between" class="flex justify-between">
                                    <vs-avatar badge-color="danger" dark color="#FFF" badge-position="top-right">
                                        <img :src="$page.props.user.profile_photo_url" alt="">
                                        <template #badge>
                                            28
                                        </template>
                                    </vs-avatar>

                                    <vs-button-group class="sideBarUserActions mt-2">
                                        <vs-button color="#000" >
                                            <i class="bx bx-user"></i>
                                        </vs-button>
                                        <vs-button color="#000" >
                                            <i class='bx bx-bell' ></i>
                                        </vs-button>
                                        <vs-button danger>
                                            <i class="bx bx-log-out"></i>
                                        </vs-button>
                                    </vs-button-group>
                                </vs-row>
                            </template>
                        </vs-sidebar>
                    </div>
                </aside>
                <main :style="{width: mainContentWidht+'px'}" ref="mainContent" id="mainContent" role="main" class="w-full sm:w-3/3 md:w-9/12 p-4 break-words bg-gray-200">
                    <slot/>
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sideBarUserActions {
    transition: all 1s !important;
}
#sideBar.reduce .sideBarUserActions{
    width: 0 !important;
    height: 0 !important;
    transform: scale(0) !important;
    transition: all 1s !important;
}
.vs-tooltip-content{

    width: auto;
    display: inline-block;
    position: relative;

}
.content-tooltip .body {
    display: flex;
    align-items: flex-start;
    justify-content: center;
}
.content-tooltip .body .vs-avatar-content {
    margin-top: -30px;
    border: 3px solid var(--vs-theme-layout);
    box-shadow: 0px 4px 15px 0px rgba(0,0,0,0.1);
}
.content-tooltip .body .text {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    font-size: 0.55rem;
    padding: 10px;
    font-weight: normal;
}
.content-tooltip .body .text span {
    font-weight: bold;
    font-size: 0.7rem;
}
.content-tooltip footer {
    display: flex;
    align-items: center;
    justify-content: center;
}
.content-tooltip h4 {
    padding: 8px;
    margin: 0px;
    text-align: left;
}
.content-tooltip p {
    text-align: left;
    padding: 0px;
    margin: 0px;
    line-height: 1rem;
    padding-bottom: 5px;
    padding-left: 8px;
}
.content-tooltip{
    color: #FFF;
    box-shadow: 0 32px 34px #10182747;
    border: 1px solid #0d2756;
    background: #101827;
    border-radius: 15px;
    padding-bottom: 8px;
    margin-top: -13px;
}
</style>


<script>
import DashboardNav from "@/Partials/DashboardNav";
import DashboardSideBar from "@/Partials/DashboardSideBar";
export default {
    name: "Layout2",
    components: {
        DashboardNav,DashboardSideBar
    },
    data: () => ({
        hover_expand: false,
        active: 'home',
        activeSidebar: true,
        openSideBar: true,
        mainContentWidht: 500,
        sideBarWidht: 200
    }),
    mounted() {
        let that = this
        function reportWindowSize() {
            let window_width = window.innerWidth;
            let sideBar_width = that.$refs.sideBar.$el.clientWidth;
            if(window_width < 768) {
                that.hover_expand = false;
                that.sideBarWidht = 0;
                that.mainContentWidht = window_width;
                that.activeSidebar = false;
                that.openSideBar = false;
            }
            else if(window_width < 900) {
                that.hover_expand = true;
                that.sideBarWidht = 50;
                that.mainContentWidht = window_width - 50;
                // that.activeSidebar = true;
                that.openSideBar = true;
            }
            else {
                that.hover_expand = false;
                that.sideBarWidht = sideBar_width;
                that.mainContentWidht = window_width - sideBar_width;
                // that.activeSidebar = true;
                that.openSideBar = true;
            }
        }
        window.onresize = reportWindowSize;
        reportWindowSize()
    }
}
</script>

<style scoped>
</style>
