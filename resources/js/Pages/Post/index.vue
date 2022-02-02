<template>
    <Layout>

        <Head>
            <title>{{siteTitle}} - Posts</title>
            <meta name="description" head-key="description" content="Posts">
        </Head>

        <div id="postsContainer" >
<!--            <div class="grid grid-cols-3 gap-4">-->
<!--                <div class=" h-full col-span-2 row-span-2">-->
<!--                </div>-->
<!--                <div class="bg-green-300 h-24"></div>-->
<!--                <div class="bg-blue-300 h-24"></div>-->
<!--            </div>-->
            <div class="md:max-w-screen-xl m-auto md:m-auto py-10 flex">

                <vs-input
                    label-placeholder="Search"
                    v-model="search"
                    :loading="showLoading"
                >

                    <template #icon>
                        <i class='bx bx-search'></i>
                    </template>
                </vs-input>
                <span class="text-sm text-gray-400 pl-5 pt-3">total: {{posts.total}} </span>
            </div>
            <div ref="postContainer"
                 class="relative container md:max-w-screen-xl m-auto md:m-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 justify-items-center gap-4">

                <Loading :show="showLoading"  class="absolute top-0 bottom-0 left-0 right-0 bg-gray-800" style="z-index: 2;background: #ffffff63;"/>

                <div v-for="(post, index) in posts.data" :key="index" >
                    <vs-card type="1"  class="">
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
                            <vs-button class="btn-chat" shadow primary>
                                <i class='bx bx-chat' ></i>
                                <span class="span font-extrabold">
                                  {{post.comments_count}}
                                </span>
                            </vs-button>
                        </template>
                    </vs-card>

                </div>
            </div>

            <div class="md:max-w-screen-xl m-auto md:m-auto py-10 grid justify-items-center">
                <vs-pagination :disabled="showLoading" v-model="page" :length="posts.last_page" />
            </div>

            <PreviewPost :postId="showPostPreviewPostId" v-model="showPostPreview"/>

        </div>
    </Layout>

</template>

<script>
import {Inertia} from '@inertiajs/inertia';
import Loading from '@/Components/Loading'
import PreviewPost from "@/Components/PreviewPost";

export default {
    name: "index",
    components:{
        Loading,
        PreviewPost,
    },
    props: {
        posts: Object,
        _filters: Object
    },
    data() {
        return {
            filter: {
                search: '',
            },
            search: "",
            page: 1,
            showLoading: false,
            awaitingSearch: false,
            showPostPreview: false,
            showPostPreviewPostId: 0
        }
    },
    mounted: function () {
        if(this._filters.search != null)
            this.search = this._filters.search;
        this.page = this.posts.current_page;
    },
    methods: {
        doFilterPosts() {
            let that = this;
            let data = {};
            if(this.search !== "")
                data['search'] = this.search;
            if(this.page !== 1)
                data['page'] = this.page;
            Inertia.get(route('Post.index'), data, {
                preserveState: true,
                preserveScroll: false,
                onStart:function() {
                    that.showLoading = true;
                },
                onFinish: function () {
                    that.showLoading = false;
                }
            })
        },
        showPreviewPsot(postId) {
            this.showPostPreviewPostId = postId;
            this.showPostPreview = true;//!this.showPostPreview;
        }
    },
    watch: {
        search: function(val, oldVal) {
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    this.page = 1;
                    this.doFilterPosts();
                    this.awaitingSearch = false;
                }, 1000); // 1 sec delay
            }
            this.awaitingSearch = true;
        },
        page: function(val, oldVal) {
            this.doFilterPosts()
        }
    }
}
</script>

<style >
#postsContainer .vs-card__text{
    width: 100%;
}
</style>
