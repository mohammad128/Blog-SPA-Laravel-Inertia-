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
                    <vs-card  v-for="(post, index) in newst_posts" :key="index"  type="1"  class="">
                        <template #title>
                            <h3></h3>
                        </template>
                        <template #img>
                            <img :src="post.feature_image" alt="">
                        </template>
                        <template #text style="width: 100%" class="bg-gray-800">
                            <h3 class="line-clamp-2">{{post.title}}</h3>
                            <inertia-link class="text-sm text-blue-700" :href="post.url">more...</inertia-link>
                        </template>
                        <template #interactions>
                            <vs-button icon gradient @click.stop="showPreviewPsot(post.id)">
                                <i class='bx bx-show'></i>
                            </vs-button>
                            <vs-button class="btn-chat" v-if="post.comments_count" active icon gradient color="#65a30d">
                                <i class='bx bxs-comment' ></i>
                                <span class="span font-extrabold pl-1">{{post.comments_count}}</span>
                            </vs-button>
                            <vs-button active v-if="post.rate" icon gradient color="#eab308">
                                <span class="flex flex-row items-center justify-center"><i class='bx bxs-star'></i> {{post.rate.rate}}</span>
                            </vs-button>
                        </template>
                    </vs-card>
                </vs-card-group>
            </div>
            <div class="mt-24">
                <h1 class="mb-2 font-bold text-2xl text-center sm:text-left">Popular posts</h1>
                <vs-card-group>
                    <vs-card  v-for="(post, index) in popular_posts" :key="index"  type="1"  class="">
                        <template #title>
                            <h3></h3>
                        </template>
                        <template #img>
                            <img :src="post.feature_image" alt="">
                        </template>
                        <template #text style="width: 100%" class="bg-gray-800">
                            <h3 class="line-clamp-2">{{post.title}}</h3>
                            <inertia-link class="text-sm text-blue-700" :href="post.url">more...</inertia-link>
                        </template>
                        <template #interactions>
                            <vs-button icon gradient @click.stop="showPreviewPsot(post.id)">
                                <i class='bx bx-show'></i>
                            </vs-button>
                            <vs-button class="btn-chat" v-if="post.comments_count" active icon gradient color="#65a30d">
                                <i class='bx bxs-comment' ></i>
                                <span class="span font-extrabold pl-1">{{post.comments_count}}</span>
                            </vs-button>
                            <vs-button active v-if="post.rate" icon gradient color="#eab308">
                                <span class="flex flex-row items-center justify-center"><i class='bx bxs-star'></i> {{post.rate.rate}}</span>
                            </vs-button>
                        </template>
                    </vs-card>
                </vs-card-group>
            </div>
        </div>

        <PreviewPost :postId="showPostPreviewPostId" v-model="showPostPreview"/>
    </Layout>
</template>

<script>
import PreviewPost from "@/Components/PreviewPost";
import carousel from 'vue-owl-carousel'

export default {
    components: {
        carousel,
        PreviewPost
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        home_slider: Array,
        popular_posts: Array,
        newst_posts: Array,
    },
    data() {
        return {
            showPostPreview: false,
            showPostPreviewPostId: 0
        }
    },
    methods: {
        showPreviewPsot(postId) {
            this.showPostPreviewPostId = postId;
            this.showPostPreview = true;//!this.showPostPreview;
        }
    }

}
</script>

<style>
span.prev{
    position: absolute;
    font-size: 5em;
    top: 30%;
    z-index: 5;
    text-shadow: 0 0 8px #9f9f9f;
    transition: all 2s;
    opacity: .3;
    color: #ffffff;
    left: 0;
    cursor: pointer;
}
span.next{
    position: absolute;
    font-size: 5em;
    top: 30%;
    z-index: 5;
    text-shadow: 0 0 8px #9f9f9f;
    transition: all 2s;
    opacity: .3;
    color: #ffffff;
    right: 0;
    cursor: pointer;
}

</style>
