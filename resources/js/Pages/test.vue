<template>

    <DashboardLayout>
        Rich Text Editor:

        <AdvanceEditor v-model="body"/>

    </DashboardLayout>

</template>
<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";
import AdvanceEditor from "@/Pages/Dashboard/Components/AdvanceEditor"

export default {
    components: {
        DashboardLayout,
        AdvanceEditor
    },
    data() {
        return {
            body: '',
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

