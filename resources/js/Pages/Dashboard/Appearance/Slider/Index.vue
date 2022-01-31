<template>
    <DashboardLayout>
        <div class="flex flex-col gap-4">
            <Slider :slider_items="home_slider" :slider_name="'home_slider'"></Slider>
        </div>
    </DashboardLayout>
</template>

<script>
import Slider from "@/Pages/Dashboard/Appearance/Slider/Components/Slider";

export default {
    name: "Sliders",
    components: {
        Slider
    },
    props: {
        home_slider: Array
    },
}
</script>
