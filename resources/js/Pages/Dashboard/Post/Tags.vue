<template>
    <DashboardLayout>
        <div class="flex flex-col lg:flex-row gap-4">

            <div>
                <div class="py-8 px-4 bg-white rounded-2xl lg:shadow-2xl lg:sticky lg:top-16 grid gap-8 lg:w-64">

                    <h3 v-if="edit_tag==0" class="text-lg font-bold text-gray-600">Add New Tag:</h3>
                    <h3 v-else class="text-lg font-bold text-gray-600">Edit Tag:</h3>

                    <vs-input label-placeholder="Name" v-model="name">
                    </vs-input>
                    <vs-input label-placeholder="Slug" disabled v-model="slug">
                    </vs-input>
                    <vs-button v-if="edit_tag==0" floating icon color="facebook" :loading="form.processing" :disabled="!name"
                       @click="addTag()"
                    >
                        Add Tag
                        <i class='bx bx-plus' ></i>
                    </vs-button>
                    <div v-else  class="flex">
                        <vs-button class="flex-1" floating icon color="#9d6c00" :loading="form.processing" :disabled="!name" @click="editTag()" >
                            Edit Tag
                        </vs-button>
                        <vs-button class="flex-1" icon color="#000" :loading="form.processing" @click="edit_tag=0; name=''" >
                            Cancel
                        </vs-button>
                    </div>
                </div>
            </div>

            <div class="flex-1 bg-white rounded-2xl py-8 px-4 relative">

                <vs-table
                    v-model="selected"
                >
                    <template #thead>
                        <vs-tr>
                            <vs-th>
                                <vs-checkbox
                                    :indeterminate="selected.length == tags.data.length" v-model="allCheck"
                                    @change="selected = $vs.checkAll(selected, tags.data)"
                                />
                            </vs-th>
                            <vs-th>
                                Name
                            </vs-th>
                            <vs-th>
                                Slug
                            </vs-th>
                            <vs-th>
                            </vs-th>
                        </vs-tr>
                    </template>
                    <template #tbody>
                        <vs-tr
                            :key="i"
                            v-for="(tr, i) in tags.data"
                            :data="tr"
                            :is-selected="!!selected.includes(tr)"
                        >
                            <vs-td checkbox>
                                <vs-checkbox :val="tr" v-model="selected" />
                            </vs-td>
                            <vs-td>
                                {{ tr.name.en }}

                            </vs-td>
                            <vs-td>
                                {{ tr.slug.en }}
                            </vs-td>
                            <vs-td>
                                <div class="flex justify-center align-center">

                                    <vs-button circle icon floating color="#000"
                                        @click="edit_tag = tr.id;name=tr.name.en;slug=tr.slug.en">
                                        <i class='bx bx-edit-alt' ></i>
                                    </vs-button>

                                    <vs-button danger circle icon floating
                                        @click="link($event)"
                                        :url="route('dashboard.tag.delete', {id: tr.id })"
                                        method="delete"
                                    >
                                        <i class='bx bx-trash' ></i>
                                    </vs-button>
                                </div>
                            </vs-td>
                        </vs-tr>
                    </template>
                    <template #footer>
                        <vs-pagination :disabled="page != tags.current_page" v-model="page" :length="tags.last_page" />
                    </template>
                    <template #notFound>
                        <span class="flex items-center justify-center space-x-2">
                            <b>Not Found Post</b><i class="text-3xl text-gray-800 bx bx-sleepy"></i>
                        </span>
                    </template>
                </vs-table>

                <transition name="bounce">
                    <div class="absolute -bottom-4 -left-6"  v-if="selected.length" >
                        <vs-button floating icon danger :loading="form.processing" @click="deleteSelected()" >
                            Delete Selected
                        </vs-button>
                    </div>
                </transition>
            </div>
        </div>

    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Tags",
    components: {
        DashboardLayout,
    },
    props: {
        tags: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                slug: ''
            }),
            name: '',
            slug: '',
            edit_tag: 0,
            allCheck: false,
            selected: [],
            page: 1,
        }
    },
    methods: {
        addTag() {
            this.form.name = this.name;
            this.form.slug = this.slug;
            this.form.post(route('dashboard.tag.store'), {
                onFinish: () => {
                }
            })
        },
        editTag() {
            this.form.name = this.name;
            this.form.slug = this.slug;
            this.form.put(route('dashboard.tag.update', {id: this.edit_tag}), {
                onFinish: () => {
                }
            })
        },
        doFilter() {
            let data = {};
            if(this.page !== 1)
                data['page'] = this.page;
            Inertia.get(route('dashboard.tag'), data, {
                preserveState: true,
                onStart:function() {
                },
                onFinish: function () {
                }
            });
        },
        deleteSelected() {
            let ids = this.selected.map(function (item){ return item['id'] });
            this.selected = [];
            Inertia.post(route('dashboard.tag.multiDelete'), {ids: ids}, {
                preserveState: true,
            })
        }
    },
    mounted() {
        this.page = this.tags.current_page;
    },
    watch: {
        name: function(val, oldVal) {
            this.slug = this.convertToSlug(this.name);
        },
        page: function() {
            if(this.page != this.tags.current_page)
                this.doFilter();
        }
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
