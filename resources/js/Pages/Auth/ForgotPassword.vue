<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-300 dark:bg-gray-900 items-center flex-col">

        <div>
            <div class="flex justify-between fixed top-0 right-0 sm:relative mb-6 ">

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
                    :url="route('login')"
                    method="get"
                >
                    <i class='bx bx-log-in'></i>
                </vs-button>
            </div>

            <h3 class="text-lg font-extrabold text-gray-700 block sm:hidden text-center mb-5">Login</h3>

            <form class="loginForm" @submit.prevent="submit">
                <div class="center content-inputs grid sm:grid-cols-1 md:grid-cols-2 justify-items-center gap-2">
                    <vs-input v-model="form.email" placeholder="Email" id="inputPassword"
                              :danger="errors['email']!=null ? true : false  "
                              :state="errors['email'] ? 'danger' : ''" >
                        <template #icon>
                            @
                        </template>
                    </vs-input>
                    <div class="grid justify-items-center pt-0 sm:pt-0">
                        <vs-button
                            :loading="form.processing"
                            dark
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        >
                            Send Password Reset Link
                        </vs-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        components: {
        },

        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },
        computed: {
            errors() {
                return this.$page.props.errors;
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('password.email'),{
                    onFinish: () => {
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
        }
    }
</script>
