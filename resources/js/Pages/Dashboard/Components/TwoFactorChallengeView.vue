<template>
    <div class="flex flex-col gap-16 h-screen w-screen items-center justify-center">
        <i class="-mt-16 bx bxs-user text-6xl text-sky-800"></i>
        <div class="p-4 bg-white rounded-lg w-full max-w-lg shadow-lg flex flex-col gap-4">
            <div v-if="use_code" class="flex flex-col gap-4">
                <p class="text-sm text-gray-700">
                    Please confirm access to your account by entering the authentication code provided by your authenticator application.
                </p>
                <vs-input v-model="form.code" placeholder="Code" block state="primary">
                    <template #icon>
                        <i class='bx bx-key'></i>
                    </template>
                </vs-input>
            </div>

            <div v-else class="flex flex-col gap-4">
                <p class="text-sm text-gray-700">
                    Please confirm access to your account by entering one of your emergency recovery codes.
                </p>
                <vs-input v-model="form.recovery_code" placeholder="Recovery Code" block state="primary">
                    <template #icon>
                        <i class='bx bx-key'></i>
                    </template>
                </vs-input>
            </div>

            <div class="flex flex-row justify-between">
                <span class="text-sm text-sky-800 underline decoration-double cursor-pointer decoration-sky-400"
                      @click="use_code=true" v-if="!use_code">Use an authentication code</span>
                <span class="text-sm text-sky-800 underline decoration-double cursor-pointer decoration-sky-400"
                      @click="use_code=false" v-else>Use a recovery code</span>
                <vs-button @click="login">
                    Login
                </vs-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TwoFactorChallengeView",
    data() {
        return {
            use_code: true,
            form: this.$inertia.form({
                code: '',
                recovery_code: ''
            })
        }
    },
    methods: {
        login() {
            this.form.post(route('two-factor.login'));
        }
    }
}
</script>

<style scoped>

</style>
