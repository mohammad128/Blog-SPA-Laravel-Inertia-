<template>

    <Layout>

        <Head>
            <title>{{siteTitle}} - Posts</title>
            <meta name="description" head-key="description" content="Posts">
        </Head>

        <div id="postsContainer" >
            <div class="banner h-64 bg-cover bg-fixed bg-center" :style="`background-image: url('${post.feature_image}')`"></div>
            <div class="post w-full mb-8 max-w-screen-xl mx-auto divide-y-2">
                <div class="relative flex flex-row justify-between w-full bg-white rounded-tl-2xl rounded-tr-2xl p-4 ">
                    <h2 class="text-center font-bold text-lg lg:text-4xl text-gray-800">{{post.title}}</h2>
                    <span class="inline-block relative">
                        <span class="relative -mt-8  mr-16">
                            <vue-star animate="animated bounceIn" color="#b91c1c" class="-top-6 cursor-pointer" >
                                <i slot="icon" class="bx bxs-like text-4xl"  @click="likePost"></i>
                            </vue-star>
                        </span>
                    </span>
                </div>
                <div class="post-content prose prose-h1:text-2xl prose-stone prose-sm lg:prose-lg w-full lg:prose-h1:text-3xl max-w-none bg-white p-4" v-html="post.content">
                </div>
                <div class="w-full bg-white rounded-bl-2xl rounded-br-2xl p-4">
                    <star-rating :star-size="24"
                                 :increment="0.5"
                                 :max-rating="10"
                                 inactive-color="#d6d3d1"
                                 text-class="text-xs text-gray-500"
                                 :animate="true"
                                 active-color="#eab308"
                                 @rating-selected ="setRating"
                    />

                </div>
            </div>
        </div>

    </Layout>
</template>

<script>
import StarRating from 'vue-star-rating'
import VueStar from 'vue-star'


export default {
    name: "Index",
    components: {
        StarRating,
        VueStar
    },
    props: {
        post: Object
    },
    methods: {
        setRating: function(rating){
            console.log(rating);
        },
        likePost( ) {
            alert('likePost')
        }
    },
}
</script>

<style scoped>
.post-content{
    width: 100% !important;
}
</style>
