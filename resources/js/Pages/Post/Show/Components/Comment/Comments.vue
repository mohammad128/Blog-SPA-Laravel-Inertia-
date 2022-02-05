<template>
    <div class="flex flex-col gap-8">
        <CommentItem v-for="(item, index) in comments.data" :key="index" :comment="item" :post-id="postId"/>
        <div class="w-full pb-4">
            <ReplyComment :post-id="postId"/>
        </div>

    </div>
</template>

<script>
import CommentItem from "@/Pages/Post/Show/Components/Comment/CommentItem";
import ReplyComment from "@/Pages/Post/Show/Components/Comment/ReplyComment";

export default {
    name: "Comment",
    components: {
        CommentItem,
        ReplyComment
    },
    props: {
        comments: Object,
        postId: {
            type: Number,
            default: 0
        }
    },
    methods: {

    }
}
</script>

<style scoped>

</style>
