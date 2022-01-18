<template>
    <div>
        <span @click="startConfirmingPassword">
            <slot/>
        </span>

        <vs-dialog not-center v-model="confirmingPassword">
            <template #header>
                <h4 class="not-margin text-sm text-gray-800 font-bold">
                    Confirm Password
                </h4>
            </template>


            <div class="con-content">
                <vs-input
                    block
                    primary
                    v-model="form.password"
                    type="password"
                    state="primary"
                    ref="password"
                    placeholder="Password" >
                    <template #icon>
                        <i class='bx bx-lock-open-alt'></i>
                    </template>
                    <template #message-danger v-if="form.error">
                        {{ form.error }}
                    </template>
                </vs-input>
            </div>

            <template #footer>
                <div class="con-footer flex flex-row justify-end">
                    <vs-button @click="confirmPassword" :loading="form.processing">
                        Confirm
                    </vs-button>
                    <vs-button @click="confirmingPassword=false" dark transparent>
                        Cancel
                    </vs-button>
                </div>
            </template>
        </vs-dialog>

    </div>

</template>

<script>
export default {
    name: "ConfirmPasswordDialog",
    data() {
        return {
            confirmingPassword: false,
            form: {
                password: '',
                error: '',
                processing: false
            },
        }
    },
    methods: {
        startConfirmingPassword() {
            this.form.error = '';
            axios.get(route('password.confirmation')).then(response => {
                if (response.data.confirmed) {
                    this.$emit('confirmed');
                } else {
                    this.confirmingPassword = true;
                }
            })
        },
        confirmPassword() {
            this.form.processing = true;
            this.form.error = '';
            let that = this;
            axios.post(route('password.confirm'), {
                password: this.form.password,
            }).then(() => {
                that.form.processing = false;
                that.closeModal()
                that.$emit('confirmed')
            }).catch(error => {
                that.form.processing = false;
                that.form.error = error.response.data.errors.password[0];
            });
        },

        closeModal() {
            this.confirmingPassword = false
            this.form.password = '';
            this.form.error = '';
        },

    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
