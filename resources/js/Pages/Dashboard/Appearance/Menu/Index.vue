<template>
    <DashboardLayout>
        <Head>
            <title>Manage Menus</title>
            <meta name="description" head-key="description" content="Manage Menus">
        </Head>

        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
            <div class="flex space-x-4 flex space-x-4">
                <div class="text-5xl font-extrabold">
                      <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">
                        Menu
                      </span>
                </div>
                <vs-button
                    circle
                    icon
                    floating
                    @click="activeCreateMenuDialog = true"
                >
                    <i class='bx bx-plus' ></i>
                </vs-button>
            </div>
        </div>

        <div class="flex flex-row justify-between gap-4">

            <div class="w-full">
                <div class="bg-white p-4 rounded-lg shadow-lg flex flex-col gap-4">
                    <div class="flex flex-row gap-6 overflow-hidden overflow-x-auto">
                        <div v-for="(item, index) in menus" :key="index"  class="flex bg-gray-100 cursor-pointer rounded-lg gap-3 justify-between items-center pl-2 "
                             @click="link($event)" method="get" :url="route('dashboard.appearance.menu.edit', {'name': item.name})">
                            <span  class="text-lg text-gray-900" >{{ item.name }}</span>
                            <div class="flex flex-row">
                                <vs-button circle size="mini" icon >
                                    <i class="bx bx-edit"></i>
                                </vs-button>
                                <vs-button circle size="mini" icon danger @click.stop="link($event)" method="delete" :url="route('dashboard.appearance.menu.delete', {'id': item.id})">
                                    <i class="bx bx-trash"></i>
                                </vs-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <CreateMenu v-model="activeCreateMenuDialog"/>
    </DashboardLayout>
</template>

<script>
import CreateMenu from "@/Pages/Dashboard/Appearance/Menu/Components/CreateMenu";

export default {
    name: "MenuIndex",
    components: {
        CreateMenu
    },
    props: {
        menus: Array,
        menu: Object,
    },
    data() {
        return {
            activeCreateMenuDialog: false,
        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Appearance_Menu';
    },
}
</script>

<style scoped>

</style>
