<template>
    <div class="flex flex-col p-4 gap-4">
        <p class="text-sm text-gray-800">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
        <div>
            <vs-button danger @click="dialogActive = true">
                Delete Account
            </vs-button>
        </div>


        <vs-dialog not-center v-model="dialogActive">
            <template #header>
                <h4 class="not-margin text-sm text-gray-800 font-bold">
                    Delete Account
                </h4>
            </template>


            <div class="con-content w-full max-w-lg flex flex-col gap-4">
                <p class="text-xs text-gray-500">Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
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
                    <vs-button @click="deleteUser" :loading="form.processing" danger>
                        Confirm
                    </vs-button>
                </div>
            </template>
        </vs-dialog>

    </div>
</template>

<script>
export default {
    name: "DeleteAccount",
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

        deleteUser() {
            this.form.delete(route('current-user.destroy'), {
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
