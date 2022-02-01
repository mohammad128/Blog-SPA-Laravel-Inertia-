<template>
    <div class="flex flex-row">
        <vs-input v-model="icon"></vs-input>
        <IconPicker v-model="icon"/>
    </div>
</template>
<script>
import IconPicker from "@/Pages/Dashboard/Appearance/Menu/Components/IconPicker";
export default {
    components: {
        IconPicker
    },
    props: {
    },
    data() {
        return {
            icon: 'bx-user',
        }
    },
    methods: {
    },
    watch: {
    },
    created() {

    },
    mounted() {
    }
}
</script>
<style lang="scss">
</style>

