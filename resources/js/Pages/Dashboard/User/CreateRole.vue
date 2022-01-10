<template>
    <DashboardLayout>
        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
            <div class="flex space-x-4 flex space-x-4">
                <div class="text-5xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">Roles</span>
                </div>

            </div>
            <vs-button
                floating
                class="font-bold"
                @click="link($event)"
                method="get"
                :url="route('dashboard.user.role.create')"
                state="primary"
            >
                Create Role
                <i class='bx bx-plus font-bold text-2xl' ></i>
            </vs-button>
        </div>
        <div class="flex flex-col space-y-10">
            <div class="center relative">

                <transition name="bounce">
                    <div class="absolute flex flex-row -top-8 -left-6"  v-if="selected.length">
                        <vs-button floating icon danger size="small" @click="delteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>
                <transition name="bounce">
                    <div class="absolute flex flex-row -bottom-8 -left-6"  v-if="selected.length">
                        <vs-button floating icon danger size="small" @click="delteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>

<!--                <vs-table
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
                </vs-table>-->

<!--                <vs-dialog v-model="editActive" blur>
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
                </vs-dialog>-->

            </div>
        </div>
    </DashboardLayout>
</template>

<script>
export default {
    name: "UserRole",
    props: {
        permissions: Object,
        roles: Array,
    },
    data() {
        return {
            loading: false,
            selected: [],

            editActive: false,
            edit: {},


        }
    },
    mounted() {

    }

}
</script>

<style scoped>

</style>
