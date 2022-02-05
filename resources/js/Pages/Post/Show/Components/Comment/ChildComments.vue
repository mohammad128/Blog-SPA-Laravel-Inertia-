<template>
    <div class="flex flex-col gap-0 mr-16">

        <transition name="slide">
            <div v-if="!hideComments" >
                <div v-for="(item, index) in replyComments" :key="index">
                    <ChildCommentItem :post-id="postId" :comment="item" />
                </div>
            </div>
        </transition>

        <div v-if="child_count>0"  @click="load()" class="w-full my-6 cursor-pointer border-b-2 border-b-sky-200 border-dashed h-1 relative flex flex-row items-center justify-center">
            <span class="bg-white cursor-pointer px-1 text-sm text-sky-400 font-bold">
                <vs-button color="#38bdf8" transparent :loading="loading" size="small">
                    {{child_count}} reply
                </vs-button>
            </span>
        </div>
        <div v-else @click="hideComments = !hideComments" class="w-full my-6 cursor-pointer border-b-2 border-b-sky-200 border-dashed h-1 relative flex flex-row items-center justify-center">
            <span class="bg-white cursor-pointer px-1 text-sm text-sky-400 font-bold">
                <vs-button color="#38bdf8" transparent size="small">
                    {{hideComments ? 'show' : 'hide'}}
                </vs-button>
            </span>
        </div>
    </div>
</template>

<script>
import LikeButton from "@/Components/LikeButton";
import ChildCommentItem from "@/Pages/Post/Show/Components/Comment/ChildCommentItem";

export default {
    name: "ChildComments",
    components: {
        LikeButton,
        ChildCommentItem
    },
    props: {
        comment: Object,
        postId: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {
            replyComments: [],
            page: 1,
            child_count: 0,
            hideComments: false,
            loading: false
        }
    },
    methods: {
        load() {
            let that = this;
            this.loading = true;
            axios.post(route('Post.getPostChildComment', {post: this.postId}), {
                comment_id: this.comment.id,
                page: this.page
            })
            .then(function (response) {
                that.replyComments = that.replyComments.concat(response.data.data)
                that.child_count = that.child_count - response.data.data.length;
                that.loading = false;
                that.page++;
            })
            .catch(function (error) {
                that.loading = false;
                console.log(error);
            });
        }
    },
    mounted() {
        this.child_count = this.comment.child_count;
    }
}
</script>

<style scoped>

.slide-enter-active {
    -moz-transition-duration: 0.5s;
    -webkit-transition-duration: 0.5s;
    -o-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -moz-transition-timing-function: ease-in;
    -webkit-transition-timing-function: ease-in;
    -o-transition-timing-function: ease-in;
    transition-timing-function: ease-in;
}

.slide-leave-active {
    -moz-transition-duration: 0.5s;
    -webkit-transition-duration: 0.5s;
    -o-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -moz-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    -webkit-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    -o-transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
    transition-timing-function: cubic-bezier(0, 1, 0.5, 1);
}

.slide-enter-to, .slide-leave {
    max-height: 100px;
    overflow: hidden;
}

.slide-enter, .slide-leave-to {
    overflow: hidden;
    max-height: 0;
}
</style>
