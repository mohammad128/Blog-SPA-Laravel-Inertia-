<template>

<!--    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 md:gap-4 gap-2 items-center justify-center">-->
    <div class="flex flex-col md:flex-row md:gap-4 gap-2 items-center justify-center">

        <div v-if="data.post" class="w-full flex flex-col bg-white rounded-full shadow-lg">
            <vs-button size="xl" circle transparent color="#333" border
                       @click="link($event)" method="get" :url="route('dashboard.post.allPosts')"
            >
                <i class="text-2xl bx bxs-dock-top"></i>&nbsp;&nbsp; {{data.post}} Post
            </vs-button>
        </div>
        <div v-if="data.page" class="w-full flex flex-col bg-white rounded-full shadow-lg">
            <vs-button size="xl" circle transparent color="#333" border
                       @click="link($event)" method="get" :url="route('dashboard.page.index')"
            >
                <i class="text-2xl bx bxs-copy-alt"></i>&nbsp;&nbsp; {{data.page}} Page
            </vs-button>
        </div>
        <div v-if="data.user" class="w-full flex flex-col bg-white rounded-full shadow-lg">
            <vs-button size="xl" circle transparent color="#333" border
                       @click="link($event)" method="get" :url="route('dashboard.user.index')"
            >
                <i class="text-2xl bx bxs-user"></i>&nbsp;&nbsp; {{data.user}} User
            </vs-button>
        </div>
        <div v-if="data.comment" class="w-full flex flex-col bg-white rounded-full shadow-lg">
            <vs-button size="xl" circle transparent color="#333" border
                       @click="link($event)" method="get" :url="route('dashboard.comment.index')"
            >
                <i class="text-2xl bx bxs-comment"></i>&nbsp;&nbsp; {{data.comment}} Comment
            </vs-button>
        </div>
    </div>

</template>

<script>
export default {
    name: "Counts",
    props: {
        data: Object
    }
}
</script>

<style scoped>

</style>
