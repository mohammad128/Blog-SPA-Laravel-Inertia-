<template>
    <DashboardLayout>
        <Head>
            <title>Manage All Posts</title>
            <meta name="description" head-key="description" content="Manage All Posts">
        </Head>

        <div class="flex space-x-4 ">
            <div class="text-5xl font-extrabold">
                  <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">
                    Posts
                  </span>
            </div>
            <vs-button
                circle
                icon
                floating
            >
                <i class='bx bx-plus' ></i>
            </vs-button>
        </div>

        <div class="flex justify-between items-center mb-8 mt-4 flex-col-reverse gap-4 md:flex-row">
            <div class="flex">
                <vs-input
                    placeholder="Search"
                    v-model="search"
                    :loading="searchLoading"
                >
                    <template #icon>
                        <i class='bx bx-search'></i>
                    </template>
                </vs-input>
                <span class="w-5"></span>

                <vs-select
                    placeholder="Pre Page"
                    v-model="postPrePage"
                    style="width: 100px"
                >
                    <vs-option label="10" value="10">
                        10
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

                <span class="w-5"></span>
                <span class="text-sm text-gray-400 pl-5 pt-3">total: 222</span>
            </div>
            <div class="flex bg-white rounded-full">
                <a class="bg-white rounded-l-full px-4 py-2 text-sm font-bold text-gray-600 hover:text-gray-900" href="#">All</a>
                <a class="bg-white bg-purple-200 px-4 py-2 text-sm font-bold text-red-600 hover:text-red-800" href="#">Trash</a>
                <a class="bg-white px-4 py-2 text-sm font-bold text-yellow-600 hover:text-yellow-400" href="#">Draft</a>
                <a class="bg-white rounded-r-full px-4 py-2 text-sm font-bold text-blue-600 hover:text-blue-800" href="#">Published</a>
            </div>
        </div>

        <div class="flex justify-between items-center mb-8 mt-4 flex-col-reverse gap-4 md:flex-row">

            <vs-select
                label="Multiple collapse chips"
                filter
                multiple
                collapse-chips
                placeholder="Category"
                v-model="categories"
            >
                <vs-option label="Vuesax" value="1">
                    Vuesax
                </vs-option>
                <vs-option label="Vue" value="2" parent_id="1" style="margin-left: 2em">
                    Vue
                </vs-option>
                <vs-option label="Javascript" value="3">
                    Javascript
                </vs-option>
                <vs-option label="Sass" value="4">
                    Sass
                </vs-option>
                <vs-option label="Typescript" value="5">
                    Typescript
                </vs-option>
                <vs-option label="Webpack" value="6">
                    Webpack
                </vs-option>
                <vs-option label="Nodejs" value="7">
                    Nodejs
                </vs-option>
            </vs-select>
        </div>
        <div class="flex flex-col space-y-10">
            <div>

                <vs-table
                v-model="selected"
                style="background: #fff"
                 >
                <template #thead>
                    <vs-tr>
                        <vs-th>
                            <vs-checkbox
                                :indeterminate="selected.length == users.length" v-model="allCheck"
                                @change="selected = $vs.checkAll(selected, users)"
                            />
                        </vs-th>
                        <vs-th sort @click="users = $vs.sortData($event ,users, 'name')">
                            Title
                        </vs-th>
                        <vs-th>
                            Author
                        </vs-th>
                        <vs-th>
                            Categories
                        </vs-th>
                        <vs-th>
                            Tags
                        </vs-th>
                        <vs-th>
                            <i class="text-2xl bx bxs-comment"></i>
                        </vs-th>
                        <vs-th>
                            Date
                        </vs-th>
                    </vs-tr>
                </template>
                <template #tbody>
                    <vs-tr
                        :key="i"
                        v-for="(tr, i) in users"
                        :data="tr"
                        :is-selected="!!selected.includes(tr)"
                        class="group"
                    >
                        <vs-td checkbox>
                            <vs-checkbox :val="tr" v-model="selected" />
                        </vs-td>
                        <vs-td class="font-bold text-lg">
                            {{ tr.name }}
                            <div class="pt-2 flex opacity-0 group-hover:opacity-100 duration-300 transition space-x-4 pl-2 ">
                                <a class="flex items-center text-xs text-gray-900 opacity-70 space-x-1 hover:opacity-100 " href="#" ><i class="text-lg bx bx-edit"></i> Edit</a>
                                <a class="flex items-center text-xs text-red-900 opacity-70 space-x-1 hover:opacity-100 " href="#" ><i class="text-lg bx bxs-trash-alt"></i> Delete</a>
                                <a class="flex items-center text-xs text-green-900 opacity-70 space-x-1 hover:opacity-100 " href="#" ><i class="text-lg bx bxs-show"></i> View</a>
                            </div>
                        </vs-td>
                        <vs-td>
                            {{ tr.email }}
                        </vs-td>
                        <vs-td>
                            {{ tr.id }}
                        </vs-td>
                        <vs-td>
                            <a class="text-blue-700 opacity-80 hover:opacity-100 transform duration-300" href="#">tag1</a>,
                            <a class="text-blue-700 opacity-80 hover:opacity-100 transform duration-300" href="#">tag1</a>,
                            <a class="text-blue-700 opacity-80 hover:opacity-100 transform duration-300" href="#">tag1</a>,
                            <a class="text-blue-700 opacity-80 hover:opacity-100 transform duration-300" href="#">tag1</a>,
                            <a class="text-blue-700 opacity-80 hover:opacity-100 transform duration-300" href="#">tag1</a>
                        </vs-td>
                        <vs-td>
                            <span>
                                <i class="relative bx bxs-comment text-4xl text-gray-900">
                                    <b class="absolute top-1.5 left-2.5 text-white text-xs">30</b>
                                </i>
                            </span>
                        </vs-td>
                        <vs-td>
                            2021/12/16 at 7:02 am
                        </vs-td>
                    </vs-tr>
                </template>
                <template #notFound>
                    <span class="flex items-center justify-center space-x-2">
                        <b>Not Found Post</b><i class="text-3xl text-gray-800 bx bx-sleepy"></i>
                    </span>
                </template>

            </vs-table>

            </div>
            <div class="center relative">
                <vs-pagination not-margin v-model="page" :length="20" />
            </div>
        </div>

    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";

