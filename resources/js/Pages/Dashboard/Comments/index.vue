<template>
    <DashboardLayout>

        <Head>
            <title>Manage Comments</title>
            <meta name="description" head-key="description" content="Mange Comments">
        </Head>

        <div class="flex flex-col w-full">

            <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
                <div class="flex space-x-4 flex space-x-4">
                    <div class="text-5xl font-extrabold">
                      <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">
                        Comments
                      </span>
                    </div>
                </div>

                <div class="flex bg-white rounded-full divide-x divide-gray-300">
                    <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['comment_type']==='all' | !filters['comment_type'] }"  class="bg-gray-100 rounded-l-full px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900" href="javascript:void(0)"
                       @click="comment_type='all'" >
                        All <span class="text-xs text-gray-600">({{ comments_count.all }})</span>
                    </a>
                    <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['comment_type']==='pending' }"  class="bg-gray-100 px-4 py-2 text-sm font-bold text-yellow-600 hover:text-yellow-700" href="javascript:void(0)"
                       @click="comment_type='pending'" >
                        Pending <span class="text-xs text-gray-600">({{ comments_count.pending }})</span>
                    </a>
                    <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['comment_type']==='approved' }"  class="bg-gray-100 px-4 py-2 text-sm font-bold text-lime-600 hover:text-lime-800" href="javascript:void(0)"
                       @click="comment_type='approved'" >
                        Approved <span class="text-xs text-gray-600">({{ comments_count.approved }})</span>
                    </a>
                    <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['comment_type']==='spam' }"  class="bg-gray-100 px-4 py-2 text-sm font-bold text-red-600 hover:text-red-700" href="javascript:void(0)"
                       @click="comment_type='spam'" >
                        Spam <span class="text-xs text-gray-600">({{ comments_count.spam }})</span>
                    </a>
                    <a :class="{'bg-indigo-100 transform -translate-y-1' : filters['comment_type']==='trash' }" class="bg-gray-100 rounded-r-full px-4 py-2 text-sm font-bold text-rose-600 hover:text-rose-800" href="javascript:void(0)"
                       @click="comment_type='trash'" >
                        Trash <span class="text-xs text-gray-600">({{ comments_count.trash }})</span>
                    </a>
                </div>
            </div>

            <div class="center relative">

                <transition name="bounce">
                    <div class="absolute flex flex-row -top-8 -left-6"  v-if="selected.length && comment_type!='trash'">
                        <vs-button floating icon danger size="small" @click="trashSelected()" :loading="loading">
                            Trash ({{selected.length}})
                        </vs-button>
                        <vs-button floating icon dark size="small" @click="spamSelected()" :loading="loading">
                            Spam ({{selected.length}})
                        </vs-button>
                        <vs-button floating icon success size="small" @click="approveSelected()" :loading="loading">
                            Approve ({{selected.length}})
                        </vs-button>
                    </div>
                    <div class="absolute flex flex-row -top-8 -left-6"  v-else-if="selected.length && comment_type=='trash'">
                        <vs-button floating icon danger size="small" @click="delteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                        <vs-button floating icon warn size="small" @click="restoreSelected()" :loading="loading">
                            Restore ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>
                <transition name="bounce">
                    <div class="absolute flex flex-row -bottom-8 -left-6"  v-if="selected.length && comment_type!='trash'">
                        <vs-button floating icon danger size="small" @click="trashSelected()" :loading="loading">
                            Trash ({{selected.length}})
                        </vs-button>
                        <vs-button floating icon dark size="small" @click="spamSelected()" :loading="loading">
                            Spam ({{selected.length}})
                        </vs-button>
                        <vs-button floating icon success size="small" @click="approveSelected()" :loading="loading">
                            Approve ({{selected.length}})
                        </vs-button>
                    </div>
                    <div class="absolute flex flex-row -bottom-8 -left-6"  v-else-if="selected.length && comment_type=='trash'">
                        <vs-button floating icon danger size="small" @click="delteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                        <vs-button floating icon warn size="small" @click="restoreSelected()" :loading="loading">
                            Restore ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>

                <vs-table
                    v-model="selected"
                    class="bg-white"
                >
                    <template #header>
                        <div class="flex gap-8">
                            <div class="flex-1">
                                <vs-input v-model="search" type="search"
                                          state="primary" placeholder="Search" >
                                    <template #icon>
                                        <i class='bx bx-search'></i>
                                    </template>
                                </vs-input>
                            </div>
                            <div>
                                <vs-select
                                    placeholder="Pre Page"
                                    v-model="prePage"
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
                            </div>
                            <div class="flex gap-4 flex-col lg:flex-row items-center ">
                                <div class="flex gap-2 items-center">
                                    <span class="text-xs text-gray-700">From:</span>
                                    <vs-input state="primary"
                                              v-model="fromDate"
                                        type="date"
                                    />
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="text-xs text-gray-700">To:</span>
                                    <vs-input state="primary"
                                              v-model="toDate"
                                        type="date"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #thead>
                        <vs-tr>
                            <vs-th>
                                <vs-checkbox
                                    :indeterminate="selected.length == comments.data.length" v-model="allCheck"
                                    @change="selected = $vs.checkAll(selected, comments.data)"
                                />
                            </vs-th>
                            <vs-th sort ref="th_name" @click="sort($event, 'name')">
                                Name
                            </vs-th>
                            <vs-th sort ref="th_comment" @click="sort($event, 'comment')">
                                Comment
                            </vs-th>
                            <vs-th sort ref="th_date" @click="sort($event, 'date')">
                                Date
                            </vs-th>
                            <vs-th sort ref="th_status" @click="sort($event, 'status')">
                                Status
                            </vs-th>
                        </vs-tr>
                    </template>
                    <template #tbody>
                        <vs-tr
                            :key="i"
                            v-for="(tr, i) in comments.data"
                            :data="tr"
                            :is-selected="!!selected.includes(tr)"
                            not-click-selected
                            open-expand-only-td
                            :class="{
                                'bg-amber-100': tr.status=='pending',
                                'bg-red-100': tr.status=='spam',
                            }"
                        >
                            <vs-td checkbox>
                                <vs-checkbox :val="tr" v-model="selected" />
                            </vs-td>
                            <vs-td class="text-xs font-bold text-gray-800">
                                {{ tr.user.name }}
                            </vs-td>
                            <vs-td class="flex items-center no-underline text-xs font-bold" >
                                {{ tr.content }}
                            </vs-td>
                            <vs-td>
                                {{ tr.created_at.substring(0,10) }} &#8626;
                            </vs-td>
                            <vs-td>
                                <span :class="{
                                        'bg-amber-500': tr.status=='pending',
                                        'bg-lime-500': tr.status=='approve',
                                        'bg-red-500': tr.status=='spam',
                                    }"
                                    class="text-xs rounded-lg font-bold p-1 text-white">{{tr.status}}</span>
                            </vs-td>
                            <template #expand>
                                <div class="con-content flex flex-row justify-between bg-gray-200 p-4 divide-x">
                                    <div class="flex flex-row items-center gap-4">
                                        <vs-avatar>
                                            <img :src="tr.user.profile_photo_url" alt="">
                                        </vs-avatar>
                                        <div class="flex flex-col">
                                            <span class="text-md font-bold text-gray-900">{{ tr.user.name }}</span>
                                            <span class="text-xs text-gray-600">{{ tr.user.email }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 px-4 mx-4 text-xs">
                                        <div class="flex flex-col">
                                            <div class="flex flex-row items-center">
                                                &nbsp;&nbsp; <span class="line-clamp-1 text-sm font-bold text-sky-500">{{tr.commentable.title}}</span>
                                                <vs-button circle  size="small" floating @click="link($event)" method="get" :url="tr.commentable.url" >
                                                    <i class="font-bold text-xl bx bxs-show"></i>
                                                </vs-button>
                                            </div>
                                            <div v-if="tr.parent" class="flex flex-row items-center gap-2">
                                                <span  class="text-xs text-sky-700 my-2">In reply to {{tr.parent.user.email}}</span>
                                                <div class="text-gray-800 line-clamp-1">[{{tr.parent.content}}]</div>
                                            </div>
                                            <div class="bg-white p-4 mb-2" @click="edit = tr, editActive = true">
                                                {{ tr.content }}
                                                <br><span class="text-xs font-bold">{{ tr.created_at.substring(0,10) }}</span>
                                                <span class="text-xs text-gray-500">({{tr.created_at_for_human}})</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-row justify-between items-center ">
                                            <div class="flex flex-row">
                                            </div>
                                            <div  class="flex flex-row justify-end items-center" v-if="comment_type!='trash'">
                                                <div v-if="tr.status != 'approve'">
                                                    <vs-button floating success @click="updateComment(tr.id, {'status': 'approve'})">
                                                        <i class="bx bx-check text-lg font-bold"></i> Approve
                                                    </vs-button>
                                                </div>
                                                <div v-if="tr.status != 'spam'">
                                                    <vs-button
                                                        color="#ef4444" active size="small" @click="updateComment(tr.id, {'status': 'spam'})">
                                                        <i class="bx bx-bug text-lg font-bold"></i> Spam
                                                    </vs-button>
                                                </div>
                                                <div>
                                                    <vs-button active size="small" @click="edit = tr, editActive = true">
                                                        <i class="bx bx-comment-edit text-lg font-bold"></i> Edit
                                                    </vs-button>
                                                </div>
                                                <div>
                                                    <vs-button danger active size="small" @click="deleteComment(tr.id)" transparent>
                                                        <i class="bx bx-trash text-lg font-bold"></i> Delete
                                                    </vs-button>
                                                </div>
                                            </div>
                                            <div  class="flex flex-row justify-end items-center" v-else-if="comment_type=='trash'">
                                                <vs-button floating icon danger size="small" @click="link($event)" method="get" :url="route('dashboard.comment.delete', {'id': tr.id})" :loading="loading">
                                                    Delete <i class="bx bx-trash"></i>
                                                </vs-button>
                                                <vs-button floating icon warn size="small" @click="link($event)" method="get" :url="route('dashboard.comment.restore', {'id': tr.id})" :loading="loading">
                                                    Restore <i class="text-lg bx bx-reset"></i>
                                                </vs-button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </template>
                        </vs-tr>
                    </template>
                    <template #footer>
                        <vs-pagination v-model="page" :length="comments.last_page" />
                    </template>
                </vs-table>

                <vs-dialog v-model="editActive" blur>
                    <template #header>
                        <h4 class="not-margin flex flex-col">
                            Edit comment
                        </h4>
                    </template>

                    <div class="con-content flex flex-col gap-8">
                        <div>
                            <div class="flex flex-row items-center gap-4">
                                <vs-avatar>
                                    <img :src="edit.user.profile_photo_url" alt="">
                                </vs-avatar>
                                <div class="flex flex-col">
                                    <span class="text-md font-bold text-gray-900">{{ edit.user.name }}</span>
                                    <span class="text-xs text-gray-600">{{ edit.user.email }}</span>
                                </div>
                            </div>
                        </div>
                        <vs-input class="w-full" state="primary" v-model="edit.content"  label-placeholder="Comment"></vs-input>
                    </div>

                    <template #footer>
                        <div class="con-footer flex flex-row justify-end">
                            <vs-button class="font-bold" active @click="editActive=false; updateComment(edit.id, {'content':edit.content})" transparent>
                                &nbsp;&nbsp;Ok&nbsp;&nbsp;
                            </vs-button>
                            <vs-button @click="editActive=false" dark transparent>
                                Cancel
                            </vs-button>
                        </div>
                    </template>
                    <template #notFound>
                    <span class="flex items-center justify-center space-x-2">
                        <b>Not Found Comment</b><i class="text-3xl text-gray-800 bx bx-sleepy"></i>
                    </span>
                    </template>
                </vs-dialog>

            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from '@/Pages/Layouts/DashboardLayout'

export default {
    name: "index",
    components: {
        DashboardLayout
    },
    props: {
        comments: Object,
        filters: Object,
        comments_count: Object
    },
    data() {
        return {
            comment_type: 'all',
            search: '',
            fromDate: '',
            toDate: '',

            editActive: false,
            edit: {
                id: 0,
                content: '',
                user: {
                    name: '',
                    email: '',
                    profile_photo_url: '',
                }
            },
            allCheck: false,
            page: 1,
            active: 0,
            selected: [],
            loading: false,
            allow: false,
            prePage: 15,
            awaitingSearch: false,
            sortKey: '',
            sortType: ''
        }
    },
    created() {
        this.allow = false;
        this.comment_type = this.filters.comment_type;
        this.page = this.filters.page;
        this.comment_type = this.filters.comment_type;
        this.search = this.filters.search;
        this.fromDate = this.filters.fromDate;
        this.toDate = this.filters.toDate;
        this.prePage = this.filters.prePage;
        this.sortKey = this.filters.sortKey;
        this.sortType = this.filters.sortType;
        let that = this;
        setInterval(function () {
            that.allow = true;
        }, 1000)

    },
    mounted(){
        let el = null;
        switch(this.sortKey) {
            case 'name':
                el = this.$refs.th_name.$el;
                break;
            case 'comment':
                el = this.$refs.th_comment.$el;
                break;
            case 'date':
                el = this.$refs.th_date.$el;
                break;
            case 'status':
                el = this.$refs.th_status.$el;
                break;
        }
        if( el ) {
            el.dataset["sortType" + this.sortKey] = this.sortType;
            el.dataset["sortType"] = this.sortType;
            el.dataset["sortKey"] = "sortType" + this.sortKey;
        }
    },
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
        deleteComment(id) {
            this.$inertia.delete(route('dashboard.comment.trash', {'id': id}), {
                preserveScroll: true,
                preserveState: true
            });
        },
        updateComment(id, data) {
            this.$inertia.put( route('dashboard.comment.update', {'id': id}), data, {
                preserveScroll: true,
                preserveState: true
            });
        },
        doFilter() {
            if(!this.allow) {
                this.allow = true;
                return;
            }
            let data = {};
            if(this.page>1)
                data['page'] = this.page;
            if(this.comment_type !== '' && this.comment_type !== 'all')
                data['comment_type'] = this.comment_type;
            if(this.search !== '')
                data['search'] = this.search;
            if(this.fromDate !== '')
                data['fromDate'] = this.fromDate;
            if(this.toDate !== '')
                data['toDate'] = this.toDate;
                if(this.prePage !== 15 && this.prePage !== '15' )
                data['prePage'] = this.prePage;

            if(this.sortKey && this.sortType ) {
                data['sortKey'] = this.sortKey;
                data['sortType'] = this.sortType;
            }
            this.selected = [];
            this.$inertia.get(route('dashboard.comment.index'), data, {
                preserveState: true,
                preserveScroll: true,
            });
        },
        trashSelected() {
            let ids = this.selected.map(item => item['id']);
            this.selected = [];
            this.$inertia.post(route('dashboard.comment.trashComments'), {'ids':ids}, {
                preserveState: true,
                preserveScroll: true,
            })
        },
        spamSelected() {
            let ids = this.selected.map(item => item['id']);
            this.selected = [];
            this.$inertia.post(route('dashboard.comment.spamComments'), {'ids':ids}, {
                preserveState: true,
                preserveScroll: true,
            })
        },
        approveSelected() {
            let ids = this.selected.map(item => item['id']);
            this.selected = [];
            this.$inertia.post(route('dashboard.comment.approveComments'), {'ids':ids}, {
                preserveState: true,
                preserveScroll: true,
            })
        },
        delteSelected() {
            let ids = this.selected.map(item => item['id']);
            this.selected = [];
            this.$inertia.post( route('dashboard.comment.deleteComments'), {'ids': ids}, {
                preserveState: true,
                preserveScroll: true,
            })
        },
        restoreSelected() {
            let ids = this.selected.map(item => item['id']);
            this.selected = [];
            this.$inertia.post( route('dashboard.comment.restoreComments'), {'ids': ids}, {
                preserveState: true,
                preserveScroll: true,
            })
        },
    },
    watch: {
        page: function(){
            this.doFilter();
        },
        comment_type: function() {
            this.page = 1;
            this.doFilter();
        },
        search: function() {
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    this.page = 1;
                    this.doFilter();
                    this.awaitingSearch = false;
                }, 1000); // 1 sec delay
            }
            this.awaitingSearch = true;
        },
        sortKey: function(){
            this.doFilter();
        },
        sortType: function(){
            this.doFilter();
        },
        fromDate: function() {
            this.page = 1;
            this.doFilter();
        },
        toDate: function() {
            this.page = 1;
            this.doFilter();
        },
        prePage: function() {
            this.doFilter();
        }

    },
}
</script>

<style >
.vs-input-content input {
    width: 100%;
}
.no-underline {
    text-decoration: none !important;
}
body {
    overflow-x: hidden;
}
</style>

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

