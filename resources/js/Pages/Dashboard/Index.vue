<template>
    <DashboardLayout  v-slot="scope">

        <Head>
            <title>Dashboard</title>
            <meta name="description" head-key="description" content="Dashboard Page">
        </Head>

        <div class="max-w-none">
            <div class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-4 gap-2">
                    <div class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
                        <h2 class="w-full text-lg font-bold text-center">Post</h2>
                        <vs-button size="xl" circle transparent dark
                            @click="link($event)" method="get" :url="route('dashboard.post.allPosts')"
                        >
                            <i class="bx bx-trash"></i>&nbsp;&nbsp; {{counts.post}} Post
                        </vs-button>
                    </div>
                    <div class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
                        <h2 class="w-full text-lg font-bold text-center">Page</h2>
                        <vs-button size="xl" circle transparent dark
                            @click="link($event)" method="get" :url="route('dashboard.page.index')"
                        >
                            <i class="bx bx-trash"></i>&nbsp;&nbsp; {{counts.page}} Page
                        </vs-button>
                    </div>
                    <div class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
                        <h2 class="w-full text-lg font-bold text-center">User</h2>
                        <vs-button size="xl" circle transparent dark
                            @click="link($event)" method="get" :url="route('dashboard.user.index')"
                        >
                            <i class="bx bx-trash"></i>&nbsp;&nbsp; {{counts.user}} User
                        </vs-button>
                    </div>
                    <div class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
                        <h2 class="w-full text-lg font-bold text-center">Comment</h2>
                        <vs-button size="xl" circle transparent dark
                            @click="link($event)" method="get" :url="route('dashboard.comment.index')"
                        >
                            <i class="bx bx-trash"></i>&nbsp;&nbsp; {{counts.comment}} Comment
                        </vs-button>
                    </div>
                </div>

                <div class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
                    <div class="flex flex-row">
                        <h3 class="w-full text-center font-bold text-2xl text-gray-800">In 30 days ago</h3>
                    </div>
                    <div class="flex-1">
                        <div class="w-full grid grid-cols-1">
                            <apexchart type="bar" height="350px" width="100%" :options="chartOptions" :series="series"></apexchart>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="flex flex-col bg-white rounded-lg shadow-lg py-4 ">
                        <div class="flex flex-row">
                            <h3 class="pb-4 w-full text-center font-bold text-2xl text-gray-800">Post</h3>
                        </div>
                        <div class="flex-1">
                            <div class="w-full grid grid-cols-1">
                                <apexchart type="polarArea" height="350px" width="100%" :options="post_chartOptions" :series="post_series"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col bg-white rounded-lg shadow-lg p-4 py-4">
                        <div class="flex flex-row">
                            <h3 class="pb-4 w-full text-center font-bold text-2xl text-gray-800">Page</h3>
                        </div>
                        <div class="flex-1">
                            <div class="w-full grid grid-cols-1">
                                <apexchart type="polarArea" height="350px" width="100%" :options="page_chartOptions" :series="page_series"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col bg-white rounded-lg shadow-lg p-4 py-4">
                        <div class="flex flex-row">
                            <h3 class="pb-4 w-full text-center font-bold text-2xl text-gray-800">Comment</h3>
                        </div>
                        <div class="flex-1">
                            <div class="w-full grid grid-cols-1">
                                <apexchart type="polarArea" height="350px" width="100%" :options="comment_chartOptions" :series="comment_series"></apexchart>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col bg-white rounded-lg shadow-lg p-4 py-4">
                        <div class="flex flex-row">
                            <h3 class="pb-4 w-full text-center font-bold text-2xl text-gray-800">User</h3>
                        </div>
                        <div class="flex-1">
                            <div class="w-full grid grid-cols-1">
                                <apexchart type="polarArea" height="350px" width="100%" :options="user_chartOptions" :series="user_series"></apexchart>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </DashboardLayout>
</template>


<script>
import VueApexCharts from 'vue-apexcharts'


export default {
    components: {
        apexchart: VueApexCharts,

    },
    props: {
        in_30_days_chart: Object,
        counts: Object,
        post_chart: Object,
        page_chart: Object,
        comment_chart: Object,
        user_chart: Object
    },
    data() {
        return {
            series: [],
            chartOptions: { },

            post_series: [],
            post_chartOptions: {},

            page_series: [],
            page_chartOptions: {},

            comment_series: [],
            comment_chartOptions: {},

            user_series: [],
            user_chartOptions: {},


        }
    },
    methods: {
    },
    mounted(){

        this.post_series = this.post_chart.series;
        this.post_chartOptions = {
            chart: {
                type: 'polarArea',
            },
            labels: this.post_chart.labels,
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        this.page_series = this.page_chart.series;
        this.page_chartOptions = {
            chart: {
                type: 'polarArea',
            },
            labels: this.page_chart.labels,
            stroke: {
                colors: ['#ff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        this.comment_series = this.comment_chart.series;
        this.comment_chartOptions = {
            chart: {
                type: 'polarArea',
            },
            labels: this.comment_chart.labels,
            stroke: {
                colors: ['#ff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };


        this.user_series = this.user_chart.series;
        this.user_chartOptions = {
            chart: {
                type: 'polarArea',
            },
            labels: this.user_chart.labels,
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Dashboard';
    }
}
</script>
