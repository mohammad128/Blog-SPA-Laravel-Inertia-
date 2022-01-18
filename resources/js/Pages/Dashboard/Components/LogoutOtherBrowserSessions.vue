<template>
    <div class="flex flex-col p-4 gap-4">
        <p class="text-sm text-gray-800">
            If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
        </p>
        <div v-for="(session, index) in sessions" class="flex flex-row items-center gap-2">
            <i class="text-4xl bx" :class="{ 'bx-mobile': !session.agent.is_desktop, 'bx-desktop': session.agent.is_desktop }"></i>
            <div class="flex flex-col">
                <div class="text-sm text-gray-700 flex flex-row justify-center items-center gap-1">
                    {{ session.agent.platform }} - {{ session.agent.browser }}
                    <i v-if="session.agent.browser.toLowerCase() == 'firefox'" class="bx bxl-firefox text-lg"></i>
                    <i v-if="session.agent.browser.toLowerCase() == 'chrome'" class="bx bxl-chrome text-lg"></i>
                    <i v-if="session.agent.browser.toLowerCase() == 'edge'" class="bx bxl-edge text-lg"></i>
                    <i v-if="session.agent.browser.toLowerCase() == 'opera'" class="bx bxl-opera text-lg"></i>
                    <i v-if="session.agent.browser.toLowerCase() == 'internet-explorer'" class="bx bxl-internet-explorer text-lg"></i>
                    <i v-if="session.agent.browser.toLowerCase() == 'compass'" class="bx bxl-compass text-lg"></i>

                </div>
                <div>
                    <span class="text-xs text-gray-800">{{ session.ip_address }}, </span>
                    <span class="text-xs text-gray-700" v-if="!session.is_current_device">{{ session.last_active }}</span>
                    <span v-else class="text-sm text-lime-600 font-bold">This device</span>
                </div>
            </div>
        </div>
        <div>
            <vs-button dark @click="dialogActive = true">
                Log Out Other Browser Sessions
            </vs-button>
        </div>


        <vs-dialog not-center v-model="dialogActive">
            <template #header>
                <h4 class="not-margin text-sm text-gray-800 font-bold">
                    Log Out Other Browser Sessions
                </h4>
            </template>


            <div class="con-content w-full max-w-lg flex flex-col gap-4">
                <p class="text-xs text-gray-500">Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.</p>
                <vs-input
                    block
                    primary
                    v-model="form.password"
                    type="password"
                    dark
                    state="dark"
                    ref="password"
                    placeholder="Password" >
                    <template #icon>
                        <i class='bx bx-lock-open-alt'></i>
                    </template>
                    <template #message-danger v-if="errors['password']">
                        {{ errors['password'] }}
                    </template>
                </vs-input>
            </div>

            <template #footer>
                <div class="con-footer flex flex-row justify-end">
                    <vs-button @click="dialogActive=false" dark transparent>
                        Cancel
                    </vs-button>
                    <vs-button @click="logoutOtherBrowserSessions" :loading="form.processing" dark>
                        Confirm
                    </vs-button>
                </div>
            </template>
        </vs-dialog>

    </div>
</template>

<script>
export default {
    name: "LogoutOtherBrowserSessions",
    props: {
        sessions: Array,
    },
    data() {
        return {
            dialogActive: false,
            form: this.$inertia.form({
                password: '',
            })
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
    },
    methods: {

        logoutOtherBrowserSessions() {
            this.form.delete(route('other-browser-sessions.destroy'), {
                preserveScroll: true,
                onSuccess: () => { this.dialogActive = false; this.form.reset() },
                onFinish: () => this.form.reset(),
            })
        },
    }
}
</script>

<style scoped>

</style>
