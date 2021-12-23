<template>
    <DashboardLayout>
        <Head>
            <title>Add New Media</title>
            <meta name="description" head-key="description" content="Add New Media">
        </Head>

<!--        <div class="flex flex-col bg-white rounded-2xl py-2 px-1 rounded-bl-none rounded-br-none">-->
<!--            <div class="flex justify-between items-center">-->
<!--                <h2 class="text-xl pl-4">Pick File: </h2>-->

<!--                <vs-button-->
<!--                    circle-->
<!--                    icon-->
<!--                    floating-->
<!--                    @click="list = !list"-->
<!--                >-->
<!--                    <i v-if="!list" class='bx bx-list-ul' ></i>-->
<!--                    <i v-else class='bx bx-grid-alt' ></i>-->
<!--                </vs-button>-->
<!--            </div>-->

<!--            <VueFileAgent-->
<!--                :theme="list ? 'list' : 'default'"-->
<!--                :uploadUrl="uploadUrl"-->
<!--                v-model="fileRecords"></VueFileAgent>-->
<!--        </div>-->

        <AddNewMedia
        @onUpload="onUpload($event)">
        </AddNewMedia>
    </DashboardLayout>

</template>

<script>
import DashboardLayout from '@/Pages/Layouts/DashboardLayout'
import AddNewMedia from "@/Pages/Dashboard/Components/AddNewMedia";


export default {
    name: "Index",
    components: {
        DashboardLayout,
        AddNewMedia
    },
    data() {
        return {
            // list: false,
            // fileRecords: [],
            // uploadUrl: 'http://localhost/upload-server-examples/php/upload-server.php',
        };
    },
    methods: {
        onUpload(response) {
            console.log(response)
        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Media_Add_Media';
    }
}
</script>

<style scoped>

</style>
