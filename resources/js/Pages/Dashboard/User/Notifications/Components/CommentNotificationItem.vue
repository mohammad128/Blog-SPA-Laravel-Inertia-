<template>
    <div class="relative flex flex-row justify-start gap-2 cursor-pointer hover:bg-gray-100 rounded-lg p-4" @click="link($event)" method="get" :url="data.data.modelUrl">
        <span v-if="!data.read_at" class="bg-sky-600 text-white transform -rotate-45 text-xs font-bold rounded-lg py-1 px-2 absolute top-2 left-0">New</span>

        <div class="w-12 h-12 overflow-hidden">
            <i v-if="data.data.comment.status == 'pending'" class="bx bx-comment-dots font-bold text-4xl text-gray-600"></i>
            <i v-else-if="data.data.comment.status == 'approve'" class="bx bx-comment-check font-bold text-4xl text-green-600"></i>
            <i v-else-if="data.data.comment.status == 'spam'" class="bx bx-comment-error font-bold text-4xl text-yellow-600"></i>
            <i v-else-if="data.data.comment.status == 'deleted'" class="bx bx-comment-x font-bold text-4xl text-red-600"></i>
        </div>
        <div class="h-full flex flex-col justify-between">
            <p class=" " :class="[data.read_at ? 'text-gray-900' : 'text-gray-800 font-bold']">
                <span class="flex flex-row gap-3 items-center">
                    <span>{{ data.data.comment.content }}</span>
                    <span v-if="data.data.comment.status == 'pending'" class="text-xs px-2 py-1 rounded-lg bg-gray-800 text-white">Pending</span>
                    <span v-else-if="data.data.comment.status == 'approve'" class="text-xs px-2 py-1 rounded-lg bg-green-600 text-white">Approve</span>
                    <span v-else-if="data.data.comment.status == 'spam'" class="text-xs px-2 py-1 rounded-lg bg-yellow-600 text-white">Spam</span>
                    <span v-else-if="data.data.comment.status == 'deleted'" class="text-xs px-2 py-1 rounded-lg bg-red-600 text-white">Deleted</span>
                </span>
            </p>
            <span class="text-gray-500 text-xs">{{data.dif_for_human}}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "CommentNotificationItem",
    props: {
        data: Object
    }
}
</script>

<style scoped>

</style>
