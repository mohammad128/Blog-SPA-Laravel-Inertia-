<template>
    <DashboardLayout  v-slot="scope">

        <Head>
            <title>Dashboard</title>
            <meta name="description" head-key="description" content="Dashboard Page">
        </Head>

        <div class="max-w-none">
            <div class="flex flex-col gap-8">
                <component v-for="(item, index) in data" :key="index" :is="item.component" :data="item.data"/>
            </div>
        </div>

    </DashboardLayout>
</template>


<script>
import In30Days from "@/Pages/Dashboard/Components/DashboardComponents/In30Days";
import Counts from "@/Pages/Dashboard/Components/DashboardComponents/Counts";
import Charts from "@/Pages/Dashboard/Components/DashboardComponents/Charts";
import AngleCircleChart from "@/Pages/Dashboard/Components/DashboardComponents/AngleCircleChart";

export default {
    components: {
        In30Days,
        Counts,
        Charts,
        AngleCircleChart
    },
    props: {
        data: Array,
    },
    data() {
        return {
        }
    },
    methods: {
    },
    mounted(){
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Dashboard';
    }
}
</script>
