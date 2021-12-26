<template>
    <DashboardLayout>

        <Head>
            <title>Add New Post</title>
            <meta name="description" head-key="description" content="Add New Post">
        </Head>
        <div class="flex flex-col xl:flex-row gap-4" id="createPostConent">
            <div class="w-full flex flex-col gap-4">
                <div class="w-full grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="md:col-span-2" >
                        <vs-input v-model="form.title" placeholder="Title" />
                    </div>
                    <div>
                        <vs-input v-model="form.slug" placeholder="Slug" />
                    </div>
                    <div>
                        <CategoryCheckboxGroup :multiselect="true" :items="categories" v-model="form.categuries"/>
                    </div>
                </div>

                <AdvanceEditor v-model="form.content" />

                <div class="w-full grid grid-cols-1 gap-8 md:grid-cols-2 py-4">
                    <div class="flex justify-center bg-white rounded-2xl p-4">
                        <div class="">
                            <div class="relative group rounded-full overflow-hidden ">
                                <span style="background: rgb(255 255 255 / 35%)"
                                      class="absolute z-10 pb-6 bt-4 bottom-0 left-0 right-0 w-full text-center text-sm font-bold text-gray-800 opacity-100 group-hover:opacity-0  transition-all duration-300">
                                    Feature Image
                                </span>
                                <img class="p-4 w-80 cursor-pointer opacity-90 hover:opacity-100 transition-all duration-300"
                                     :src="form.feature_image ? form.feature_image : '/uploads/icons/picture.png'" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center gap-4 bg-white rounded-2xl p-4">
                        <div class="grid grid-cols-2 gap-4">

                            <vs-checkbox dark v-model="form.disible_comment" >
                                Disible Comment
                            </vs-checkbox>

                            <ProgressPassword v-model="form.password"/>
                        </div>
                        <div class="grid grid-cols-2">
                            <vs-button success active @click="form.draft = false; submit()" >
                                Publish
                            </vs-button>
                            <vs-button  warn  @click="form.draft = true; submit()"  >
                                Draft
                            </vs-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import CategoryCheckboxGroup from "@/Components/CategoryCheckboxGroup";
import MediaManager from '@/Pages/Dashboard/Components/MediaManager';
import AdvanceEditor from "@/Pages/Dashboard/Components/AdvanceEditor";
import ProgressPassword from "@/Components/ProgressPassword";

export default {
    name: "create",
    props: {
        categories: Array
    },
    components: {
        DashboardLayout,
        CategoryCheckboxGroup,
        MediaManager,
        AdvanceEditor,
        ProgressPassword
    },
    data() {
        return {
            mediaMangerActive: false,
            mediaMangerLoading: false,
            form: this.$inertia.form({
                title: '',
                slug: '',
                categuries: [],
                content: 'aasgasgasasdfasdgasg',
                feature_image: '',
                disible_comment: false,
                password: '',
                draft: false
            })
        }
    },
    methods: {
        onLoading(isLoading) {
            this.mediaMangerLoading = isLoading;
        },
        onPick(result) {
            this.mediaMangerActive = false;
            console.log(result)
        },
        submit() {
            console.log(this.form);
        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Post_Add_New';
    },
    watch: {
        form: {
            handler(val) {
                console.log(val)
            },
            deep: true
        }
    }
}
</script>

<style>
.fr-box.fr-basic .fr-element {
    min-height: 30em !important;
}
#createPostConent .vs-input-content .vs-input{
    width: 100%;
}
</style>
