<template>
    <div class="flex flex-col p-8">

        <div class="flex-flex-col gap-4" v-if="!user.two_factor_enabled"><!-- Tow Factor Authentication is Disabled -->
            <h2 class="text-xl text-gray-900 font-bold max-w-lg" >You have not enabled two factor authentication.</h2>
            <p class="text-gray text-md max-w-lg text-gray-600">
                When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
            </p>
            <div>
                <ConfirmPasswordDialog @confirmed="enableTwoFactorAuthentication">
                    <vs-button  :loading="enabling">
                        Enable
                    </vs-button>
                </ConfirmPasswordDialog>
            </div>
        </div>
        <div class="flex-flex-col gap-4" v-else><!-- Tow Factor Authentication is Enabled -->
            <h2 class="text-xl text-gray-900 font-bold max-w-lg">You have enabled two factor authentication.</h2>
            <div>
                <p class="text-gray text-md max-w-lg text-gray-600">
                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                </p>
                <p class="text-gray text-md max-w-lg pt-2 text-gray-800 font-bold">
                    Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                </p>
                <div class="mt-4" v-html="qrCode">
                </div>
                <div v-if="recoveryCodes.length > 0">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-4">
                <ConfirmPasswordDialog v-if="user.two_factor_enabled" @confirmed="regenerateRecoveryCodes">
                    <vs-button transparent dark>
                        Regenerate Recovery Codes
                    </vs-button>
                </ConfirmPasswordDialog>
                <ConfirmPasswordDialog @confirmed="disableTwoFactorAuthentication">
                    <vs-button danger :loading="disabling">
                        Disabled
                    </vs-button>
                </ConfirmPasswordDialog>
            </div>
        </div>



    </div>
</template>

<script>
import ConfirmPasswordDialog from "@/Pages/Dashboard/Components/ConfirmPasswordDialog";

export default {
    name: "TwoFactorAuthentication",
    components: {
        ConfirmPasswordDialog
    },
    props: {
        user: Object
    },
    data() {
        return {
            enabling: false,
            disabling: false,

            qrCode: null,
            recoveryCodes: [],
        }
    },
    methods: {

        enableTwoFactorAuthentication() {
            this.enabling = true

            // let url = '/user/two-factor-authentication';
            let url = route('two-factor.enable');

            this.$inertia.post(url, {}, {
                preserveScroll: true,
                onSuccess: () => Promise.all([
                    this.showQrCode(),
                    this.showRecoveryCodes(),
                ]),
                onFinish: () => (this.enabling = false),
            })
        },

        showQrCode() {
            // let url = '/user/two-factor-qr-code';
            let url = route('two-factor.qr-code');
            return axios.get(url)
                .then(response => {
                    this.qrCode = response.data.svg
                })
        },

        showRecoveryCodes() {
            // let url = '/user/two-factor-recovery-codes';
            let url = route('two-factor.recovery-codes');
            return axios.get(url)
                .then(response => {
                    this.recoveryCodes = response.data
                })
        },

        regenerateRecoveryCodes() {
            // let url = '/user/two-factor-recovery-codes';
            let url = route('two-factor.recovery-codes');
            axios.post(url)
                .then(response => {
                    this.showRecoveryCodes()
                })
        },

        disableTwoFactorAuthentication() {
            this.disabling = true
            // let url = '/user/two-factor-authentication';
            let url = route('two-factor.disable');
            this.$inertia.delete(url, {
                preserveScroll: true,
                onSuccess: () => (this.disabling = false),
            })
        },
    },
    mounted() {
        if(this.user.two_factor_enabled) {
            this.showQrCode();
            this.showRecoveryCodes();
        }
    },
}
</script>

<style scoped>

</style>
