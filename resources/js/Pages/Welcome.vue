<template>
    <div class="pt-20">
        <Nav/>
        <carousel>
            <img src="https://via.placeholder.com/700x700">
            <img src="https://via.placeholder.com/700x700">
            <img src="https://via.placeholder.com/700x700">
            <img src="https://via.placeholder.com/700x700">
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
    </div>
</template>

<style scoped>
</style>

<script>
import carousel from 'vue-owl-carousel'
import Layout from "@/Pages/Layouts/Layout";
import test from "@/Pages/test";

export default {
    layout: Layout,
    components: { carousel,test },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    },

}
</script>
