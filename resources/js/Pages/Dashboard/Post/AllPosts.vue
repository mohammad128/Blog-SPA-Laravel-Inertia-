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
            <div class="flex bg-white rounded-full">
                <a :class="{'bg-purple-200' : filters['post_type']==='all' }" class="bg-gray-100 rounded-l-full px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900" href="javascript:void(0)"
                    @click="post_type='all'" >
                    All
                </a>
                <a :class="{'bg-purple-200' : filters['post_type']==='trash' }" class="bg-gray-100 px-4 py-2 text-sm font-bold text-red-600 hover:text-red-800" href="javascript:void(0)"
                    @click="post_type='trash'" >
                    Trash
                </a>
                <a :class="{'bg-purple-200' : filters['post_type']==='draft' }" class="bg-gray-100 px-4 py-2 text-sm font-bold text-yellow-600 hover:text-yellow-400" href="javascript:void(0)"
                    @click="post_type='draft'" >
                    Draft
                </a>
                <a :class="{'bg-purple-200' : filters['post_type']==='published' }" class="bg-gray-100 rounded-r-full px-4 py-2 text-sm font-bold text-blue-600 hover:text-blue-800" href="javascript:void(0)"
                    @click="post_type='published'" >
                    Published
                </a>
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
            <CategoryCheckboxGroup :multiselect="true" :items="categories" v-model="selectedCat"/>
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
            <div>

                <vs-table
                v-model="selected"
                style="background: #fff"
                 >
                <template #thead>
                    <vs-tr>
                        <vs-th>
                            <vs-checkbox
                                :indeterminate="selected.length == posts.data.length" v-model="allCheck"
                                @change="selected = $vs.checkAll(selected, posts.data)"
                            />
                        </vs-th>
                        <vs-th sort @click="posts.data = $vs.sortData($event ,posts.data, 'name')">
                            Title
                        </vs-th>
                        <vs-th>
                            Author
                        </vs-th>
                        <vs-th>
                            Categories
                        </vs-th>
                        <vs-th>
                            Tags
                        </vs-th>
                        <vs-th>
                            <i class="text-2xl bx bxs-comment"></i>
                        </vs-th>
                        <vs-th>
                            Last Modified
                        </vs-th>
                    </vs-tr>
                </template>
                <template #tbody>
                    <vs-tr
                        :key="i"
                        v-for="(tr, i) in posts.data"
                        :data="tr"
                        :is-selected="!!selected.includes(tr)"
                        class="group"
                    >
                        <vs-td checkbox>
                            <vs-checkbox :val="tr" v-model="selected" />
                        </vs-td>
                        <vs-td class="font-bold text-lg">
                            {{ tr.title }}
                            <div class="pt-2 flex opacity-0 group-hover:opacity-100 duration-300 transition space-x-4 pl-2 ">
                                <a class="flex items-center text-xs text-gray-900 opacity-70 space-x-1 hover:opacity-100 " href="#" ><i class="text-lg bx bx-edit"></i> Edit</a>
                                <a class="flex items-center text-xs text-red-900 opacity-70 space-x-1 hover:opacity-100 " href="#" ><i class="text-lg bx bxs-trash-alt"></i> Delete</a>
                                <a class="flex items-center text-xs text-green-900 opacity-70 space-x-1 hover:opacity-100 " href="#" ><i class="text-lg bx bxs-show"></i> View</a>
                            </div>
                        </vs-td>
                        <vs-td>
                            {{ tr.user.name }}
                        </vs-td>
                        <vs-td>
                            {{ tr.categories.map( function(item){return item.name } ).join(', ') }}
                        </vs-td>
                        <vs-td>
                            {{ tr.tags.map( function(item){return item.name.en } ).join(', ') }}
                        </vs-td>
                        <vs-td>
                            <span>
                                <i class="relative bx bxs-comment text-4xl text-gray-900">
                                    <b class="absolute top-1.5 left-2.5 text-white text-xs">30</b>
                                </i>
                            </span>
                        </vs-td>
                        <vs-td>
                            {{tr.updated_at}}
                        </vs-td>
                    </vs-tr>
                </template>
                <template #notFound>
                    <span class="flex items-center justify-center space-x-2">
                        <b>Not Found Post</b><i class="text-3xl text-gray-800 bx bx-sleepy"></i>
                    </span>
                </template>

            </vs-table>

            </div>
            <div class="center relative">
                <vs-pagination not-margin v-model="page" :length="20" />
            </div>
            <div class="actions"></div>
        </div>

    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import CategoryCheckboxGroup from "@/Components/CategoryCheckboxGroup";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "AllPosts",
    components: {
        DashboardLayout,
        CategoryCheckboxGroup
    },
    props: {
        categories: Array,
        posts: Object,
        filters: Object
    },

    data:() => ({
        allCheck: false,
        selected: [],
        searchLoading: false,
        selectedCat: [57],
        fromDate: '',
        toDate: '',
        search: '',
        post_type: '',
        postPrePage: 15,
        page: 1,
        allow: false,
    }),
    methods: {
        joinObject(obj, key, spliter=',') {
            console.log(spliter)
        },
        doFilter() {
            let data = {};
            // if(this.selectedCat !== [] )
            //     data['selectedCat'] = this.selectedCat.join(',');
            if(this.fromDate !== '' )
                data['fromDate'] = this.fromDate;
            if(this.toDate !== '' )
                data['toDate'] = this.toDate;
            if(this.search !== '' )
                data['search'] = this.search;
            if(this.post_type !== '' )
                data['post_type'] = this.post_type;
            if(this.postPrePage !== 15 )
                data['postPrePage'] = this.postPrePage;
            if(this.page !== 1 )
                data['page'] = this.page;

            Inertia.get(route('dashboard.post.allPosts'), data, {
                preserveState: true,
                preserveScroll: true,
            });
        }
    },
    beforeMount() {
        this.allow = false;
        this.$store.state.dashboard.activeSidebarItem = 'Post_All_Posts';

        // this.selectedCat = this.filters['selectedCat'];
        this.fromDate = this.filters['fromDate'];
        this.toDate = this.filters['toDate'];
        this.search = this.filters['search'];
        this.post_type = this.filters['post_type'];
        this.postPrePage = parseInt(this.filters['postPrePage']);
        this.page = this.filters['page'];

        this.allow = true;
    },
    watch: {
        selectedCat: function(val, oldVal) {
            if (!this.allow) return;
            // this.doFilter();
            console.log('selectedCat:',val)
        },
        fromDate: function(val, oldVal) {
            if (!this.allow) return;
            this.doFilter();
            console.log('fromDate:',val)
        },
        toDate: function(val, oldVal) {
            if (!this.allow) return;
            this.doFilter();
            console.log('toDate:',val)
        },
        search: function(val, oldVal) {
            if (!this.allow) return;
            this.doFilter();
            console.log('search:',val)
        },
        post_type: function(val, oldVal) {
            if (!this.allow) return;
            this.doFilter();
            console.log('post_type:',val)
        },
        postPrePage: function(val, oldVal) {
            if (!this.allow) return;
            this.doFilter();
            console.log('postPrePage:',val)
        },
        page: function(val, oldVal) {
            if (!this.allow) return;
            this.doFilter();
            console.log('page:',val)
        },
    }
}
</script>

<style scoped>

</style>
