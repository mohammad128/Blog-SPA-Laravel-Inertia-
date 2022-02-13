<template>

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
                <vs-button icon dark
                           @click="index+1==options.length ? index=0 : index++">
                    <i class="bx bx-bar-chart-alt"></i>
                </vs-button>
            </div>
        </div>
        <div class="flex-1">
            <div class="w-full grid grid-cols-1">
                <apexchart :type="types[index]" height="350px" width="100%" :options="in_30_days_chartOptions" :series="in_30_days_series"></apexchart>
            </div>
        </div>
    </div>

</template>

<script>
import VueApexCharts from "vue-apexcharts";

export default {
    name: "In30Days",
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        data: Object,
    },
    data() {
        return {
            fromDate: '',
            toDate: '',
            in_30_days_series: [],
            in_30_days_chartOptions: { },

            options: [],
            types: ['area', 'bar'],
            index: 1
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
    mounted() {
        this.fromDate = this.data.categories[0];
        this.toDate = this.data.categories[this.data.categories.length-1];



        this.options[0] = {
            chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                    categories: this.data.categories,
            },
        };
        this.options[1] = {
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
                categories: this.data.categories,
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
            },
        }

        this.in_30_days_series = this.data.series;
        this.in_30_days_chartOptions = this.options[this.index];
    }
}
</script>

<style scoped>

</style>
