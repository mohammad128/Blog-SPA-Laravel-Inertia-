<template>
    <div class="flex flex-col  p-8">
        <div class="flex flex-col w-full max-w-lg gap-8">
            <vs-input
                block
                type="password"
                v-model="currentPassword"
                label-placeholder="Current Password"
                icon-after >
                <template #icon>
                    <i class='bx bx-key'></i>
                </template>
                <template #message-success>
                    &nbsp;
                </template>
            </vs-input>

            <vs-input
                block
                type="password"
                v-model="newPassword"
                label-placeholder="New Password"
                :progress="getProgress"
                :visiblePassword="hasVisiblePassword"
                icon-after
                @click-icon="hasVisiblePassword = !hasVisiblePassword">
                <template #icon>
                    <i v-if="!hasVisiblePassword" class='bx bx-show-alt'></i>
                    <i v-else class='bx bx-hide'></i>
                </template>

                <template v-if="getProgress >= 100" #message-success>
                    Secure password
                </template>
                <template v-else #message-success>
                    &nbsp;
                </template>
            </vs-input>

            <vs-input
                block
                type="password"
                v-model="confirmNewPassword"
                label-placeholder="Confirm Password"
                icon-after >
                <template #icon>
                    <i class='bx bx-key'></i>
                </template>
                <template #message-success>
                    &nbsp;
                </template>
            </vs-input>
        </div>
        <div class="w-full flex justify-end">
            <div class="flex flex-row justify-between">
                <vs-button @click="doUpdate()">
                    &nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;
                </vs-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UpdatePassword",
    props: {
        user: Object
    },
    data() {
        return {
            hasVisiblePassword: false,

            currentPassword: '',
            newPassword: '',
            confirmNewPassword: '',
        }
    },
    computed: {
        getProgress() {
            let progress = 0
            if (/\d/.test(this.newPassword)) {
                progress += 20
            }
            if (/(.*[A-Z].*)/.test(this.newPassword)) {
                progress += 20
            }
            if (/(.*[a-z].*)/.test(this.newPassword)) {
                progress += 20
            }
            if (this.newPassword.length >= 6) {
                progress += 20
            }
            if (/[^A-Za-z0-9]/.test(this.newPassword)) {
                progress += 20
            }
            return progress
        }
    },
    methods: {
        doUpdate() {
            alert('doUpdate')
        }
    }
}
</script>

<style scoped>

</style>
