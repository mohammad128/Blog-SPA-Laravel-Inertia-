<template>
    <div class="flex flex-col">
        <CategoryCheckboxGroup :multiselect="true" :items="categories" v-model="selectedCat"/>
        <div class="flex flex-row justify-end items-center bg-gray-100 rounded-lg p-2">
            <vs-button @click="add" transparent >&nbsp;&nbsp;{{buttonText}}&nbsp;&nbsp;</vs-button>
        </div>
    </div>
</template>

<script>
import CategoryCheckboxGroup from "@/Components/CategoryCheckboxGroup";

export default {
    name: "CategoryPicker",
    components:{
        CategoryCheckboxGroup
    },
    props: {
        buttonText: {
            type: String,
            default: 'OK'
        }
    },
    data() {
        return {
            selectedCat: [],
            categories: [],
        }
    },
    methods: {
        add() {
            let that = this;
            let temp = [];
            let fn = function (obj) {
                obj.map(function (item) {
                    if(item.children)
                        fn(item.children)
                    if( that.selectedCat.includes(item.id) ) {
                        temp.push(item);
                        return item;
                    }
                });
            }
            fn(this.categories);

            this.$emit('onOk', temp);
            this.selectedCat = [];
        },
    },
    mounted() {
        let that = this;
        axios.post( route('dashboard.category.all_categories') )
            .then(function (response) {
                that.categories = response.data;
            })
            .catch(function (error) {
                console.log("Post Picker:",error);
            });
    },
    watch: {
    }
}
</script>

<style scoped>

</style>