export default {
    name: "AllPosts",
    components: {
        DashboardLayout
    },

    data:() => ({
        allCheck: false,
        selected: [],
        search: '',
        searchLoading: false,
        postPrePage: 10,
        categories: [],
        page: 1,
        users: [
            {
                "id": 1,
                "name": "Leanne Graham",
                "username": "Bret",
                "email": "Sincere@april.biz",
                "website": "hildegard.org",
            },
            {
                "id": 2,
                "name": "Ervin Howell",
                "username": "Antonette",
                "email": "Shanna@melissa.tv",
                "website": "anastasia.net",
            },
            {
                "id": 3,
                "name": "Clementine Bauch",
                "username": "Samantha",
                "email": "Nathan@yesenia.net",
                "website": "ramiro.info",
            },
            {
                "id": 4,
                "name": "Patricia Lebsack",
                "username": "Karianne",
                "email": "Julianne.OConner@kory.org",
                "website": "kale.biz",
            },
            {
                "id": 5,
                "name": "Chelsey Dietrich",
                "username": "Kamren",
                "email": "Lucio_Hettinger@annie.ca",
                "website": "demarco.info",
            },
            {
                "id": 6,
                "name": "Mrs. Dennis Schulist",
                "username": "Leopoldo_Corkery",
                "email": "Karley_Dach@jasper.info",
                "website": "ola.org",
            },
            {
                "id": 7,
                "name": "Kurtis Weissnat",
                "username": "Elwyn.Skiles",
                "email": "Telly.Hoeger@billy.biz",
                "website": "elvis.io",
            },
            {
                "id": 8,
                "name": "Nicholas Runolfsdottir V",
                "username": "Maxime_Nienow",
                "email": "Sherwood@rosamond.me",
                "website": "jacynthe.com",
            },
            {
                "id": 9,
                "name": "Glenna Reichert",
                "username": "Delphine",
                "email": "Chaim_McDermott@dana.io",
                "website": "conrad.com",
            },
            {
                "id": 10,
                "name": "Clementina DuBuque",
                "username": "Moriah.Stanton",
                "email": "Rey.Padberg@karina.biz",
                "website": "ambrose.net",
            }
        ]
    }),
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Post_All_Posts';
    }
}
</script>

<style scoped>

</style>
