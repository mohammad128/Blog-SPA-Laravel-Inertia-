<template>
    <div v-if="chartOptions" class="w-full flex flex-col bg-white rounded-lg shadow-lg p-4 gap-4">
        <div class="flex flex-col">
            <div class="flex flex-row justify-between">
                <h3 class="w-full text-left font-bold text-2xl text-gray-800">Overview</h3>
                <vs-button icon
                    @click="chartTypesIndex+1==chartTypes.length ? chartTypesIndex=0 : chartTypesIndex++">
                    <i class="bx bx-abacus"></i>
                </vs-button>
            </div>
            <apexchart :type="chartTypes[chartTypesIndex]" height="390" :options="chartOptions" :series="series"></apexchart>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";

export default {
    name: "AngleCircleChart",
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        data: Object,
    },
    data() {
        return {
            series: [],
            chartOptions: null,
            chartTypes: ['radialBar', 'donut', 'pie', 'polarArea'],
            chartTypesIndex: 0
        }
    },
    mounted() {
        let that = this;
        setTimeout(function () {
            that.series = that.data.series;//[76, 67, 61, 90];
            that.chartOptions = {
                chart: {
                    height: 390,
                        type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                            startAngle: 0,
                            endAngle: 270,
                            hollow: {
                            margin: 5,
                                size: '30%',
                                background: 'transparent',
                                image: undefined,
                        },
                        dataLabels: {
                            name: {
                                show: true,
                            },
                            value: {
                                show: true,
                            }
                        }
                    }
                },
                labels: that.data.labels,//['Vimeo', 'Messenger', 'Facebook', 'LinkedIn'],
                legend: {
                    show: true,
                        floating: true,
                        fontSize: '16px',
                        position: 'left',
                        offsetX: 160,
                        offsetY: 15,
                        labels: {
                        useSeriesColors: true,
                    },
                    markers: {
                        size: 0
                    },
                    formatter: function (seriesName, opts) {
                        return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                    },
                    itemMargin: {
                        vertical: 3
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            show: false
                        }
                    }
                }]
            };
        }, 100);
    }
}
</script>

<style scoped>

</style>
