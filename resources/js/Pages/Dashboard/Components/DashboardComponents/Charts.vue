<template>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">


        <Chart v-for="(item, title) in this.data" :key="title" :title="title" :data="item" :type="2"/>


    </div>
</template>

<script>
import Chart from "@/Pages/Dashboard/Components/DashboardComponents/Chart";

export default {
    name: "Charts",
    components: {
        Chart
    },
    props: {
        data: Object
    },
    data() {
        return {
        }
    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
