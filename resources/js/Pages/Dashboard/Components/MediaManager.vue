<template>
    <div>
        <div class="flex flex-col gap-y-5" id="allMediaContainer">
            <div v-if="can('create_media')">
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
                    <AddNewMedia v-if="addMedia" @onUpload="onUpload($event)"/>
                </transition>
            </div>
            <div class="bg-white rounded-2xl  p-4 flex flex-col"  v-if="can('read_media')">

                <div class="flex justify-between items-center mb-8 mt-4 flex-col-reverse gap-4 md:flex-row">
                    <div class="flex">
                        <vs-input
                            type="search"
                            placeholder="Search"
                            v-model="search"
                        >
                            <template #icon>
                                <i class='bx bx-search'></i>
                            </template>
                        </vs-input>
                        <span class="w-5"></span>
                        <span class="w-5"></span>
                        <span class="text-sm text-gray-400 pl-5 pt-3">total: {{medias.total}}</span>
                        <span class="w-5"></span>


                        <transition name="scale">
                            <vs-button v-if="selectedItems.length && can('delete_media')" danger icon @click="mulitDeleteMedia()" :loading="onDeleteProcessing">
                                <i class='bx bx-trash'></i> Delete {{ selectedItems.length }}
                            </vs-button>
                        </transition>
                        <transition name="scale">
                            <vs-button flat  v-if="selectedItems.length" icon @click="selectedItems = []" :loading="onDeleteProcessing">
                                <i class='bx bx-x'></i> Cancel
                            </vs-button>
                        </transition>


                        <transition name="scale">
                            <vs-button v-if="picker && multiCheck && selectedItems.length" :loading="onDeleteProcessing"
                                           circle
                                           icon
                                           relief class="text-lg font-bold" @click="onMultiPick()" >
                            <i class="bx bx-check-double"></i>
                            </vs-button>
                        </transition>


                    </div>
                    <div class="flex">
                        <transition name="scale">
                            <vs-button
                                danger
                                v-if="user_id!=0"
                                @click="user_id = 0"
                            >
                                <i class='bx bx-x'></i> &nbsp;
                                {{user_name}}
                            </vs-button>
                        </transition>
                        <span class="w-5"></span>
                        <vs-select
                            v-if="!( this.type != undefined && this.type != '' )"
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


                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 justify-center">
                    <div v-for="(media, index) in medias.data" :key="index">
                        <vs-card type="1" >
                            <template #title>
                                <h3 :style="{ 'font-size': media.title.length<50 ? '14px' : '10px' }" >{{media.title}}</h3>
                            </template>
                            <template #img >
                                <img @click="showMedia(media)" v-if="media.type =='image'"
                                     :src="media.media_url" class="min-h-24" style="min-height:14em" onerror="this.onerror=null; this.src='/uploads/images/notFoundImage.png'">
                                <img @click="showMedia(media)" style="transform: scale(1.3)" v-else :src="'/uploads/icons/'+media.type+'.png'" alt="">

                                <vs-checkbox danger v-model="selectedItems" :val="media" class="selectItemCheckbox">
                                </vs-checkbox>
                                <vs-button v-if="picker && !multiCheck" squire dark icon relief class="text-lg font-bold btnPickFIle absolute -top-4"
                                           @click="onPick(media)">
                                    <i class="bx bx-check "></i>
                                </vs-button>

                            </template>
                            <template #text>
                                <p>
                                    {{media.description}}
                                    <vs-button class="btn-chat" primary circle border @click="user_id = media.user.id;  user_name = media.user.name">
                                        <i class='bx bx-time' ></i>
                                        <span class="span text-xs">
                                            {{media.diff_for_human}} / {{ media.user.name }}
                                        </span>
                                    </vs-button>
                                </p>
                            </template>
                            <template #interactions>
                                <vs-button v-if="can('delete_media')" danger icon @click="deleteMedia(media.id)">
                                    <i class='bx bx-trash'></i>
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



        <vs-dialog id="dialogMediaPreview" full-screen  scroll v-model="imageDialogActive">
            <template #header>
                <h4 class="not-margin flex gap-4 items-center">
                    <b>{{imageDialogData.title ? imageDialogData.title : '...'}}</b>

                    <vs-button
                        circle
                        icon
                        floating
                        @click="downloadMedia(imageDialogData)"
                    >
                        <i class='bx bxs-download' ></i>
                    </vs-button>
                </h4>
            </template>


            <div class="con-form">
                <div class="flex flex-col md:flex-row justify-around gap-4" v-if="imageDialogData.id != undefined">
                    <div class="flex-1 grid grid-cols-1 content-center">
                        <div v-if="imageDialogData.type=='image'">
                            <inner-image-zoom style="border: 2px solid #999;padding: 4px;border-radius: 8px;box-shadow: 0 6px 16px #aaa;" :src="imageDialogData.media_url" :zoomSrc="imageDialogData.media_url" />
                        </div>
                        <vue-plyr v-else-if="imageDialogData.type=='audio'">
                            <audio controls crossorigin playsinline>
                                <source
                                    :src="imageDialogData.media_url"
                                />
                            </audio>
                        </vue-plyr>
                        <vue-plyr v-else-if="imageDialogData.type=='video'" >
                            <video
                                controls
                            >
                                <source
                                    size="720"
                                    :src="imageDialogData.media_url"
                                    type="video/mp4"
                                />
                            </video>
                        </vue-plyr>
                        <img v-else  :src="'/uploads/icons/'+imageDialogData.type+'.png'" alt="" >
                    </div>
                    <div class="flex-1">
                        <div class="grid grid-cols-1 py-8 text-center">
                            <h3 class="font-bold text-md">This {{ imageDialogData.type }} uploaded by {{ imageDialogData.user.name }}. {{ imageDialogData.diff_for_human }} </h3>
                        </div>
                        <div class="grid grid-cols-1 gap-8">
                            <div class="flex flex-col">
                                <div>
                                    <b>Title: </b>
                                </div>
                                <div class="px-4 md:pl-16">
                                    <vs-input :disabled="!can('edit_media')" v-model="imageDialogData.title">
                                    </vs-input>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <div>
                                    <b>Description: </b>
                                </div>
                                <div class="px-4 md:pl-16">
                                    <vs-input :disabled="!can('edit_media')" v-model="imageDialogData.description">
                                    </vs-input>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <b>File Name: </b>
                                </div>
                                <div class="px-4 md:pl-16">
                                    <vs-input disabled v-model="imageDialogData.path.substr(6)">
                                    </vs-input>
                                </div>
                            </div>
                            <div class="flex flex-row-reverse justify-between">
                                <div>

                                    <transition name="scale">
                                        <vs-button
                                            :disabled="!can('edit_media')"
                                            v-show="imageDialogData.title"
                                            success
                                            @click="updateMedia(imageDialogData)"
                                            :loading="onUpdateProcessing"
                                        >
                                            Save
                                        </vs-button>
                                    </transition>
                                </div>

                                <vs-button
                                    :disabled="!can('delete_media')"
                                    :loading="onUpdateProcessing"
                                    danger
                                    flat
                                    @click="deleteMedia(imageDialogData.id); imageDialogActive=false"
                                >
                                    Delete
                                </vs-button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </vs-dialog>

    </div>

