<template>

    <DashboardLayout>
        Rich Text Editor:

<!--        <AdvanceEditor v-model="body"/>-->

        <TagInput :tags="tags" v-model="selectedTags"/>
{{selectedTags}}
    </DashboardLayout>

</template>
<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import AdvanceEditor from "@/Pages/Dashboard/Components/AdvanceEditor"
import TagInput from "@/Pages/Dashboard/Components/TagInput"

export default {
    components: {
        DashboardLayout,
        AdvanceEditor,
        TagInput
    },
    data() {
        return {
            body: '',
            selectedTags: [],
            tags: [
                { value: "ironman", code: "im" },
                { value: "antman", code: "am" },
                { value: "captain america", code: "ca" },
                { value: "thor", code: "th" },
                { value: "spiderman", code: "sm" },
                { value: "mahyar", code: "sm" }
            ]
        }
    },
    methods: {
    },
    watch: {
        body: function(val){
            console.log(val)
        }
    },
    mounted() {


    }
}
</script>
<style >
.fr-floating-btn {
    padding-top: 1px;
}
</style>

