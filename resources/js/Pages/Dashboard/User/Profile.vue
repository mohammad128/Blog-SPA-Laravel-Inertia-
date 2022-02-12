<template>
    <DashboardLayout>
        <div class="flex flex-col flex-col-1 gap-8">

            <div class="flex flex-row">
                <div class="pt-8 pr-8 max-w-lg w-full">
                    <h2 class="text-2xl font-bold text-gray-900">Profile Information</h2>
                    <p class="text-sm text-gray-700">Update your account's profile information and email address.</p>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow-lg gap-0">
                    <UpdateProfileInformation :user="user"></UpdateProfileInformation>
                </div>
            </div>

            <div class="flex flex-row">
                <div class="pt-8 pr-8 max-w-lg w-full">
                    <h2 class="text-2xl font-bold text-gray-900">Update Password</h2>
                    <p class="text-sm text-gray-700">Ensure your account is using a long, random password to stay secure.</p>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow-lg gap-0">
                    <UpdatePassword :user="user"></UpdatePassword>
                </div>
            </div>

            <div class="flex flex-row">
                <div class="pt-8 pr-8 max-w-lg w-full">
                    <h2 class="text-2xl font-bold text-gray-900">Two Factor Authentication</h2>
                    <p class="text-sm text-gray-700">Add additional security to your account using two factor authentication.</p>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow-lg gap-0">
                    <TwoFactorAuthentication :user="user"></TwoFactorAuthentication>
                </div>
            </div>

            <div class="flex flex-row">
                <div class="pt-8 pr-8 max-w-lg w-full">
                    <h2 class="text-2xl font-bold text-gray-900">Browser Sessions</h2>
                    <p class="text-sm text-gray-700">Manage and log out your active sessions on other browsers and devices.</p>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow-lg gap-0">
                    <LogoutOtherBrowserSessions :sessions="sessions"></LogoutOtherBrowserSessions>
                </div>
            </div>

<!--            <div class="flex flex-row">-->
<!--                <div class="pt-8 pr-8 max-w-lg w-full">-->
<!--                    <h2 class="text-2xl font-bold text-gray-900">Delete Account</h2>-->
<!--                    <p class="text-sm text-gray-700">Permanently delete your account.</p>-->
<!--                </div>-->
<!--                <div class="flex-1 bg-white rounded-lg shadow-lg gap-0">-->
<!--                    <DeleteAcount ></DeleteAcount>-->
<!--                </div>-->
<!--            </div>-->

        </div>
    </DashboardLayout>
</template>

<script>
import UpdateProfileInformation from "@/Pages/Dashboard/Components/UpdateProfileInformation";
import UpdatePassword from "@/Pages/Dashboard/Components/UpdatePassword";
import TwoFactorAuthentication from "@/Pages/Dashboard/Components/TwoFactorAuthentication";
import LogoutOtherBrowserSessions from "@/Pages/Dashboard/Components/LogoutOtherBrowserSessions";
import DeleteAcount from "@/Pages/Dashboard/Components/DeleteAccount";

export default {
    name: "Profile",
    components: {
        UpdateProfileInformation,
        UpdatePassword,
        TwoFactorAuthentication,
        LogoutOtherBrowserSessions,
        DeleteAcount
    },
    props: {
        user: Object,
        sessions: Array
    },
    data() {
        return {

        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'User_Profile';
    },
}
</script>

<style scoped>

</style>
