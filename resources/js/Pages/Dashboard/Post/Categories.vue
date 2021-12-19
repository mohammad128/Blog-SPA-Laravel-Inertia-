<template>
    <DashboardLayout>

        <Head>
            <title>Post Categories</title>
            <meta name="description" head-key="description" content="Post Categories">
        </Head>

        <div class="flex flex-col sm:flex-row 0 flex-wrap gap-8">
            <form class="flex-none" @submit.prevent="addCategory" autocomplete="off">
                <div class="flex relative flex-col space-y-8 px-8 pt-4 pb-8  rounded-2xl bg-white shadow-2xl drop-shadow-2xl">
                    <h2 class="font-extrabold" >Add New Category:</h2>
                    <vs-input
                        label-placeholder="Name"
                        v-model="addCategoryFrom.name"
                        required
                        style="width: 100%"
                    />

                    <vs-input
                        label-placeholder="Slug"
                        v-model="addCategoryFrom.slug"
                        style="width: 100%"
                    />

                    <vs-input
                        label-placeholder="Description"
                        v-model="addCategoryFrom.desc"
                        style="width: 100%"
                    />

                    <CategoryCheckboxGroup :multiselect="false" :items="categories" v-model="addCategoryFrom.parent_id"/>

                    <transition name="bounce">
                        <vs-button
                            gradient
                            class="font-bold"
                            v-show="addCategoryFrom.name != ''"
                            :loading="addCategoryFrom.processing"
                            :class="{ 'opacity-25': addCategoryFrom.processing }" :disabled="addCategoryFrom.processing"
                            ref="btnAddNewCat"
                            :style="{'bottom': '-1.5em', 'position': 'absolute', 'right': '-1em'}"
                        >
                            <i class="bx bx-plus"></i> &nbsp;Add
                        </vs-button>
                    </transition>
                </div>
            </form>
            <div class="flex-1">
                    <form @submit.prevent="deleteCategorys" autocomplete="off">
                        <div class="relative rounded-2xl bg-white py-4 shadow-2xl drop-shadow-2xl">
                            <vs-table
                                v-model="form2.selectedCats"
                            >
                                <template #thead>
                                    <vs-tr>
                                        <vs-th>
                                            <vs-checkbox
                                                :indeterminate="form2.selectedCats.length == flatCategories.length" v-model="allCheck"
                                                @change="form2.selectedCats = $vs.checkAll(form2.selectedCats, flatCategories)"
                                            />
                                        </vs-th>
                                        <vs-th>
                                            Name
                                        </vs-th>
                                        <vs-th>
                                            Slug
                                        </vs-th>
                                        <vs-th>
                                            Description
                                        </vs-th>
                                        <vs-th>
                                            Post
                                        </vs-th>
                                    </vs-tr>
                                </template>
                                <template #tbody>
                                    <vs-tr
                                        v-for="(catItem, index) in flatCategories"
                                        :is-selected="!!form2.selectedCats.includes(catItem)"
                                        :key="index"
                                        :data="catItem"
                                        class="group text-xs"
                                    >
                                        <vs-td checkbox>
                                            <vs-checkbox :val="catItem" v-model="form2.selectedCats" />
                                        </vs-td>
                                        <vs-td class="text-xs font-bold text-gray-600" :style="{'padding-left': `${catItem.padding*1.7}em`}">
