<template>

    <div class="notificationsContainer w-full flex flex-col gap-4" ref="notificationContainer">
        <div v-if="notifications.length" class="divide-y-2 divide-gray-100">
            <div v-for="(item, index) in notifications" :key="index">
                <component :is="item.component" :data="item"/>
            </div>
        </div>
        <div v-if="(notifications.length && !loading) && page<last_page" class="text-center p-4">
            <i @click="getNotifications" class="bx bx-plus text-2xl cursor-pointer ring hover:ring-2 ring-gray-500 p-0 font-bold hover:ring-offset-4 text-gray-600 rounded-full"></i>
        </div>
        <div v-else-if="loading==true" class="text-center p-4">
            <i class="bx bx-loader-alt animate-spin text-3xl cursor-pointer p-0 font-bold text-gray-600 rounded-full"></i>
        </div>
    </div>

</template>

<script>
import PostNotificationItem from "@/Pages/Dashboard/User/Notifications/Components/PostNotificationItem";
import NotificationItem from "@/Pages/Dashboard/User/Notifications/Components/NotificationItem";
import CommentNotificationItem from "@/Pages/Dashboard/User/Notifications/Components/CommentNotificationItem";
import ReplyCommentNotificationItem from "@/Pages/Dashboard/User/Notifications/Components/ReplyCommentNotificationItem";
import CommentLikeNotificationItem from "@/Pages/Dashboard/User/Notifications/Components/CommentLikeNotificationItem";

export default {
    name: "Notifications",
    components: {
        PostNotificationItem,
        NotificationItem,
        CommentNotificationItem,
        CommentLikeNotificationItem,
        ReplyCommentNotificationItem
    },
    data() {
        return {
            notifications: [],
            last_page:0,
            page: 0,
            loading: false
        }
    },
    methods: {
        getNotifications() {
            this.loading = true;
            let that = this;
            axios.post(route('dashboard.user.getNotifications'), {
                    page: ++this.page,
                })
                .then(function (response) {
                    that.notifications.push(...response.data.data);
                    that.last_page = response.data.last_page;
                    that.page = response.data.current_page;
                    that.$page.props.user.new_notifications_count = 0;
                    that.loading = false;
                })
                .catch(function (error) {
                    console.log(error);
                    that.loading = false;
                });
        }
    },
    mounted() {
        this.getNotifications();
    }
}
</script>

<style scoped>
/*.notificationsContainer{*/
/*    background: red;*/
/*    overflow-x: hidden;*/
/*    overflow-y: scroll;*/
/*    min-height: 200px;*/
/*    height: 400px;*/
/*}*/

</style>
