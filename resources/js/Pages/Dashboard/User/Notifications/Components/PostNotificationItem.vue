<template>
    <div class="relative flex flex-row justify-start gap-2 cursor-pointer hover:bg-gray-100 rounded-lg p-4" @click="link($event)" method="get" :url="data.data.url">

        <span v-if="!data.read_at" class="bg-sky-600 text-white transform -rotate-45 text-xs font-bold rounded-lg py-1 px-2 absolute top-2 left-0">New</span>

        <div class="rounded-full w-12 h-12 overflow-hidden">
            <img class="h-full w-full" :src="data.data.feature_image" alt="">
        </div>
        <div class="h-full flex flex-col justify-between">
            <p class=" " :class="[data.read_at ? 'text-gray-900' : 'text-gray-800 font-bold']">
                {{ data.data.title }}
            </p>
            <span class="text-gray-500 text-xs">{{data.dif_for_human}}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "PostNotificationItem",
    props: {
        data: Object
    }
}
</script>

<style scoped>

</style>
