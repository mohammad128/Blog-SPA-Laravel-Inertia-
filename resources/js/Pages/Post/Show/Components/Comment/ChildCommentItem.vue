<template>
    <div class="flex flex-col">
        <div class="flex flex-row-reverse items-center gap-6" >
            <div class="avatar flex flex-col w-16 justify-center items-center">
                <vs-avatar circle size="32"  history :success="$page.props.user && ($page.props.user.id == comment.user.id)">
                    <img :src="comment.user.profile_photo_url" alt="">
                </vs-avatar>
                <div class="flex flex-col items-center w-full">
                    <span class="text-xs text-gray-700 line-clamp-1 text-center w-full">{{ comment.user.name }}</span>
                    <span class="text-xs text-gray-400 line-clamp-1 text-center w-full">{{ comment.user.roles.map(item=>item.name).join(',') }}</span>
                </div>
            </div>
            <div class="comment-content flex-1 bg-gray-100 justify-end items-end p-4 flex flex-col">
                <div class="text-sm text-gray-700" v-html="comment.content"></div>

                <div class="inline-block relative flex flex-row-reverse gap-4 items-center justify-center shrink-0 transform scale-75">
                    <LikeDislikeComment :comment="comment" :post-id="postId"/>

                    <a class="text-sm"  :class="{'text-rose-600':activeReply, 'text-sky-600':!activeReply}"  href="javascript:void(0)" @click.s.stop="activeReply = !activeReply">
                        {{ !activeReply ? 'Reply ' : 'Cancel' }}
                    </a>
                </div>
            </div>
        </div>
        <transition name="slide">
            <ReplyComment v-if="activeReply" @commentSend="activeReply=!activeReply" :comment="comment" :post-id="postId"/>
        </transition>

    </div>
</template>

<script>
import LikeDislikeComment from "@/Pages/Post/Show/Components/Comment/LikeDislikeComment";
import ReplyComment from "@/Pages/Post/Show/Components/Comment/ReplyComment";

export default {
    name: "ChildCommentItem",
    components: {
        LikeDislikeComment,
        ReplyComment
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
            activeReply: false,
        }
    },
    methods: {
        handleLike(active) {
            console.log(active);
        },
        handleDislike(active) {
            console.log(active);
        }
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
