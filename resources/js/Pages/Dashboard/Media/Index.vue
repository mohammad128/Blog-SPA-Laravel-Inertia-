<template>
    <DashboardLayout>

        <MediaManager/>

    </DashboardLayout>

</template>

<script>
import DashboardLayout from '@/Pages/Layouts/DashboardLayout';
import MediaManager from '@/Pages/Dashboard/Components/MediaManager';

export default {
    name: "Index",
    components: {
        DashboardLayout,
        MediaManager
    },
    data() {
        return {

        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Media_All_Media';
    }
}
</script>

<style scoped>
</style>
