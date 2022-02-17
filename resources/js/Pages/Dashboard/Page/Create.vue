<template>
    <DashboardLayout>

        <Head>
            <title>Add New Page</title>
            <meta name="description" head-key="description" content="Add New Page">
        </Head>
        <div class="flex flex-col xl:flex-row gap-4" id="createPostConent">
            <div class="w-full flex flex-col gap-4">

                <div class="w-full grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="" >
                        <vs-input :danger="errors['title'] ? true : false" :state="errors['title'] ? 'danger' : ''" v-model="title" placeholder="Title" >
                            <template v-if="errors['title']" #message-danger>
                                {{ errors['title'] }}
                            </template>
                        </vs-input>
                    </div>
                    <div>
                        <vs-input :danger="errors['slug'] ? true : false" :state="errors['slug'] ? 'danger' : ''" v-model="form.slug" placeholder="Slug">
                            <template v-if="errors['slug']" #message-danger>
                                {{ errors['slug'] }}
                            </template>
                        </vs-input>
                    </div>
                </div>

                <AdvanceEditor :error="errors['content'] ? true : false" :error_message="errors['content']" v-model="form.content" />

                <div class="w-full grid grid-cols-1 gap-8 md:grid-cols-2 py-4">
                    <div class="flex justify-center bg-white rounded-2xl p-4">
                        <div class="">
                            <div class="relative group rounded-full overflow-hidden ">
                                <span style="background: rgb(255 255 255 / 35%)"
                                      class="absolute z-10 pb-6 bt-4 bottom-0 left-0 right-0 w-full text-center text-sm font-bold text-gray-800 opacity-100 group-hover:opacity-0  transition-all duration-300">
                                    Feature Image
                                </span>
                                <img class="p-4 w-80 cursor-pointer opacity-90 hover:opacity-100 transition-all duration-300"
                                     :src="form.feature_image" alt="" @click="mediaMangerActive = true">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center gap-4 bg-white rounded-2xl p-4">
                        <div class="flex flex-row items-end gap-8">
                            <div class="flex-1">
                                <ProgressPassword v-model="form.password"/>
                            </div>
                        </div>
                        <div class="grid grid-cols-2">
                            <vs-button success active @click="form.draft = false; submit()" :loading="form.processing">
                                Publish
                            </vs-button>
                            <vs-button  warn  @click="form.draft = true; submit()"  :loading="form.processing">
                                Draft
                            </vs-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <vs-dialog v-model="mediaMangerActive"  :loading="mediaMangerLoading">
            <MediaManager :picker="true" type="image" :multiCheck="false" @onPick="onPickMediaPicker($event)" @onLoading="onLoadingMediaPicker($event)"/>
        </vs-dialog>

    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import CategoryCheckboxGroup from "@/Components/CategoryCheckboxGroup";
import MediaManager from '@/Pages/Dashboard/Components/MediaManager';
import AdvanceEditor from "@/Pages/Dashboard/Components/AdvanceEditor";
import ProgressPassword from "@/Components/ProgressPassword";
import TagInput from "@/Pages/Dashboard/Components/TagInput"

export default {
    name: "create",
    props: {
    },
    components: {
        DashboardLayout,
        CategoryCheckboxGroup,
        MediaManager,
        AdvanceEditor,
        mediaMangerActive: false,
        mediaMangerLoading: false,
        ProgressPassword,
        TagInput
    },
    data() {
        return {
            mediaMangerActive: false,
            mediaMangerLoading: false,
            title: '',
            form: this.$inertia.form({
                title: '',
                slug: '',
                content: '',
                feature_image: route('index')+'/uploads/icons/picture.png',
                disable_comment: false,
                password: '',
                draft: false,
                enable_rate: false,
                enable_like: false,
            })
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
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
            this.form
                .transform(data => ({
                    ... data,
                }))
                .post(route('dashboard.page.store'), {
                    preserveScroll: true,
                    preserveState: true,
                    onFinish: ()=>{
                    }
                })
        },
        onLoadingMediaPicker(isLoading) {
            this.mediaMangerLoading = isLoading;
        },
        onPickMediaPicker(media) {
            this.mediaMangerActive = false;
            console.log( media.media_url)
            this.form.feature_image = media.media_url;
        },
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Page_Add_New';
    },
    watch: {
        title: function (){
            this.form.title = this.title;
            this.form.slug = this.convertToSlug(this.title);
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
