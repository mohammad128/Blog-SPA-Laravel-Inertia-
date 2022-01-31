<template>
    <Layout>
        <carousel class="relative"  :autoplay="true" :nav="false" :autoplayTimeout="5000" :autoplayHoverPause="true">
            <img v-for="(item, index) in home_slider" :src="item">
            <template slot="prev"><span class="prev"><i class="bx bxs-left-arrow"></i></span></template>
            <template slot="next"><span class="next"><i class="bx bxs-right-arrow"></i></span></template>
        </carousel>

        <div class="max-w-screen-xl mx-auto m-auto md:container md:mx-auto">
            <div>
                <h1 class="mb-2 font-bold text-2xl text-center sm:text-left">New posts</h1>
                <vs-card-group>
                    <vs-card v-for="card in 6" :key="card">
                        <template #title>
                            <h3>Pot with a plant</h3>
                        </template>
                        <template #img>
                            <img :src="`/uploads/images/foto${card}.png`" alt="">
                        </template>
                        <template #text>
                            <p>
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            </p>
                        </template>
                        <template #interactions>
                            <vs-button danger icon>
                                <i class='bx bx-heart'></i>
                            </vs-button>
                            <vs-button class="btn-chat" shadow primary>
                                <i class='bx bx-chat' ></i>
                                <span class="span">
                                54
                            </span>
                            </vs-button>
                        </template>
                    </vs-card>
                </vs-card-group>
            </div>
            <div class="mt-24">
                <h1 class="mb-2 font-bold text-2xl text-center sm:text-left">Popular posts</h1>
                <vs-card-group>
                    <vs-card v-for="card in 6" :key="card">
                        <template #title>
                            <h3>Pot with a plant</h3>
                        </template>
                        <template #img>
                            <img :src="`/uploads/images/foto${card}.png`" alt="">
                        </template>
                        <template #text>
                            <p>
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                            </p>
                        </template>
                        <template #interactions>
                            <vs-button danger icon>
                                <i class='bx bx-heart'></i>
                            </vs-button>
                            <vs-button class="btn-chat" shadow primary>
                                <i class='bx bx-chat' ></i>
                                <span class="span">
                                54
                            </span>
                            </vs-button>
                        </template>
                    </vs-card>
                </vs-card-group>
            </div>
        </div>
    </Layout>
</template>

<script>
import carousel from 'vue-owl-carousel'

export default {
    components: { carousel },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        home_slider: Array
    },

}
</script>

<style>
span.prev{
    position: absolute;
    font-size: 5em;
    top: 30%;
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
    font-size: 5em;
    top: 30%;
    z-index: 5;
    text-shadow: 0 0 8px #000;
    transition: all 2s;
    opacity: .7;
    color: #dfdfdf;
    right: 0;
    cursor: pointer;
}

</style>
