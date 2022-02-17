<template>
    <Layout>
        <Head>
            <title>{{siteTitle}} - Posts</title>
            <meta name="description" head-key="description" content="Posts">
        </Head>

        <div id="postsContainer" >
            <div class="relative banner h-64 md:h-96 bg-cover bg-fixed bg-center filter flex flex-row justify-center grayscale hover:grayscale-0 bg-100% hover:bg-120% bg-center bg-no-repeat duration-700" :style="`background-image: url('${post.feature_image}')`"></div>
            <div class="post w-full mb-8 max-w-screen-xl mx-auto divide-y-2 bg-white">
                <div class="relative flex flex-row justify-between w-full bg-white rounded-tl-2xl rounded-tr-2xl p-4">
                    <h2 class="text-center font-bold text-lg lg:text-4xl text-gray-800">{{post.title}}</h2>
                </div>
                <div class="post-content prose prose-h1:text-2xl prose-stone prose-sm lg:prose-lg w-full lg:prose-h1:text-3xl max-w-none bg-white p-4" v-html="post.content">
                </div>
                <div  class="w-full flex flex-row justify-start items-center gap-4 bg-white rounded-bl-2xl rounded-br-2xl p-4" >
                </div>
            </div>
        </div>
        <div class="shadowDiv"> </div>


    </Layout>
</template>

<script>

export default {
    name: "Index",
    components: {
    },
    props: {
        post: Object,
        comments: Object
    },
    methods: {
    },
}
</script>

<style scoped>
.post-content{
    width: 100% !important;
}
</style>
<style>
body{
    overflow-x: hidden;
}
.shadowDiv {
    background: url(https://www.monas.lt/blogas/wp-content/themes/themia-lite/images/post-shadow.png) top center no-repeat;
    height: 29px;
    margin-top: -30px;
    margin-bottom: 2em;
    box-shadow: none;
    border-bottom: 0;
    outline: 0;
}
</style>
