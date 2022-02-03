<template>
    <div class="flex flex-row min-h-screen w-full items-center justify-center gap-6">
        <LikeButton @handleClick="handleLike($event)"/>
        <LikeButton @handleClick="handleDislike($event)" button-type="dislike"/>
    </div>
</template>
<script>
import LikeButton from "@/Components/LikeButton";
export default {
    components: {
        LikeButton
    },
    props: {
    },
    data() {
        return {
        }
    },
    methods: {
        handleLike(active) {
            console.log('Like', active);
        },
        handleDislike(active) {
            console.log('DisLike', active);
        },
    },
    watch: {
    },
    created() {

    },
    mounted() {
    }
}
</script>
<style lang="scss">
</style>

