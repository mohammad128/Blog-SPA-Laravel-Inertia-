<template>
    <div class="flex flex-col  p-8">
        <div class="flex flex-col w-full max-w-lg gap-6">

            <vs-input
                block
                type="password"
                state="primary"
                v-model="form.current_password"
                label-placeholder="Current Password">
                <template #icon>
                    <i class='bx bx-key'></i>
                </template>
                <template #message-success>
                    &nbsp;
                </template>
                <template #message-danger v-if="errors.updatePassword">
                    {{ errors.updatePassword['current_password'] }}
                </template>
            </vs-input>

            <vs-input
                block
                type="password"
                state="primary"
                v-model="form.password"
                label-placeholder="New Password"
                :progress="getProgress"
                :visiblePassword="hasVisiblePassword"
                @click-icon="hasVisiblePassword = !hasVisiblePassword">
                <template #icon>
                    <i v-if="!hasVisiblePassword" class='bx bx-show-alt'></i>
                    <i v-else class='bx bx-hide'></i>
                </template>
                <template #message-danger v-if="errors.updatePassword">
                    {{ errors.updatePassword['password'] }}
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
                state="primary"
                v-model="form.password_confirmation"
                label-placeholder="Confirm Password">
                <template #icon>
                    <i class='bx bx-key'></i>
                </template>
                <template #message-danger v-if="errors.updatePassword">
                    {{ errors.updatePassword['password_confirmation'] }}
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
            form: this.$inertia.form({
                current_password: '',
                password: '',
                password_confirmation: '',
            })
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
        getProgress() {
            let progress = 0
            if (/\d/.test(this.form.password)) {
                progress += 20
            }
            if (/(.*[A-Z].*)/.test(this.form.password)) {
                progress += 20
            }
            if (/(.*[a-z].*)/.test(this.form.password)) {
                progress += 20
            }
            if (this.form.password.length >= 6) {
                progress += 20
            }
            if (/[^A-Za-z0-9]/.test(this.form.password)) {
                progress += 20
            }
            return progress
        }
    },
    methods: {
        doUpdate() {
            this.form.put( route('user-password.update'),{
                preserveState: true,
                preserveScroll: true,
                onSuccess: () =>  {
                    if(this.errors.updatePassword == null) {
                        this.form.reset();
                        this.openNotification('top-right', 'success', `<i class='bx bx-check-double' ></i>`,
                            'Password Update', "Password updated successfully." );
                    }
                },

            } );
        },

        openNotification(position = null, color, icon, title, text) {
            this.$vs.notification({
                icon,
                color,
                position,
                title: title,
                text: text
            })
        }
    }
}
</script>

<style scoped>

</style>
