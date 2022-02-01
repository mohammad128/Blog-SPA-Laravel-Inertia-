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
                <vs-sidebar-item  v-if="item.href!='' && item.href!='#'"  :id="`item${item.id}`" >
                    <template v-if="item.icon" #icon>
                        <i class='bx ' :class="item.icon"></i>
                    </template>
                    <template v-else #icon>
                        <i class='bx '></i>
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

                    <div v-for="(children, index) in item.children" >
                        <vs-sidebar-item :id="`item${children.id}`">
                            <template v-if="children.icon" #icon>
                                <i class='bx ' :class="children.icon"></i>
                            </template>
                            <template v-else #icon>
                                <i class='bx '></i>
                            </template>
                            {{ children.title }}
                        </vs-sidebar-item>
                    </div>

                </vs-sidebar-group>

            </template>
        </template>


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

</template>

<script>
import Sidebaritem from "@/Partials/Sidebar/Sidebaritem";

export default {
    name: "SideBar",
    components:{
        Sidebaritem
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
