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
                    @click="link($event)"
                    method="get"
                    :url="route('dashboard.post.create')"
                >
                    <i class='bx bx-plus' ></i>
                </vs-button>
            </div>
        </div>

        <div class="flex flex-row justify-between gap-4">

            <div class="bg-white p-4 rounded-lg shadow-lg flex flex-col gap-4">

                <div v-for="(item, index) in menus" :key="index" class="flex border-l border-l-amber-800 border-l-2 gap-3 justify-between items-center pl-2 ">
                    <span class="text-lg text-gray-900">{{ item.name }}</span>
                    <span class="text-xs text-gray-500">{{ item.created_at.substring(0,10) }} ({{ item.diff_for_human }})</span>
                    <div>
                        <vs-button icon @click="link($event)" method="get" :url="route('dashboard.appearance.menu.edit', {'name': item.name})">
                            <i class="bx bx-edit"></i>
                        </vs-button>
                        <vs-button icon danger @click="link($event)" method="delete" :url="route('dashboard.appearance.menu.delete', {'id': item.id})">
                            <i class="bx bx-trash"></i>
                        </vs-button>
                    </div>
                </div>

            </div>

        </div>
    </DashboardLayout>
</template>

<script>
export default {
    name: "MenuIndex",
    props: {
        menus: Array,
        menu: Object,
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Appearance_Menu';
    },
}
</script>

<style scoped>

</style>
