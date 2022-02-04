<template>

    <div class="bg-white rounded lg w-full p-4 relative">
        <div class="flex flex-row justify-between items-center">
            <h3 class="text-gray-700 text-xl font-bold py-4">Home Slider</h3>
            <vs-button transparent @click="mediaMangerActive=true" :loading="form.processing">
                Change Items
            </vs-button>
        </div>
        <carousel class="relative"  :autoplay="true" :nav="false" :autoplayTimeout="5000" :autoplayHoverPause="true"
                  :responsive="{0:{items:1,nav:false},600:{items:2,nav:false},900:{items:3,nav:false}}">
            <img v-for="(item, index) in slider_items" :src="item">

            <template slot="prev"><span class="prev"><i class="bx bxs-left-arrow"></i></span></template>
            <template slot="next"><span class="next"><i class="bx bxs-right-arrow"></i></span></template>
        </carousel>


        <vs-dialog v-model="mediaMangerActive"  :loading="mediaMangerLoading">
            <MediaManager :picker="true" type="image" :multiCheck="true" @onPick="onPickMediaPicker($event)" @onLoading="onLoadingMediaPicker($event)"/>
        </vs-dialog>
    </div>

</template>

<script>
import carousel from 'vue-owl-carousel';
import MediaManager from "@/Pages/Dashboard/Components/MediaManager";

export default {
    name: "SliderComponent",
    components: {
        carousel,
        MediaManager
    },
    props: {
        slider_items: Array,
        slider_name: String
    },
    data() {
        return {
            mediaMangerActive: false,
            mediaMangerLoading: false,
            form: this.$inertia.form({
                name: this.slider_name,
                items: null
            })
        }
    },
    methods: {
        onLoadingMediaPicker(isLoading) {
            this.mediaMangerLoading = isLoading;
        },
        onPickMediaPicker(media) {
            this.mediaMangerActive = false;
            this.form.items = media.map(item=>item.media_url);
            this.updateSlider();
        },
        updateSlider() {
            this.form.post(route('dashboard.appearance.sliders.update'), {
                preserveScroll: true,
                preserveState: false
            })
        }
    }
}
</script>

<style>
span.prev{
    position: absolute;
    font-size: 2em;
    top: 40%;
    z-index: 5;
    text-shadow: 0 0 8px #000;
    transition: all 2s;
    opacity: .7;
    color: #dfdfdf;
    left: 0;
    cursor: pointer;
}
span.next{
    position: absolute;
    font-size: 2em;
    top: 40%;
    z-index: 5;
    text-shadow: 0 0 8px #000;
    transition: all 2s;
    opacity: .7;
    color: #dfdfdf;
    right: 0;
    cursor: pointer;
}

</style>
