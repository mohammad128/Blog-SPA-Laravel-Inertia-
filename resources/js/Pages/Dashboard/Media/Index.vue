<template>
    <DashboardLayout>
        <div class="flex flex-col gap-y-5" id="allMediaContainer">
            <div>

                <vs-button
                    @click="addMedia=!addMedia"
                    :color="'primary'"
                    class="font-bold"
                >
                    <i v-if="!addMedia" class='bx bxs-down-arrow' ></i>
                    <i v-else class='bx bxs-up-arrow' ></i>
                    &nbsp;&nbsp;Add Media
                </vs-button>
                <transition name="slide-fade">
                    <AddNewMedia v-if="addMedia"/>
                </transition>
            </div>
            <div class="bg-white rounded-2xl shadow-2xl p-4 flex flex-col">

                <div class="flex justify-between items-center mb-8 mt-4 flex-col-reverse gap-4 md:flex-row">
                    <div class="flex">
                        <vs-input
                            placeholder="Search"
                            v-model="search"
                        >
                            <template #icon>
                                <i class='bx bx-search'></i>
                            </template>
                        </vs-input>
                        <span class="w-5"></span>
                        <vs-button
                            danger
                            v-if="user_id!=0"
                            @click="user_id = 0"
                        >
                            <i class='bx bx-x'></i> &nbsp;
                            {{user_name}}
                        </vs-button>
                        <span class="w-5"></span>
                        <span class="w-5"></span>
                        <span class="text-sm text-gray-400 pl-5 pt-3">total: {{medias.total}}</span>

                    </div>

                    <vs-select
                        placeholder="Type"
                        v-model="mediaType"
                        style="width: 100px"
                    >
                        <vs-option label="All" value="all">
                            All
                        </vs-option>
                        <vs-option label="Image" value="image">
                            Image
                        </vs-option>
                        <vs-option label="Audio" value="audio">
                            Audio
                        </vs-option>
                        <vs-option label="Video" value="video">
                            Video
                        </vs-option>
                        <vs-option label="Document" value="document">
                            Document
                        </vs-option>
                        <vs-option label="File" value="file">
                            File
                        </vs-option>
                    </vs-select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 justify-center">
                    <div v-for="(media, index) in medias.data" :key="index">
                        <vs-card type="5" >
                            <template #title>
                                <h3 :style="{ 'font-size': media.title.length<50 ? '14px' : '10px' }" >{{media.title}}</h3>
                            </template>
                            <template #img >
                                <img @click="showMedia(media.id)" v-if="media.type =='image'" :src="media.media_url" alt="">
                                <img @click="showMedia(media.id)" v-else :src="'/uploads/icons/'+media.type+'.png'" alt="">
                            </template>
                            <template #text>
                                <p>
                                    {{media.description}}
                                </p>
                            </template>
                            <template #interactions>
                                <vs-button danger icon @click="deleteMedia(media.id)">
                                    <i class='bx bx-trash'></i>
                                </vs-button>
                                <vs-button class="btn-chat" shadow primary @click="user_id = media.user.id;  user_name = media.user.name">
                                    <i class='bx bx-time' ></i>
                                    <span class="span">
                                        {{media.diff_for_human}} / {{ media.user.name }}
                                    </span>
                                </vs-button>
                            </template>
                        </vs-card>
                    </div>
                </div>


                <div class="center con-pagination mt-8 pt-8">
                    <vs-pagination not-margin v-model="page" :length="medias.last_page" />
                </div>

            </div>
        </div>
    </DashboardLayout>

</template>

<script>
import DashboardLayout from '@/Pages/Layouts/DashboardLayout';
import AddNewMedia from "@/Pages/Dashboard/Components/AddNewMedia";

export default {
    name: "Index",
    props:{
        all_media: Object
    },
    components: {
        DashboardLayout,
        AddNewMedia
    },
    data() {
        return {
            addMedia: false,
            medias: {},
            mediaType: '',
            page: 1,
            search: '',
            user_id: 0,

            user_name: '',
            awaitingSearch: false
        }
    },
    methods: {
        showMedia(id) {
            alert(id);
        },
        deleteMedia(id) {
            alert(id);
        },
        doFilter() {
            let url = route('dashboard.media.filter');
            let that = this;
            axios.post(url, {
                'mediaType': this.mediaType,
                'page': this.page,
                'search': this.search,
                'user_id': this.user_id,
            })
            .then(function (response) {
                that.medias = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
    watch: {
        mediaType: function(){
            this.page = 1;
            this.doFilter();
        },
        page: function(){
            this.doFilter();
        },
        search: function(){
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    this.page = 1;
                    this.doFilter();
                    this.awaitingSearch = false;
                }, 1000); // 1 sec delay
            }
            this.awaitingSearch = true;
        },
        user_id: function(){
            this.page = 1;
            this.doFilter();
        },
    },
    beforeMount() {
        // this.medias = this.all_media;
        this.doFilter();
        this.$store.state.dashboard.activeSidebarItem = 'Media_All_Media';
    }
}
</script>

<style scoped>

.slide-fade-enter-active {
    transition: all .3s ease;
}
.slide-fade-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateY(-100px) scale(.8);
    opacity: 0;
}
</style>
<style>

#allMediaContainer .vs-card-content .vs-card .vs-card__text {
    background: #ffffffd1 !important;
}
#allMediaContainer .vs-card-content .vs-card {
    margin: auto;
}
</style>
