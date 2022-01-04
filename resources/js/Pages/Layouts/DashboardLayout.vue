<template>
    <div class="pt-10 min-h-screen bg-gray-200 ">

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

            <vs-tooltip interactivity left bottom>
                <vs-avatar circle size="40" badge-color="danger" badge-position="top-right">
                    <img :src="$page.props.user.profile_photo_url" alt="">
                    <template #badge>
                        28
                    </template>
                </vs-avatar>
                <template #tooltip>
                    <div class="content-tooltip">
                        <div class="body">
                            <div class="text">
                            </div>
                            <vs-avatar circle size="80" badge-color="danger" badge-position="top-right">
                                <img :src="$page.props.user.profile_photo_url" alt="">
                                <template #badge>
                                    28
                                </template>
                            </vs-avatar>
                            <div class="text">
                            </div>
                        </div>
                        <footer>
                            <vs-button circle icon border>
                                <i class="bx bx-user"></i>
                            </vs-button>
                            <vs-button @click="link($event)" method="post" :url="route('logout')"  danger circle>
                                Logout
                            </vs-button>
                            <vs-button circle icon border>
                                <i class='bx bx-bell' ></i>
                            </vs-button>
                        </footer>
                    </div>
                </template>
            </vs-tooltip>

            <template #right>
                <vs-button
                    danger flat
                    animation-type="scale"
                    class="font-bold"

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
                            v-model="$store.state.dashboard.activeSidebarItem"
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

                            <template v-for="item in sidebarItems" >
                                <template v-if="item.group">
                                    <vs-sidebar-group v-if="canShowGroup(item.group)">
                                        <template #header>
                                            <vs-sidebar-item arrow>
                                                <template #icon>
                                                    <i :class='item.icon'></i>
                                                </template>
                                                {{ item.title }}
                                            </vs-sidebar-item>
                                        </template>
                                        <template v-for="subItem in item.group">
                                            <div v-if="can(item.can)" class="w-full" @click="link($event)"
                                                 :url="subItem.url"
                                                 method="get" >
                                                <vs-sidebar-item :id="titleToId(item.title)+'_'+titleToId(subItem.title)" v-if="can(subItem.can)">
                                                    <template #icon>
                                                        <i :class='subItem.icon'></i>
                                                    </template>
                                                    {{ subItem.title }}
                                                </vs-sidebar-item>
                                            </div>
                                        </template>
                                    </vs-sidebar-group>
                                </template>
                                <template v-else>
                                    <div v-if="can(item.can)" class="w-full" @click="link($event)"
                                         :url="item.url"
                                         method="get" >
                                        <vs-sidebar-item :id="titleToId(item.title)" >
                                            <template #icon>
                                                <i :class='item.icon'></i>
                                            </template>
                                            {{ item.title }}
                                        </vs-sidebar-item>
                                    </div>
                                </template>
                            </template>

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
                <main :style="{width: mainContentWidht+'px'}" ref="mainContent" id="mainContent" role="main"
                      class="relative w-full sm:w-3/3 md:w-9/12 p-4 break-words min-h-screen">
                    <div class="w-full p-2 md:pl-4">
                        <slot />
                    </div>
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
    margin-top: -24px;
}
</style>
<style>
body{
    background: #e4e7eb;
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
        active: 'AllPosts',
        activeSidebar: true,
        openSideBar: true,
        mainContentWidht: 500,
        sideBarWidht: 200,

        sidebarItems: [
            {
                'title': 'Dashboard',
                'url': route('dashboard'),
                'icon': 'bx bxs-dashboard',
                'can': '',
            },
            {
                'title': 'Post',
                'icon': 'bx bxs-dock-top',
                'group': [
                    {
                        'title': 'All Posts',
                        'url': route('dashboard.post.allPosts'),
                        'icon': 'bx bx-dock-top',
                        'can': 'read_post'
                    },
                    {
                        'title': 'Add New',
                        'url': route('dashboard.post.create'),
                        'icon': 'bx bx-list-ul',
                        'can': 'create_post'
                    },
                    {
                        'title': 'Categories',
                        'url': route('dashboard.post.categories'),
                        'icon': 'bx bx-add-to-queue',
                        'can': 'create_category'
                    },
                    {
                        'title': 'Tags',
                        'url': route('dashboard.tag'),
                        'icon': 'bx bx-category',
                        'can': 'create_tag'
                    },
                ]
            },
            // {
            //     'title': 'Videos',
            //     'icon': 'bx bxs-video',
            //     'group': [
            //         {
            //             'title': 'All Videos',
            //             'url': route('dashboard'),
            //             'icon': 'bx bx-video',
            //             'can': 'read_video'
            //         },
            //         {
            //             'title': 'Add New',
            //             'url': route('dashboard'),
            //             'icon': 'bx bx-video-plus',
            //             'can': 'create_video'
            //         },
            //         {
            //             'title': 'Categories',
            //             'url': route('dashboard'),
            //             'icon': 'bx bx-add-to-queue',
            //             'can': 'create_category'
            //         },
            //         {
            //             'title': 'Tags',
            //             'url': route('dashboard'),
            //             'icon': 'bx bx-category',
            //             'can': 'create_tag'
            //         },
            //     ]
            // },
            {
                'title': 'Pages',
                'icon': 'bx bxs-copy-alt',
                'group': [
                    {
                        'title': 'All Pages',
                        'url': route('dashboard.page.index'),
                        'icon': 'bx bx-copy-alt',
                        'can': 'read_page'
                    },
                    {
                        'title': 'Add New',
                        'url': route('dashboard.page.create'),
                        'icon': 'bx bx-add-to-queue',
                        'can': 'create_page'
                    }
                ]
            },
            {
                'title': 'Comments',
                'url': route('dashboard'),
                'icon': 'bx bxs-comment',
                'can': 'comments_actions',
            },
            {
                'title': 'Users',
                'icon': 'bx bxs-user',
                'group': [
                    {
                        'title': 'All Users',
                        'url': route('dashboard'),
                        'icon': 'bx bx-user',
                        'can': 'read_users'
                    },
                    {
                        'title': 'Add New',
                        'url': route('dashboard'),
                        'icon': 'bx bx-user-plus',
                        'can': 'create_users'
                    },
                    {
                        'title': 'User Roles',
                        'url': route('dashboard'),
                        'icon': 'bx bx-block',
                        'can': 'create_role'
                    },
                    {
                        'title': 'Profile',
                        'url': route('dashboard'),
                        'icon': 'bx bxs-user-detail',
                        'can': ''
                    },

                ]
            },
            {
                'title': 'Media',
                'icon': 'bx bxs-image',
                'group': [
                    {
                        'title': 'All Media',
                        'url': route('dashboard.media.index'),
                        'icon': 'bx bx-images',
                        'can': 'read_media'
                    },
                    {
                        'title': 'Add Media',
                        'url': route('dashboard.media.create'),
                        'icon': 'bx bxs-image-add',
                        'can': 'create_media'
                    },
                ]
            },
            {
                'title': 'Settings',
                'url': route('dashboard'),
                'icon': 'bx bxs-dashboard',
                'can': 'change_site_settings',
            },
        ]
    }),
    methods: {
        canShowGroup( group ) {
            for( let i=0; i<group.length; i++ ) {
                if( this.can( group[i].can ))
                    return true;
            }
            return false;
        }
    },
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
