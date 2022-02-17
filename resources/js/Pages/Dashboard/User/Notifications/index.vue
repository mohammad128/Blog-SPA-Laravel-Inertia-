<template>
    <DashboardLayout>
        <div class="w-full bg-white rounded-lg shadow-lg">
            <div class="w-full p-2 flex flex-row items-center justify-between">
                <span class="text-md font-bold text-white px-4 py-1 bg-sky-600 rounded-lg">New {{$page.props.user.new_notifications_count}}</span>
                <vs-button danger transparent @click="link($event)" method="delete" :url="route('dashboard.user.clearNotifications')"> <i class="bx bx-trash"></i> Clear Notification List</vs-button>
            </div>
            <hr>
            <Notifications  />
        </div>
    </DashboardLayout>
</template>

<script>
import Notifications from "@/Pages/Dashboard/User/Notifications/Components/Notifications";

export default {
    name: "NotificationsIndex",
    components: {
        Notifications
    },
    props: {
    },
}
</script>

<style scoped>

</style>