</template>

<script>
import AddNewMedia from "@/Pages/Dashboard/Components/AddNewMedia";
import InnerImageZoom from 'vue-inner-image-zoom';

export default {
    name: "Index",
    props:{
        mediaMangerLoading: Boolean,
        multiCheck: Boolean,
        picker: Boolean,
        type: String
    },
    components: {
        AddNewMedia,
        InnerImageZoom
    },
    data() {
        return {
            imageDialogActive: false,
            imageDialogData: {},
            selectedItems: [],
            addMedia: false,
            medias: {},
            mediaType: '',
            page: 1,
            search: '',
            user_id: 0,

            user_name: '',
            awaitingSearch: false,

            onDeleteProcessing: false,
            onUpdateProcessing: false,

        }
    },
    computed: {
    },
    methods: {
        downloadMedia(media) {
            window.open(route('dashboard.media.download', {id: media.id}), '_blank');

            // axios.get(route('dashboard.media.download', {id: media.id})).then(function (response) {
            //     let file_name = media.title + "." +  media.path.split('.')[media.path.split('.').length-1];
            //     fileDownload(response.data, file_name);
            // });
        },
        showMedia(media) {
            this.imageDialogActive=!this.imageDialogActive;
            this.imageDialogData = Object.assign({}, media);
        },
        deleteMedia(id) {
            let url = route('dashboard.media.service.delete', {id: id});
            let that = this;
            axios.delete(url).then(function (response) {
                if( response.data ) that.doFilter();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        doFilter() {
            let url = route('dashboard.media.filter');
            let that = this;
            this.$emit('onLoading', true);
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
                }).finally(function() {
                    that.$emit('onLoading', false);
                });
        },
        onUpload(response) {
            this.doFilter()
        },
        mulitDeleteMedia() {
            this.onDeleteProcessing = true;
            let url = route('dashboard.media.service.multidelte');
            let that = this;
            let ids = this.selectedItems.map( item => item['id'] );

            axios.post(url,{ids: ids}).then(function (response) {
                if( response.data )
                    that.doFilter();
                that.selectedItems = [];
                that.onDeleteProcessing = false;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        updateMedia(media) {
            this.onUpdateProcessing = true;
            let that = this;
            let url = route('dashboard.media.service.update', {id:media.id});
            axios.put(url, media).then(function (response) {
                that.onUpdateProcessing = false;
                that.doFilter();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        onMultiPick() {
            this.$emit('onPick', this.selectedItems)
        },
        onPick(media) {
            this.$emit('onPick', media);
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
        }
    },
    beforeMount() {
        // this.medias = this.all_media;
        if(this.type != undefined && this.type != '' )
            this.mediaType = this.type;
        this.doFilter();
    },
    mounted() {
    }
}
</script>

<style scoped>
.btnPickFIle{
    position: absolute;
    top: 0;
    right: 0;
}
.selectItemCheckbox{
    position: absolute;
    left: 6px;
    top: 6px;
    box-shadow: 0 0 35px white;
    border-radius: 7px;
}
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


.scale-enter-active {
    transition: all .3s ease;
}
.scale-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.scale-enter, .scale-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: scale(.8);
    opacity: 0;
}
</style>
<style>
#dialogMediaPreview .vs-dialog--scroll .vs-dialog__content {
    max-height: calc(100vh - 50px);
    overflow: auto;
}

#allMediaContainer .vs-card-content .vs-card .vs-card__text {
    background: #ffffffd1 !important;
}
#allMediaContainer .vs-card-content .vs-card {
    margin: auto;
}
#dialogMediaPreview .con-form .vs-input{
    width: 100%;
}
</style>
