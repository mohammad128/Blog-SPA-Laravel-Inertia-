<template>
    <div v-if="chartOptions" class="flex flex-col bg-white rounded-lg shadow-lg p-4 py-4">
        <div class="flex flex-row justify-between">
            <h3 class="w-full text-left font-bold text-2xl text-gray-800">{{ title }}</h3>
            <vs-button icon
                       @click="chartTypesIndex+1==chartTypes.length ? chartTypesIndex=0 : chartTypesIndex++">
                <i class="bx bx-abacus"></i>
            </vs-button>
        </div>
        <div class="flex-1">
            <div class="w-full grid grid-cols-1">
                <apexchart :type="chartTypes[chartTypesIndex]" height="350px" width="100%" :options="chartOptions" :series="series"></apexchart>
            </div>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";

export default {
    name: "Chart",
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        data: Object,
        title: {
            type: String,
            default: ''
        },
        type: {
            type: Number,
            default: 0
        }
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
        this.chartTypesIndex = this.type;
        let that = this;
        setTimeout(function () {
            that.series = that.data.series;
            that.chartOptions = {
                labels: that.data.labels,
                stroke: {
                    colors: ['#ff']
                },
                fill: {
                    opacity: 0.8
                },
            };
        }, 100);
    }
}
</script>

<style scoped>

</style>
