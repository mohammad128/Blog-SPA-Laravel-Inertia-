<template>

    <Layout>

        <Head>
            <title>{{siteTitle}} - Posts</title>
            <meta name="description" head-key="description" content="Posts">
        </Head>

        <div id="postsContainer" >
            <div class="relative banner h-64 md:h-96 bg-cover bg-fixed bg-center filter flex flex-row justify-center" :style="`background-image: url('${post.feature_image}')`">
                <div class="w-1/3 transform backdrop-grayscale"></div>
                <div class="w-1/3 "></div>
                <div class="w-1/3 transform backdrop-hue-rotate-180"></div>
            </div>
            <div class="post w-full mb-8 max-w-screen-xl mx-auto divide-y-2">
                <div class="relative flex flex-row justify-between w-full bg-white rounded-tl-2xl rounded-tr-2xl p-4">
                    <h2 class="text-center font-bold text-lg lg:text-4xl text-gray-800">{{post.title}}</h2>

                    <span class="inline-block relative flex flex-row gap-4 items-center justify-center shrink-0">
                        <div class="flex flex-col justify-center">
<!--                            :read-only="$page.props.user ? false : true"-->
                            <LikeButton  @handleClick="handleLike($event)" :active="(post.user_like_status && post.user_like_status.like) ? true : false"/>
                            <span class="text-xs text-gray-700">{{ post.like_status ? post.like_status.like_count : '0' }}</span>
                        </div>
                        <div class="flex flex-col justify-center">
<!--                            :read-only="$page.props.user ? false : true"-->
                            <LikeButton  @handleClick="handleDislike($event)" button-type="dislike" :active="(post.user_like_status && post.user_like_status.dislike) ? true : false"/>
                            <span class="text-xs text-gray-700">{{ post.like_status ? post.like_status.dislike_count : '0' }}</span>
                        </div>
                    </span>

                </div>
                <div class="post-content prose prose-h1:text-2xl prose-stone prose-sm lg:prose-lg w-full lg:prose-h1:text-3xl max-w-none bg-white p-4" v-html="post.content">
                </div>
                <div class="w-full flex flex-row justify-start items-center gap-4 bg-white rounded-bl-2xl rounded-br-2xl p-4">
<!--                    v-if="$page.props.user"-->
                    <star-rating
                                 :star-size="24"
                                 :increment="0.5"
                                 :max-rating="10"
                                 inactive-color="#d6d3d1"
                                 text-class="text-xs text-gray-500"
                                 :animate="true"
                                 active-color="#eab308"
                                 :rating="post.user_rate"
                                 @rating-selected ="setRating"
                    />
                    <i v-if="post.rate" class="text-xs text-gray-700 font-bold">{{ post.rate.rate }} / 10  ({{post.rate.rate_count}} Vote)</i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg w-full mb-8 max-w-screen-xl mx-auto divide-y-2">
            <div class="w-full p-4">
                <h3 class="text-3xl text-gray-800 font-bold">Comments</h3>
            </div>
            <div class="comment-content w-full p-6">
                <Comments :comments="comments" :post-id="post.id"/>
            </div>
        </div>

    </Layout>
</template>

<script>
import StarRating from 'vue-star-rating'
import LikeButton from "@/Components/LikeButton";
import Comments from "@/Pages/Post/Show/Components/Comment/Comments";

export default {
    name: "Index",
    components: {
        StarRating,
        LikeButton,
        Comments
    },
    props: {
        post: Object,
        comments: Object
    },
    methods: {
        setRating: function(rating){
            console.log(rating);
            this.$inertia.post(route('Post.setPostRate', {post: this.post.id}),{
                rate: rating
            }, {
                preserveScroll: true,
                preserveState: false
            });
        },

        handleLike(active) {
            this.$inertia.post(route('Post.like', {post: this.post.id}),{
                type: 'like',
                active: active
            }, {
                preserveScroll: true,
                preserveState: false
            });
        },
        handleDislike(active) {
            this.$inertia.post(route('Post.like', {post: this.post.id}),{
                type: 'dislike',
                active: active
            }, {
                preserveScroll: true,
                preserveState: false
            });
        },
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
</style>
