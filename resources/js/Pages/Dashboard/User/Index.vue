<template>
    <DashboardLayout>
        <Head>
            <title>Manage All Posts</title>
            <meta name="description" head-key="description" content="Manage All Posts">
        </Head>

        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
            <div class="flex space-x-4 flex space-x-4">
                <div class="text-5xl font-extrabold">
                      <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">
                        Posts
                      </span>
                </div>
                <vs-button
                    circle
                    icon
                    floating
                    @click="link($event)"
                    method="get"
                    :url="route('dashboard.post.create')"
                >
                    <i class='bx bx-plus' ></i>
                </vs-button>
            </div>
        </div>

        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col-reverse gap-4 md:flex-row flex lg:space-x-4 bg-white p-4 rounded-2xl">
            <div class="flex">
                <vs-input
                    placeholder="Search"
                    v-model="search"
                    type="search"
                    :loading="searchLoading"
                >
                    <template #icon>
                        <i class='bx bx-search'></i>
                    </template>
                </vs-input>
                <span class="w-5"></span>

                <vs-select
                    placeholder="Pre Page"
                    v-model="postPrePage"
                    style="width: 100px"
                >
                    <vs-option label="15" value="15">
                        15
                    </vs-option>
                    <vs-option label="20" value="20">
                        20
                    </vs-option>
                    <vs-option label="50" value="50">
                        50
                    </vs-option>
                    <vs-option label="99" value="99">
                        99
                    </vs-option>
                </vs-select>

                <span class="w-5"></span>
                <span class="text-sm text-gray-400 pl-5 pt-3">total: 222</span>
            </div>
        </div>

        <div class="flex justify-center lg:justify-end mb-8 flex-row lg:flex-col-reverse gap-4 md:flex-row bg-white p-4 rounded-2xl">

            <div class="flex gap-4 flex-col lg:flex-row items-center ">
                <div class="flex gap-2 items-center">
                    <span class="text-xs text-gray-700">From:</span>
                    <vs-input
                        v-model="fromDate"
                        type="date"
                    />
                </div>
                <div class="flex gap-2 items-center">
                    <span class="text-xs text-gray-700">To:</span>
                    <vs-input
                        v-model="toDate"
                        type="date"
                    />
                </div>
            </div>
        </div>

        <div class="flex flex-col space-y-10">
            <div class="relative">


                <transition name="bounce">
                    <div class="absolute -bottom-8 -left-6"  v-if="selected.length">
                        <vs-button floating icon danger  @click="deleteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>
                <transition name="bounce">
                    <div class="absolute -top-8 -left-6"  v-if="selected.length">
                        <vs-button floating icon danger  @click="deleteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>

            </div>
            <div class="center relative">
                <vs-pagination not-margin v-model="page" :length="users.last_page" />
            </div>
            <div class="actions"></div>
        </div>

    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "AllPosts",
    components: {
        DashboardLayout,
    },
    props: {
        users: Object
    },
    data:() => ({
        page: 1,
        selected: [],
        fromDate: '',
        toDate: '',
    }),
    methods: {
        doFilter() {
        },
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Users_All_Users';
    },
    created() {
    },
    watch: {
    }
}
</script>

<style scoped>
.bounce-enter-active {
    animation: bounce-in .5s;
}
.bounce-leave-active {
    animation: bounce-in .5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(1);
        opacity: 0;
    }
    50% {
        transform: scale(1.5);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