<!--                                            <span v-for="i in catItem.padding" class="inline-block h-0.5 w-3 md:w-5 bg-white"></span>-->
<!--                                            <span v-if="catItem.parent" v-for="i in catItem.padding" class="inline-block h-0.5 w-2 rounded-full bg-green-700"></span>-->
                                            {{ catItem.name }}
                                            <div class="pt-0 flex opacity-0 group-hover:opacity-100 duration-300 transition space-x-4 pl-2 w-0">
                                                <a @click="editCatForm.parent_id=catItem.parent ,editCatForm.name=catItem.name,editCatForm.slug=catItem.slug,editCatForm.id=catItem.id,editCatForm.description=catItem.description,editCategoryActive = true"
                                                   class="flex items-center text-xs font-bold text-gray-900 opacity-80 space-x-1 hover:opacity-100 "
                                                   href="javascript:void(0)" ><i class="text-lg bx bx-edit"></i> Edit</a>
                                                <a @click="deleteCat(catItem.id)"
                                                    class="flex items-center text-xs font-bold text-red-600 opacity-80 space-x-1 hover:opacity-100 "
                                                   href="javascript:void(0)" ><i class="text-lg bx bxs-trash-alt"></i> Delete</a>
                                            </div>
                                        </vs-td>
                                        <vs-td>
                                            {{ catItem.slug }}
                                        </vs-td>
                                        <vs-td>
                                            {{ catItem.description }}
                                        </vs-td>
                                        <vs-td>
                                            0
                                        </vs-td>
                                    </vs-tr>
                                </template>
                                <template #notFound>
                                    <span class="flex items-center justify-center space-x-2">
                                        <b>Empty </b><i class="text-3xl text-gray-800 bx bx-sleepy"></i>
                                    </span>
                                </template>
                            </vs-table>

                            <transition name="bounce">
                                <vs-button
                                    danger
                                    gradient
                                    class="font-bold"
                                    v-show="form2.selectedCats.length>0"
                                    :style="{'bottom': '-2em', 'position': 'absolute', 'left': '-1em'}"
                                >
                                    <i class="bx bx-trash"></i> &nbsp;Delete
                                </vs-button>
                            </transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <vs-dialog blur v-model="editCategoryActive">
            <template #header>
                <h4 class="not-margin">
                    Edit <b>Category</b>
                </h4>
            </template>


            <div class="con-form grid gap-8">
                <vs-input
                    label-placeholder="Name"
                    v-model="editCatForm.name"
                    required
                    style="width: 100%"
                />

                <vs-input
                    label-placeholder="Slug"
                    v-model="editCatForm.slug"
                    style="width: 100%"
                />

                <vs-input
                    label-placeholder="Description"
                    v-model="editCatForm.description"
                    style="width: 100%"
                />

                <CategoryCheckboxGroup :multiselect="false" :items="categories" v-model="editCatForm.parent_id"/>

            </div>

            <template #footer>
                <div class="footer-dialog">
                    <vs-button block @click="editCategory"
                               :loading="editCatForm.processing"
                               :class="{ 'opacity-25': editCatForm.processing }" :disabled="editCatForm.name == '' || editCatForm.processing">
                        Edit
                    </vs-button>
                </div>
            </template>

        </vs-dialog>

    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import CategoryCheckboxGroup from "@/Components/CategoryCheckboxGroup";

export default {
    name: "create",
    props: {
        categories: Array
    },
    components: {
        DashboardLayout,
        CategoryCheckboxGroup
    },
    data() {
        return {
            addCategoryFrom: this.$inertia.form({
                name: '',
                desc: '',
                slug: '',
                parent_id: 0,
            }),
            form2: this.$inertia.form({
                selectedCats: [],
            }),
            editCatForm: this.$inertia.form({
                id: 0,
                name: '',
                description: '',
                slug: '',
                parent_id: 0,
            }),
            editCategoryActive: false,
            allCheck:false,
            flatCategories: []
        }
    },
    methods: {
        getItems( items, padding, parent ) {
            let result = [];
            for(let i=0; i<items.length; i++) {
                let item = items[i];
                result.push({
                    'name': item.name,
                    'id': item.id,
                    'description': item.description,
                    'slug': item.slug,
                    'padding': padding,
                    'parent': parent,
                });
                if(item.children) {
                    result = result.concat( this.getItems(item.children, padding+1, item.id) );
                }
            }
            return result;
        },
        addCategory: function () {
            let that = this;
            this.addCategoryFrom.post( route('dashboard.category.create'), {
                    onFinish: () => {
                        that.addCategoryFrom.name =  '';
                        that.addCategoryFrom.desc =  '';
                        that.addCategoryFrom.slug =  '';
                        that.addCategoryFrom.parent_id =  0;
                    }
                }
            );
        },
        deleteCat: function (id) {
            let form = this.$inertia.form();
            form.delete( route('dashboard.category.delete', {id: id }), {
                    onFinish: () => {
                    }
                }
            );
        },
        deleteCategorys: function () {
            this.form2.post( route('dashboard.category.multiDelete'), {
                    onFinish: () => {
                    }
                }
            );
        },
        editCategory() {
            this.editCatForm.put( route('dashboard.category.update', {id: this.editCatForm.id }), {
                    onFinish: () => {
                        this.editCategoryActive = false;
                    }
                }
            );
        }
    },
    beforeMount() {
        this.flatCategories = this.getItems( this.$props.categories, 0, 0 );
        this.$store.state.dashboard.activeSidebarItem = 'Post_Categories';
    },
    watch: {
        categories: function (newVal) {
            this.flatCategories = this.getItems( this.$props.categories, 0, 0 );
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
<style>
.vs-input {
    width: 100%;
}
</style>
