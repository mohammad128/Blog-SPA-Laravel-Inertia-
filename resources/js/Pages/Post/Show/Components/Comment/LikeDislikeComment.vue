<template>

    <div class="flex flex-row gap-2">
        <div class="flex flex-col justify-center">
            <LikeButton  @handleClick="handleLike($event)" :active="(comment.user_like_status && comment.user_like_status.like) ? true : false"/>
            <span class="text-xs text-gray-700">{{ comment.like_status ? comment.like_status.like_count : '0' }}</span>
        </div>
        <div class="flex flex-col justify-center">
            <LikeButton  @handleClick="handleDislike($event)" button-type="dislike" :active="(comment.user_like_status && comment.user_like_status.dislike) ? true : false"/>
            <span class="text-xs text-gray-700">{{ comment.like_status ? comment.like_status.dislike_count : '0' }}</span>
        </div>
    </div>

</template>

<script>
import LikeButton from "@/Components/LikeButton";

export default {
    name: "LikeDislikeComment",
    components:{
        LikeButton,
    },
    props: {
        comment: {
            type: Object,
            default: null
        },
        postId: {
            type: Number,
            default: 0
        }
    },
    methods: {
        handleLike(active) {
            this.$inertia.post(route('Post.likeComment', {post: this.postId}), {
                type: 'like',
                active: active,
                comment_id: this.comment.id
            }, {
                preserveState: true,
                preserveScroll: true,
            })
        },
        handleDislike(active) {
            this.$inertia.post(route('Post.likeComment', {post: this.postId}), {
                type: 'dislike',
                active: active,
                comment_id: this.comment.id
            }, {
                preserveState: true,
                preserveScroll: true,
            })
        }
    }
}
</script>

<style scoped>

</style>
