<template>
    <div class="flex flex-col">
        <vs-input placeholder="Search" v-model="search" block type="search" :loading="loading">
            <template #icon>
                <i class='bx bx-search'></i>
            </template>
        </vs-input>
        <div class="flex flex-col p-4">
            <div class="flex flex-row justify-start items-center gap-2" v-for="(post, index) in posts.data">
                <vs-checkbox class="text-xs" v-model="selected" :val="post">{{ post.title }}</vs-checkbox>
            </div>

            <div class="center con-pagination">
                <vs-pagination v-model="page" :length="posts.last_page" :disabled="this.loading" />
            </div>
        </div>
        <div class="flex flex-row justify-between items-center bg-gray-100 rounded-lg p-2">
            <vs-checkbox line-through v-model="selectAll">Select All</vs-checkbox>
            <vs-button @click="add" transparent >&nbsp;&nbsp;{{buttonText}}&nbsp;&nbsp;</vs-button>
        </div>
    </div>
</template>

<script>
export default {
    name: "PostPicker",
    props: {
        value: {
            default: null
        },
        buttonText: {
            type: String,
            default: 'OK'
        }
    },
    data() {
        return {
            selected: [],
            selectAll: false,
            posts: {
                data: [],
                last_page: 1
            },
            page: 1,
            search: '',
            loading: false,
        }
    },
    methods: {
        add() {
            this.$emit('onOk', this.selected);
            this.selected = [];
        },
        getPosts() {
            let taht = this;
            axios.post(route('dashboard.page.service.published_pages'),{
                    page: this.page,
                    search: this.search,
                    pre_page: 6
                })
                .then(function (response) {
                    taht.posts = response.data;
                    that.loading = false;
                })
                .catch(function (error) {
                    console.log("Post Picker:",error);
                });
        }
    },
    watch: {
        selectAll: function (val) {
            if( val )
                this.selected = this.posts.data;
            else this.selected = [];
        },
        selected: function () {
            this.$emit('input', this.selected);
        },
        search: function () {
            this.page = 1;
            this.getPosts();
        },
        page: function () {
            this.getPosts();
        }
    },
    mounted() {
        this.getPosts();
    }
}
</script>

<style scoped>

</style>
