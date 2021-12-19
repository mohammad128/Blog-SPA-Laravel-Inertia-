<template>
    <DashboardLayout>

        <Head>
            <title>Add New Post</title>
            <meta name="description" head-key="description" content="Add New Post">
        </Head>

        <CategoryCheckboxGroup :multiselect="true" :items="categories" v-model="selectedCat"/>

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
    data: () =>({
        catList: [
            {
                "name": "Cat 0",
                "id": "0",
            },
            {
                "name": 'Cat 1',
                "id": "1",
                "children" : [
                    {
                        "name": "Cat 1-1",
                        "id": "11",
                    },
                    {
                        "name": "Cat 1-2",
                        "id": "12",
                        "children" : [
                            {
                                "name": "Cat 1-2-1",
                                "id": "121",
                            },
                            {
                                "name": "Cat 1-2-2",
                                "id": "122",
                            },
                        ]
                    },
                ]
            },
            {
                "name": 'Cat 2',
                "id": "2",
                "children" : [
                    {
                        "name": "Cat 2-1",
                        "id": "21",
                    },
                    {
                        "name": "Cat 2-2",
                        "id": "22",
                    },
                ]
            }
        ],
        selectedCat: [],
    }),
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Post_Add_New';
    },
    watch: {
        selectedCat: function (newVal) {
            console.log("Selected Cat: " ,newVal);
        }
    }
}
</script>

<style scoped>

</style>
