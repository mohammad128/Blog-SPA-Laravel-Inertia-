<template>
    <div>
        <vs-dialog scroll overflow-hidden v-model="active" v-on:close="close" >
            <template #header>
                <vue-content-loading v-if="loading" :width="300" :height="24" class="grid items-center justify-items-center">
                    <rect x="100" y="0" rx="4" ry="4" width="100" height="15" />
                </vue-content-loading>
                <h3 class="text-xs" v-else>
                    {{post.title}}
                </h3>
<!--                <vs-button-->
<!--                    flat-->
<!--                    @click="loading=!loading"-->
<!--                >-->
<!--                    Active-->
<!--                </vs-button>-->
            </template>



            <div v-if="loading" class="con-content">
                <vue-content-loading :width="300" :height="80">
                    <circle cx="150" cy="40" r="40" />
                </vue-content-loading>
                <vcl-facebook></vcl-facebook>

                <p class="opacity-0 cursor-default break-words">
                    ______________________________________________________________________________________________________________________________________________________________________________________________________________________________________________
                </p>
            </div>
            <div v-else class="con-content">
                <div class="w-full grid grid-cols-1 justify-items-center ">
                    <StickerImage :image-url="post.feature_image"/>
<!--                    <img :src="post.feature_image" alt="" class="max-w-xs rounded-full">-->
                </div>
                <br>
                <div class="w-full previewPostContent prose-sm prose-p:text-gray-900" v-html="post.content">
                </div>
            </div>
        </vs-dialog>
    </div>
</template>

<script>
import VueContentLoading from 'vue-content-loading';
import { VclFacebook } from 'vue-content-loading';
import StickerImage from "@/Components/StickerImage";

export default {
    name: "PreviewPost",
    components: {
        VclFacebook,
        StickerImage,
        VueContentLoading,
    },
    props: {
        postId: Number,
        value: Boolean
    },
    data:() => ({
        loading: true,
        active: false,
        post: {}
    }),
    mounted() {
    },
    methods: {
        close() {
            this.$emit('input', false);
        }
    },
    watch: {
        value: function (val) {
            this.active = val;
        },
        active: function (val){
            if( val ) {
                let that = this;
                axios.post( route('Post.postPreview', {id: this.$props.postId}) , {
                })
                .then(function (response) {
                    that.post = response.data;
                    console.log(that.post);
                    that.loading = false;
                })
                .catch(function (error) {
                    this.$emit('input', false);
                    that.post = {};
                    that.loading = true;
                    that.active = false;
                });

            }else {
                this.post = {};
                this.loading = true;
            }
        }
    }
}
</script>

<style scoped>

</style>
