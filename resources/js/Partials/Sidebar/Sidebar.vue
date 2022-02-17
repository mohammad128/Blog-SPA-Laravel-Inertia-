<template>

    <vs-sidebar
        fixed
        :open.sync="active"
    >
        <template #logo>
            <!-- ...img logo -->
            <i class="bx bx-atom text-6xl"></i>
        </template>

        <template v-for="(item,index) in data">


<!--            Single Item -->
            <template v-if="!item.dropdown">
                <vs-sidebar-item  v-if="item.href!=='' && item.href!=='#'"  :id="`item${item.id}`" >
                    <template v-if="item.icon" #icon>
                        <i @click.stop="link($event)" method="get" :url="item.href" class='bx ' :class="item.icon"></i>
                    </template>
                    <template v-else #icon>
                        <i @click.stop="link($event)" method="get" :url="item.href" class='bx '></i>
                    </template>
                    <span @click.stop="link($event)" method="get" :url="item.href">{{ item.title }}</span>
                </vs-sidebar-item>
                <vs-sidebar-item v-else :id="`item${item.id}`" >
                    <template v-if="item.icon" #icon>
                        <i class='bx ' :class="item.icon"></i>
                    </template>
                    <template v-else #icon>
                        <i class='bx '></i>
                    </template>
                    {{ item.title }}
                </vs-sidebar-item>
            </template>
<!--             Group Item -->
            <template v-else>
                <vs-sidebar-group>
                    <template #header>
                        <vs-sidebar-item arrow>
                            <template v-if="item.icon" #icon>
                                <i class='bx ' :class="item.icon"></i>
                            </template>
                            <template v-else #icon>
                                <i class='bx '></i>
                            </template>
                            {{ item.title }}
                        </vs-sidebar-item>
                    </template>

                    <div v-for="(children, index) in item.children" @click="link($event)" method="get" :url="children.href">
                        <vs-sidebar-item :id="`item${children.id}`">
                            <template v-if="children.icon" #icon>
                                <i class='bx ' :class="children.icon"></i>
                            </template>
                            <template v-else #icon>
                                <i class='bx '></i>
                            </template>
                            {{ children.title }}
                        </vs-sidebar-item>
                        <SidebarChilditem v-if="children.dropdown" :data="children.children"></SidebarChilditem>
                    </div>

                </vs-sidebar-group>

            </template>
        </template>


        <template #footer>
            <template v-if="$page.props.user">
                <vs-row justify="space-between">
                    <vs-avatar  class="cursor-pointer" @click="link($event)" :url="route('dashboard')" method="get">
                        <img :src="$page.props.user.profile_photo_url" alt="">
                    </vs-avatar>

                    <vs-avatar class="cursor-pointer" badge-color="danger" badge-position="top-right"  @click="link($event)" :url="route('dashboard.user.notifications')" method="get">
                        <i class='bx bx-bell' ></i>

                        <template v-if="$page.props.user.new_notifications_count" #badge>
                            {{ $page.props.user.new_notifications_count }}
                        </template>
                    </vs-avatar>
                </vs-row>
            </template>
            <template v-else>
                <vs-row justify="space-between">
                    <div class="w-full flex flex-row-reverse">
                        <vs-button flat @click="link($event)"
                                   :url="route('login')"
                                   method="get" >Login</vs-button>

                        <vs-button @click="link($event)"
                                   :url="route('register')"
                                   method="get">Register</vs-button>
                    </div>
                </vs-row>
            </template>
        </template>

    </vs-sidebar>

</template>

<script>
import SidebarChilditem from "@/Partials/Sidebar/SidebarChilditem";

export default {
    name: "SideBar",
    components:{
        SidebarChilditem
    },
    props: ['value', 'data'],
    data() {
        return {
            active: false,
        }
    },
    mounted() {
    },
    watch: {
        value: function () {
            this.active = this.value;
        },
        active: function () {
            this.$emit('input', this.active);
        }
    }
}
</script>

<style scoped>

</style>
