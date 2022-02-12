<template>
    <div class="flex flex-col bg-white rounded-2xl py-2 px-1 rounded-bl-none rounded-br-none" v-if="can('create_media')">
        <div class="flex justify-between items-center">
            <h2 class="text-xl pl-4">Pick File: </h2>

            <vs-button
                circle
                icon
                floating
                @click="list = !list"
            >
                <i v-if="!list" class='bx bx-list-ul' ></i>
                <i v-else class='bx bx-grid-alt' ></i>
            </vs-button>
        </div>

        <VueFileAgent
            :theme="list ? 'list' : 'default'"
            :uploadUrl="route('dashboard.media.service')"
            :linkable="true"
            v-model="fileRecords"
            :uploadHeaders="uploadHeaders"
            @upload="onUpload($event)"

        ></VueFileAgent>
    </div>
</template>

<script>


export default {
    name: "AddNewMedia",
    data() {
        return {
            list: false,
            fileRecords: [],
            uploadHeaders: { 'X-CSRF-TOKEN': window.csrf_token },
        };
    },
    methods: {
        onUpload(response) {
            this.$emit('onUpload', response);
        }
    },
    mounted() {
    }
}
</script>

<style >
.file-preview .file-name{
    display: flex;
}
</style>
