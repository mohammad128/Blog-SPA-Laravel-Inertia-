<template>
    <DashboardLayout  v-slot="scope">

        <Head>
            <title>Dashboard</title>
            <meta name="description" head-key="description" content="Dashboard Page">
        </Head>

        <div class="max-w-none">
            <div class="flex flex-col gap-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-4 gap-2">
                    <div class="w-full flex flex-col bg-white rounded-full shadow-lg">
                        <vs-button size="xl" circle transparent color="#333" border
                            @click="link($event)" method="get" :url="route('dashboard.post.allPosts')"
                        >
                            <i class="text-2xl bx bxs-dock-top"></i>&nbsp;&nbsp; {{counts.post}} Post
                        </vs-button>
                    </div>
                    <div class="w-full flex flex-col bg-white rounded-full shadow-lg">
                        <vs-button size="xl" circle transparent color="#333" border
                            @click="link($event)" method="get" :url="route('dashboard.page.index')"
                        >
                            <i class="text-2xl bx bxs-copy-alt"></i>&nbsp;&nbsp; {{counts.page}} Page
                        </vs-button>
                    </div>
                    <div class="w-full flex flex-col bg-white rounded-full shadow-lg">
                        <vs-button size="xl" circle transparent color="#333" border
                            @click="link($event)" method="get" :url="route('dashboard.user.index')"
                        >
                            <i class="text-2xl bx bxs-user"></i>&nbsp;&nbsp; {{counts.user}} User
                        </vs-button>
                    </div>
                    <div class="w-full flex flex-col bg-white rounded-full shadow-lg">
                        <vs-button size="xl" circle transparent color="#333" border
                            @click="link($event)" method="get" :url="route('dashboard.comment.index')"
                        >
                            <i class="text-2xl bx bxs-comment"></i>&nbsp;&nbsp; {{counts.comment}} Comment
                        </vs-button>
                    </div>
                </div>

                <div class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
                    <div class="flex flex-row">
                        <h3 class="w-full text-left font-bold text-2xl text-gray-800">In 30 days ago</h3>

                        <div class="flex gap-4 flex-col lg:flex-row items-center ">
                            <div class="flex gap-2 items-center">
                                <span class="text-xs text-gray-700">From:</span>
                                <vs-input color="#333"
                                          v-model="fromDate"
                                          type="date"
                                />
                            </div>
                            <div class="flex gap-2 items-center">
                                <span class="text-xs text-gray-700">To:</span>
                                <vs-input color="#333"
                                          v-model="toDate"
                                          type="date"
                                />
                            </div>
                            <vs-button dark @click="doFilter()">
                                <i class="bx bxs-filter-alt"></i> Filter
                            </vs-button>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="w-full grid grid-cols-1">
                            <apexchart type="bar" height="350px" width="100%" :options="in_30_days_chartOptions" :series="in_30_days_series"></apexchart>
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
            in_30_days_series: [],
            in_30_days_chartOptions: { },

            post_series: [],
            post_chartOptions: {},

            page_series: [],
            page_chartOptions: {},

            comment_series: [],
            comment_chartOptions: {},

            user_series: [],
            user_chartOptions: {},

            fromDate: '',
            toDate: '',
        }
    },
    methods: {
        doFilter() {
            let data = {};
            if(this.fromDate)  data['fromDate'] = this.fromDate;
            if(this.toDate)  data['toDate'] = this.toDate;
            this.$inertia.get(route('dashboard'), data, {
                preserveScroll: true,
                preserveState: false
            });
        }
    },
    mounted(){
        this.fromDate = this.in_30_days_chart.categories[0];
        this.toDate = this.in_30_days_chart.categories[this.in_30_days_chart.categories.length-1];

        this.in_30_days_series = this.in_30_days_chart.series;
        this.in_30_days_chartOptions = {
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 12,
                colors: ['transparent']
            },
            xaxis: {
                categories: this.in_30_days_chart.categories,
            },
            yaxis: {
                title: {
                    text: 'in 30 Days ago'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "" + val + " count"
                    }
                }
            }
        };



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
        };

    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Dashboard';
    }
}
</script>
