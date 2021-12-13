<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-300 dark:bg-gray-900 items-center flex-col">

<!--        <jet-validation-errors class="mb-4" />-->

<!--        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">-->
<!--            {{ status }}-->
<!--        </div>-->


<!--        <div class="fixed top-0 left-0">-->

<!--            <vs-button circle-->
<!--                       border-->
<!--                       size="xl"-->
<!--                       @click="link($event)"-->
<!--                       :url="route('index')"-->
<!--                       method="get"-->
<!--            >-->
<!--                <i class='bx bx-home'></i>-->
<!--            </vs-button>-->

<!--        </div>-->

        <div>
            <div class="flex justify-between fixed top-0 right-0 sm:relative mb-6 sm:px-4">

                <vs-button
                    icon
                    dark
                    shadow
                    @click="link($event)"
                    :url="route('index')"
                    method="get"
                >
                    <i class='bx bx-home-alt'></i>
                </vs-button>
                    <h3 class="text-lg font-extrabold text-gray-700 hidden sm:block">Login</h3>
                <vs-button
                    icon
                    success
                    shadow
                    @click="link($event)"
                    :url="route('register')"
                    method="get"
                >
                    <i class='bx bx-user-plus'></i>
                </vs-button>
            </div>

            <h3 class="text-lg font-extrabold text-gray-700 block sm:hidden text-center mb-5">Login</h3>

            <form class="loginForm" @submit.prevent="submit">
                <div class="center content-inputs grid sm:grid-cols-1 md:grid-cols-2 justify-items-center">
                    <vs-input v-model="form.email" placeholder="User name"
                              :danger="errors['email']!=null  ? true : false"
                              :state="errors['email'] ? 'danger' : ''">
                        <template #icon>
                            <i class='bx bx-user'></i>
                        </template>
                    </vs-input>

                    <vs-input id="inputPassword" type="password" icon-after v-model="form.password" placeholder="Password"
                              :danger="errors['password']!=null ? true : false"
                              :state="errors['password'] ? 'danger' : ''"
                              :visiblePassword="hasVisiblePassword"
                              @click-icon="hasVisiblePassword = !hasVisiblePassword">
                        <template #icon>
                            <i v-if="!hasVisiblePassword" class='bx bx-show-alt'></i>
                            <i v-else class='bx bx-hide'></i>
                        </template>

                    </vs-input>

                    <vs-checkbox v-model="form.remember">
                        Remember me
                    </vs-checkbox>

                    <inertia-link v-if="canResetPassword" :href="route('password.request')"
                                  class="pt-2 block md:text-right sm:text-left underline text-xs text-gray-500 hover:text-gray-700 transition duration-300 ease-in-out  transform hover:-translate-y-1 hover:scale-y-105">
                        Forgot your password?
                    </inertia-link>
                </div>
                <div class="grid justify-items-center pt-4 sm:pt-2">
                    <vs-button
                        size="large"
                        :loading="form.processing"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    >
                        <i class='bx bx-log-in' ></i>  &nbsp;&nbsp;Login
                    </vs-button>
                </div>
            </form>
        </div>
    </div>
</template>
<style scoped>
.loginForm{
}
.loginForm .vs-input-parent {
    background: transparent;
    border: 0;
}
</style>
<style>
#inputPassword{
    /*margin-left: -0.7em;*/
}
#vs-input--inputPassword:focus {
    background: rgba(var(--vs-gray-3), 1);
    /*padding-left: 15px;*/
    border-color: rgba(var(--vs-gray-3), 1);
    box-shadow: none;
}
</style>
<script>
    export default {
        components: {
        },

        props: {
            canResetPassword: Boolean,
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                }),
                hasVisiblePassword: false,
            }
        },
        computed: {
            errors() {
                return this.$page.props.errors;
            },
        },
        methods: {

            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => {
                            this.form.reset('password');
                            for(var key in this.errors) {
                                this.openNotification('top-right', 'danger', `<i class='bx bxs-bug' ></i>`,
                                    'Whoops! Something went wrong.', this.errors[key] );
                            }
                        }
                    })
            },

            openNotification(position = null, color, icon, title, text) {
                const noti = this.$vs.notification({
                    icon,
                    color,
                    position,
                    title: title,
                    text: text
                })
            }
        },
        mounted: function () {
        }
    }
</script>
