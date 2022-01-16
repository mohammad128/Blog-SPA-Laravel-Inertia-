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
                        Users
                      </span>
                </div>
                <vs-button
                    circle
                    icon
                    floating
                    @click="link($event)"
                    method="get"
                    :url="route('dashboard.user.create')"
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
                    state="primary"
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
                <span class="text-sm text-gray-400 pl-5 pt-3">total: {{ users.total }}</span>
            </div>

            <div>
                <vs-select
                    filter
                    multiple
                    collapse-chips
                    placeholder="Role"
                    v-model="selectedRoles"
                >
                    <vs-option v-for="(role, index) in _roles" :key="index" :data="role" :label="role.name" :value="role.name">
                        {{ role.name }}
                    </vs-option>
                </vs-select>
            </div>
        </div>

        <div class="flex justify-center lg:justify-end mb-8 flex-row lg:flex-col-reverse gap-4 md:flex-row bg-white p-4 rounded-2xl">

            <div class="flex gap-4 flex-col lg:flex-row items-center ">
                <div class="flex gap-2 items-center">
                    <span class="text-xs text-gray-700">From:</span>
                    <vs-input
                        v-model="fromDate"
                        type="date"
                        state="primary"
                    />
                </div>
                <div class="flex gap-2 items-center">
                    <span class="text-xs text-gray-700">To:</span>
                    <vs-input
                        v-model="toDate"
                        type="date"
                        state="primary"
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

                <div class="center">
                    <vs-table
                        v-model="selected"
                        class="bg-white"
                    >
                        <template #thead>
                            <vs-tr>
                                <vs-th>
                                    <vs-checkbox
                                        :indeterminate="selected.length == users.data.length" v-model="allCheck"
                                        @change="selected = $vs.checkAll(selected, users.data)"
                                    />
                                </vs-th>
                                <vs-th sort ref="th_name" @click="sort($event, 'name')">
                                    Name
                                </vs-th>
                                <vs-th sort ref="th_email" @click="sort($event, 'email')">
                                    Email
                                </vs-th>
                                <vs-th >
                                    Roles
                                </vs-th>
                                <vs-th sort ref="th_created_at" @click="sort($event, 'created_at')">
                                    Registered At
                                </vs-th>
                                <vs-th sort ref="th_post" @click="sort($event, 'post')">
                                    Post
                                </vs-th>
                                <vs-th sort ref="th_page" @click="sort($event, 'page')">
                                    Page
                                </vs-th>
                                <vs-th sort ref="th_comment" @click="sort($event, 'comment')">
                                    Comment
                                </vs-th>
                            </vs-tr>
                        </template>
                        <template #tbody>
                            <vs-tr
                                :key="i"
                                v-for="(tr, i) in users.data"
                                :data="tr"
                                :is-selected="!!selected.includes(tr)"
                                not-click-selected
                                open-expand-only-td
                            >
                                <vs-td checkbox>
                                    <vs-checkbox :val="tr" v-model="selected" />
                                </vs-td>
                                <vs-td class="flex flex-row items-start gap-4">
                                    {{ tr.name }} <span v-if="user.id == tr.id" class="bg-yellow-400 rounded-lg text-xs px-2 py-1 font-bold text-white">you</span>
                                </vs-td>
                                <vs-td>
                                    {{ tr.email }}
                                </vs-td>
                                <vs-td>
                                    {{ tr.roles.map(item=>item['name']).join(', ') }}
                                </vs-td>
                                <vs-td>
                                    {{ tr.created_at.substring(0,10) }}
                                </vs-td>
                                <vs-td>
                                    {{ tr.posts_count }}
                                </vs-td>
                                <vs-td>
                                    {{ tr.pages_count }}
                                </vs-td>
                                <vs-td>
                                    {{ tr.comments_count }}
                                </vs-td>

                                <template #expand>
                                    <div class="con-content flex flex-row p-4 border-l-4 border-l-amber-800 gap-4 bg-gray-100">
                                        <div class="flex flex-row items-center space-x-2">
                                            <vs-avatar>
                                                <img :src="tr.profile_photo_url" :alt="tr.name">
                                            </vs-avatar>
                                            <p class="text-xs font-bold flex flex-col">
                                                {{ tr.name }}
                                                <span class="text-gray-800 font-medium">
                                                    {{ tr.email }}
                                                </span>
                                                <span class="text-sky-700 font-medium">
                                                    {{ tr.roles.map(item=>item['name']).join(', ') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="flex flex-row text-xs  space-x-2">
                                            <div class="flex flex-col text-gray-700">
                                                <span>Post: </span>
                                                <span>Page: </span>
                                                <span>Comment: </span>
                                            </div>
                                            <div class="flex flex-col text-emerald-900">
                                                <span>{{ tr.posts_count }}</span>
                                                <span>{{ tr.pages_count }}</span>
                                                <span>{{ tr.comments_count }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex justify-end items-end" v-if="user.id != tr.id">
                                            <div class="flex flex-row justify-end items-end">
                                                <vs-button flat icon warn transparent>
                                                    <i class='bx bx-lock-open-alt' ></i>&nbsp;Lock
                                                </vs-button>
                                                <vs-button icon transparent dark>
                                                    <i class='bx bx-message-square-detail' ></i>&nbsp;Send Message
                                                </vs-button>
                                                <vs-button transparent icon dark @click="showEditUserDialog(tr)">
                                                    &nbsp;&nbsp;&nbsp;<i class='bx bxs-edit-alt' ></i>&nbsp;
                                                    Edit&nbsp;&nbsp;&nbsp;&nbsp;
                                                </vs-button>
                                                <vs-button border danger
                                                           @click="link($event)" method="delete"
                                                           :url="route('dashboard.user.delete', {'id': tr.id})">
                                                    <i class='bx bx-trash' ></i>&nbsp;
                                                    Remove User
                                                </vs-button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </vs-tr>
                        </template>
                        <template #footer>
                            <vs-pagination v-model="page" :length="users.last_page" />
                        </template>
                    </vs-table>

                </div>
            </div>
        </div>


        <vs-dialog blur v-model="editActive">
            <template #header>
                <h4 class="not-margin">
                    Edit User
                </h4>
            </template>

            <div class="con-form flex flex-col gap-4 py-8">

                <div>
                    <div class="flex flex-col justify-center items-center w-38 h-38 relative">
                        <input type="file" class="hidden"
                               ref="photo"
                               @change="updatePhotoPreview">

                        <img v-if="!photoPreview" class="ring-offset-4 ring-2 transform scale-95 hover:scale-100 hover:ring-offset-2 hover:ring-4 transition-all duration-700 rounded-full w-36 h-36 cursor-pointer"
                             :src="editData.profile_photo_url"
                             alt=""
                             @click="selectImage()"
                        >
                        <span v-else
                              class="block ring-offset-4 ring-2 transform scale-95 hover:scale-100 hover:ring-offset-2 hover:ring-4 transition-all duration-700 rounded-full w-36 h-36 cursor-pointer"
                              :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"
                              @click="selectImage()"
                        >
                        </span>
                        <div class="flex flex-row gap-2 -mt-6 pb-6">
                            <span class="text-lg text-rose-700 font-bold" v-if="editData.profile_photo_path" >
                                <vs-button danger size="mini" @click="link($event)" method="delete" :url="route('dashboard.user.removeImage', {'user': editData.id})">
                                    <i class="bx bx-trash "></i>&nbsp;Remove Image
                                </vs-button>
                            </span>
                            <span v-show="photoPreview" class="text-lg text-rose-700 ">
                                <vs-button circle icon flat warn :disabled="!photoPreview" @click="removePhoto">
                                    <i class="bx bx-reset font-bold text-4xl"></i>
                                </vs-button>
                            </span>
                        </div>
                        <span class="text-xs text-rose-600" v-show="errors['photo']">{{ errors['photo'] }}</span>
                    </div>
                </div>

                <vs-select
                    filter
                    multiple
                    block
                    collapse-chips
                    placeholder="Role"
                    v-model="editData.roles"
                >
                    <vs-option v-for="(role, index) in _roles" :key="index" :data="role" :label="role.name" :value="role.name">
                        {{ role.name }}
                    </vs-option>
                </vs-select>

                <vs-input v-model="editData.name" placeholder="Name" block state="primary">
                    <template #icon>
                        <i class='bx bx-user'></i>
                    </template>
                </vs-input>
                <vs-input type="email" v-model="editData.email" placeholder="Email" block state="primary">
                    <template #icon>
                        @
                    </template>
                </vs-input>
            </div>

            <template #footer>
                <div class="footer-dialog">
                    <div class="flex flex-row justify-between">
                        <vs-button block transparent @click="editActive = false" shadow >
                            Cancel
                        </vs-button>
                        <vs-button block @click="doEdit()">
                            Save
                        </vs-button>
                    </div>
                </div>
            </template>
        </vs-dialog>

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
        users: Object,
        user: Object,
        _roles: Array,
        _filters: Object
    },
    data() {
        return {
            page: 1,
            fromDate: '',
            toDate: '',
            search: '',
            postPrePage: '15',
            orderKey: '',
            orderType: '',
            selectedRoles: [],

            selected: [],

            searchLoading: false,
            allCheck: false,
            awaitingSearch: false,
            allow: false,
            loading: false,

            editActive: false,
            editData: this.$inertia.form({
                id: 0,
                email: '',
                name: '',
                profile_photo_url: '',
                profile_photo_path: '',
                roles: [],
                photo: null
            }),
            photoPreview: null
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Users_All_Users';
    },
    mounted() {
        let el = null;
        switch(this.orderKey) {
            case 'name':
                el = this.$refs.th_name.$el;
                break;
            case 'email':
                el = this.$refs.th_email.$el;
                break;
            case 'role':
                el = this.$refs.th_role.$el;
                break;
            case 'created_at':
                el = this.$refs.th_created_at.$el;
                break;
            case 'post':
                el = this.$refs.th_post.$el;
                break;
            case 'page':
                el = this.$refs.th_page.$el;
                break;
            case 'comment':
                el = this.$refs.th_comment.$el;
                break;
        }
        if( el ) {
            el.dataset["sortType" + this.orderKey] = this.orderType;
            el.dataset["sortType"] = this.orderType;
            el.dataset["sortKey"] = "sortType" + this.orderKey;
        }
    },
    created() {
        this.page = this._filters.page;
        this.fromDate = this._filters.fromDate;
        this.toDate = this._filters.toDate;
        this.search = this._filters.search;
        this.postPrePage = this._filters.postPrePage;
        if(this._filters.selectedRoles != '') {
            this.selectedRoles = this._filters.selectedRoles.split('|');
            console.log('_filters', this._filters.selectedRoles);
            console.log('selectedRoles', this.selectedRoles);
        }
        this.orderKey = this._filters.orderKey;
        this.orderType = this._filters.orderType;
        let that = this;


        setInterval(function () {
            that.allow = true;
        }, 1000);
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
            this.orderKey = sortKey;
            this.orderType = sortType;
        },
        doFilter() {
            if(!this.allow) {
                this.allow = true;
                return;
            }
            let data = {};
            if( this.page > 1 )
                data['page'] = this.page;
            if( this.fromDate )
                data['fromDate'] = this.fromDate;
            if( this.toDate )
                data['toDate'] = this.toDate;
            if( this.search )
                data['search'] = this.search;
            if( this.postPrePage != '15' )
                data['postPrePage'] = this.postPrePage;
            if( this.orderKey && this.orderType ) {
                data['orderKey'] = this.orderKey;
                data['orderType'] = this.orderType;
            }
            if( this.selectedRoles.length > 0 )
                data['selectedRoles'] = this.selectedRoles.join('|');
            this.$inertia.get( route('dashboard.user.index'), data, {
                preserveState: true,
                preserveScroll: true,
            } );
        },
        deleteSelected() {
            this.loading = true;
            let that = this;
            this.$inertia.post(route('dashboard.user.multiDelete'), {'ids':this.selected.map(item=>(item['id']))}, {
                preserveScroll: true,
                preserveState: false,
                onFinish() {
                    that.selected = [];
                    that.page = true;
                    that.loading = false;
                }
            });
        },

        selectImage() {
            this.$refs.photo.click();
        },
        updatePhotoPreview() {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
                if (this.$refs.photo) {
                    this.editData.photo = this.$refs.photo.files[0];
                }
            };
            reader.readAsDataURL(this.$refs.photo.files[0]);
        },
        removePhoto() {
            this.photoPreview = null;
            this.editData.photo = null;
            this.$refs.photo.value=null;
        },
        showEditUserDialog(tr) {
            let tmp = tr;//Object.assign({}, tr);
            this.editData.id  = tmp.id
            this.editData.email  = tmp.email
            this.editData.name  = tmp.name
            this.editData.profile_photo_url  = tmp.profile_photo_url
            this.editData.profile_photo_path  = tmp.profile_photo_path
            this.editData.roles  = tmp.roles.map(item=>item['name']);
            this.editActive=true;
        },
        doEdit() {
            this.editData.put(route('dashboard.user.update', {'user': this.editData.id}));

            // this.$inertia.put(
            //     route('dashboard.user.update', {'user': this.editData.id}),
            //     {
            //         name: this.editData.name,
            //         email: this.editData.email,
            //         roles: this.editData.roles,
            //         photo: this.editData.photo
            //     }, {
            //         forceFormData: true,
            //     }
            // );
        }
    },
    watch: {
        page: function () {
            this.doFilter();
            this.selected = [];
        },
        fromDate: function () {
            this.page=1;
            this.doFilter();
        },
        toDate: function () {
            this.page=1;
            this.doFilter();
        },
        search: function () {
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    this.page = 1;
                    this.doFilter();
                    this.awaitingSearch = false;
                }, 1000); // 1 sec delay
            }
            this.awaitingSearch = true;
        },
        postPrePage: function () {
            this.page=1;
            this.doFilter();
        },
        selectedRoles: function () {
            this.page=1;
            this.doFilter();
        },
        orderKey: function () {
            this.doFilter();
        },
        orderType: function () {
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
</style>

