<template>

    <div>
        <div v-for="(item,index) in data"  @click="link($event)" method="get" :url="item.href">
            <vs-sidebar-item :id="`item${item.id}`">
                <template v-if="item.icon" #icon>
                    <i class='bx ' :class="item.icon"></i>
                </template>
                <template v-else #icon>
                    <i class='bx '></i>
                </template>
                <span class="w-4 inline-block pl-4"></span>{{ item.title }}
            </vs-sidebar-item>
            <SidebarChilditem v-if="item.dropdown" :data="item.children"></SidebarChilditem>
        </div>
    </div>

</template>

<script>
export default {
    name: "SidebarChildItem",
    props: ['data']
}
</script>

<style scoped>

</style>
