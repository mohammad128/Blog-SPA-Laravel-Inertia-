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
            <div class="flex bg-white rounded-full divide-x divide-gray-300">
                <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['post_type']==='all' | filters['post_type']==='' }" class="bg-gray-100 rounded-l-full px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900" href="javascript:void(0)"
                    @click="post_type='all'" >
                    All <span class="text-xs text-gray-600">({{post_count.all}})</span>
                </a>
                <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['post_type']==='published' }" class="bg-gray-100 rounded-r-full px-4 py-2 text-sm font-bold text-blue-600 hover:text-blue-800" href="javascript:void(0)"
                   @click="post_type='published'" >
                    Published <span class="text-xs text-gray-600">({{post_count.published}})</span>
                </a>
                <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['post_type']==='draft' }" class="bg-gray-100 px-4 py-2 text-sm font-bold text-yellow-600 hover:text-yellow-700" href="javascript:void(0)"
                   @click="post_type='draft'" >
                    Draft <span class="text-xs text-gray-600">({{post_count.draft}})</span>
                </a>
                <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['post_type']==='trash' }" class="bg-gray-100 px-4 py-2 text-sm font-bold text-red-600 hover:text-red-800" href="javascript:void(0)"
                    @click="post_type='trash'" >
                    Trash <span class="text-xs text-gray-600">({{post_count.trash}})</span>
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
            <div class="relative">


                <transition name="bounce">
                    <div class="absolute -bottom-8 -left-6 flex flex-row gap-2"  v-if="selected.length">
                        <template v-if="post_type === 'trash'">
                            <vs-button  v-if="can('force_delete_post')" floating icon danger  @click="deleteSelected()" :loading="loading">
                                Delete ({{selected.length}})
                            </vs-button>
                            <vs-button floating icon warn  @click="restoreSelected()" :loading="loading">
                                Restore ({{selected.length}})
                            </vs-button>
                        </template>
                        <template v-else>
                            <vs-button  v-if="can('delete_post')" floating icon danger  @click="deleteSelected()" :loading="loading">
                                Delete ({{selected.length}})
                            </vs-button>
                        </template>
                    </div>
                </transition>
                <transition name="bounce">
                    <div class="absolute -top-8 -left-6 flex flex-row gap-4"  v-if="selected.length">
                        <template v-if="post_type === 'trash'">
                            <vs-button  v-if="can('force_delete_post')" floating icon danger  @click="deleteSelected()" :loading="loading">
                                Delete ({{selected.length}})
                            </vs-button>
                            <vs-button v-if="can('restore_post')" floating icon warn  @click="restoreSelected()" :loading="loading">
                                Restore ({{selected.length}})
                            </vs-button>
                        </template>
                        <template v-else>
                            <vs-button  v-if="can('delete_post')" floating icon danger  @click="deleteSelected()" :loading="loading">
                                Delete ({{selected.length}})
                            </vs-button>
                        </template>
                    </div>
                </transition>

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
                        <vs-th sort ref="th_title" @click="sort($event, 'title')">
                            Title
                        </vs-th>
                        <vs-th sort ref="th_author" @click="sort($event, 'author')">
                            Author
                        </vs-th>
                        <vs-th>
                            Categories
                        </vs-th>
                        <vs-th>
                            Tags
                        </vs-th>
                        <vs-th sort ref="th_comment_count" @click="sort($event, 'comment_count')">
                            <i class="text-2xl bx bxs-comment"></i>
                        </vs-th>
                        <vs-th sort ref="th_created_at" @click="sort($event, 'created_at')">
                            Created At
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
                            {{ tr.title }}&nbsp;
                            <span v-if="tr.user_id == $page.props.user.id" class="text-white rounded-lg p-1 text-xs font-bold bg-green-700">for you</span>
                            <span v-if="tr.draft" class="text-xs text-gray-700 bg-amber-200 px-2 py-1 rounded-lg">draft</span>
                            <span v-else class="text-xs text-gray-700 bg-lime-200 px-2 py-1 rounded-lg">publish</span>

                            <div class="pt-2 flex opacity-0 group-hover:opacity-100 duration-300 transition space-x-4 pl-2 ">
                                <a v-if="post_type!='trash' && ((can('edit_post') && tr.user_id == $page.props.user.id) || can('edit_all_post') )"
                                   class="flex items-center text-xs text-gray-900 opacity-70 space-x-1 hover:opacity-100 " href="javascript:void(0)"
                                   @click="link($event)" method="get" :url="route('dashboard.post.edit', {'post':tr.slug})"><i class="text-lg bx bx-edit"></i> Edit</a>

                                <a v-if="post_type=='trash' && ((can('force_delete_post') && tr.user_id == $page.props.user.id) || can('force_delete_all_post') )"
                                   class="flex items-center text-xs text-red-900 opacity-70 space-x-1 hover:opacity-100 " href="javascript:void(0)"
                                   @click="link($event)" method="delete" :url="route('dashboard.post.forceDelete', {'post': tr.id})"><i class="text-lg bx bxs-trash-alt"></i> Delete</a>
                                <a v-else-if="post_type!='trash' && ((can('delete_post') && tr.user_id == $page.props.user.id) || can('delete_all_post') )" class="flex items-center text-xs text-red-900 opacity-70 space-x-1 hover:opacity-100 " href="javascript:void(0)"
                                    @click="link($event)" method="delete" :url="route('dashboard.post.delete', {'post': tr.id})"><i class="text-lg bx bxs-trash-alt"></i> Delete</a>

                                <a v-if="post_type=='trash' && ((can('restore_post') && tr.user_id == $page.props.user.id) || can('restore_all_post') )" class="flex items-center text-xs text-yellow-700 opacity-70 space-x-1 hover:opacity-100 " href="javascript:void(0)"
                                   @click="link($event)" method="get" :url="route('dashboard.post.restore', {'id': tr.id})"><i class="text-lg bx bxs-trash-alt"></i> Restore</a>

                                <a v-if="post_type!='trash' && can('read_post')" class="flex items-center text-xs text-green-900 opacity-70 space-x-1 hover:opacity-100 " href="javascript:void(0)"
                                   @click="link($event)" method="get" :url="route('Post.show', {'post': tr.slug})"><i class="text-lg bx bxs-show"></i> View</a>
                            </div>
                        </vs-td>
                        <vs-td>
                            {{ tr.user.username }}
                        </vs-td>
                        <vs-td>
                            {{ tr.categories.map( function(item){return item.name } ).join(', ') }}
                        </vs-td>
                        <vs-td>
                            {{ tr.tags.map( function(item){return item.name.en } ).join(', ') }}
                        </vs-td>
                        <vs-td>
                            <vs-tooltip left>
                                <i class="relative bx bxs-comment text-4xl text-gray-900">
                                    <b class="absolute top-1.5 left-2.5 text-white text-xs">{{ tr.approvedComments }}</b>
                                    <span class="blink absolute -top-1 -left-1" v-if="tr.pendingComments"></span>
                                </i>
                                <template #tooltip>
                                    <div class="content-tooltip text-left">
                                        <p class="text-xs text-lime-300">
                                            Approved: {{ tr.approvedComments }}
                                        </p>
                                        <p class="text-xs text-yellow-300">
                                            Pending: {{ tr.pendingComments }}
                                        </p>
                                        <p class="text-xs text-red-300">
                                            Spam: {{ tr.sapmComments }}
                                        </p>
                                        <p class="text-xs text-red-400">
                                            Trash: {{ tr.trashComments }}
                                        </p>
                                    </div>
                                </template>
                            </vs-tooltip>
                        </vs-td>
                        <vs-td>
                            {{tr.updated_at.substring(0,10)}}<br><span class="text-xs text-gray-600">{{tr.updated_at_for_human}}</span>
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
                <vs-pagination not-margin v-model="page" :length="posts.last_page" />
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
        filters: Object,
        post_count: Object
    },

    data:() => ({
        loading: false,
        allCheck: false,
        selected: [],
        searchLoading: false,
        selectedCat: [],
        fromDate: '',
        toDate: '',
        search: '',
        post_type: '',
        postPrePage: 15,
        page: 1,
        allow: false,
        sortKey: '',
        sortType: '',
    }),
    methods: {
        sort(event, sortKey, type) {

            var sortType = type || 'desc';
            var el = event.target;

            if (el.dataset["sortType" + sortKey] == 'desc') {
                sortType = 'asc';
            } else if (el.dataset["sortType" + sortKey] == 'asc') {
                sortType = null;
            }

            el.dataset["sortType" + sortKey] = sortType;
            el.dataset["sortType"] = sortType;
            el.dataset["sortKey"] = "sortType" + sortKey;
            var parent = el.closest('.vs-table__tr');
            var ths = parent.querySelectorAll('th.sort');
            ths.forEach(function (th) {
                if (th != el) {
                    th.dataset.sortType = null;
                    th.dataset[th.dataset["sortKey"]] = null;
                }
            });
            this.sortKey = sortKey;
            this.sortType = sortType;
        },
        doFilter() {
            let data = {};
            // if(this.selectedCat.length > 0 )
            //     data['selectedCat'] = this.selectedCat.join('-');
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
            if(this.sortKey && this.sortType ) {
                data['sortKey'] = this.sortKey;
                data['sortType'] = this.sortType;
            }

            Inertia.get(route('dashboard.post.allPosts'), data, {
                preserveState: true,
                preserveScroll: true,
            });
        },
        deleteSelected() {
            this.loading=true;
            let url;
            if(this.post_type === 'trash')
                url = route('dashboard.post.multiForceDelete');
            else url = route('dashboard.post.multiDelete');
            Inertia.post(url, {'ids':this.selected.map(item=>item['id'])}, {
                preserveState: false,
                preserveScroll: false,
                onFinish() {
                    this.loading = false;
                    this.selected = [];
                }
            })
        },
        restoreSelected() {
            this.loading=true;
            Inertia.post(route('dashboard.post.multiRestore'), {'ids':this.selected.map(item=>item['id'])}, {
                preserveState: false,
                preserveScroll: false,
                onFinish() {
                    this.loading = false;
                    this.selected = [];
                }
            })
        },
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Post_All_Posts';
    },
    mounted(){
        let el = null;
        switch(this.sortKey) {
            case 'title':
                el = this.$refs.th_title.$el;
                break;
            case 'author':
                el = this.$refs.th_author.$el;
                break;
            case 'comment_count':
                el = this.$refs.th_comment_count.$el;
                break;
            case 'created_at':
                el = this.$refs.th_created_at.$el;
                break;
        }
        if( el ) {
            el.dataset["sortType" + this.sortKey] = this.sortType;
            el.dataset["sortType"] = this.sortType;
            el.dataset["sortKey"] = "sortType" + this.sortKey;
        }
    },
    created() {
        this.allow = false;
        if( this.filters['selectedCat'] != '' ) {
            let tmp = this.filters['selectedCat'].split("-");
            let tmp2 = [];
            for(let i=0; i<tmp.length;i++)
                tmp2.push( parseInt( tmp[i] ) );
            this.selectedCat = tmp2;
            console.log( 'created . selectedCat2',this.selectedCat ,this.filters['selectedCat']);
        }
        this.fromDate = this.filters['fromDate'];
        this.toDate = this.filters['toDate'];
        this.search = this.filters['search'];
        this.post_type = this.filters['post_type'];
        this.postPrePage = parseInt(this.filters['postPrePage']);
        this.page = this.filters['page'];
        this.sortType = this.filters['sortType'];
        this.sortKey = this.filters['sortKey'];

        let that = this;
        setInterval(function () {
            that.allow = true;
        }, 1000)
    },
    watch: {
        selectedCat: {
            handler(val, oldVal) {
                if (!this.allow) return;
                console.log("selectedCat3", this.selectedCat);
                // if(this.selectedCat.length) {
                //     console.log("selectedCat3", this.selectedCat);
                //     console.log("selectedCat3", this.selectedCat.join('-'));
                // }
                this.doFilter();
            },
            deep: true
        },
        fromDate: function(val, oldVal) {
            if (!this.allow) return;
            this.page = 1;
            this.doFilter();
            console.log('fromDate:',val)
        },
        toDate: function(val, oldVal) {
            if (!this.allow) return;
            this.page = 1;
            console.log('toDate:',val);
            this.doFilter();
        },
        search: function(val, oldVal) {
            if (!this.allow) return;
            this.page = 1;
            console.log('search:',val);
            this.doFilter();
        },
        post_type: function(val, oldVal) {
            if (!this.allow) return;
            this.page = 1;
            console.log('post_type:',val);
            this.doFilter();
        },
        postPrePage: function(val, oldVal) {
            if (!this.allow) return;
            this.page = 1;
            console.log('postPrePage:',val);
            this.doFilter();
        },
        page: function(val, oldVal) {
            if (!this.allow) return;
            console.log('page:',val);
            this.doFilter();
        },
        sortKey: function () {
            if (!this.allow) return;
            this.doFilter();
        },
        sortType: function f() {
            if (!this.allow) return;
            this.doFilter();
        },
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

@keyframes blinkAnimation {
    0% {
        background-color: transparent;
    }
    100% {
        background-color: #fde047;
    }
}
.blink {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    animation-name: blinkAnimation;
    animation-duration: .5s;
    animation-direction: alternate-reverse;
    animation-iteration-count: infinite;
}
</style>

