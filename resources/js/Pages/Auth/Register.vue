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
                <h3 class="text-lg font-extrabold text-gray-700 hidden sm:block">Register</h3>
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
            <h3 class="text-lg font-extrabold text-gray-700 block sm:hidden text-center mb-5">Register</h3>
            <form @submit.prevent="submit">
                <div class="center content-inputs grid sm:grid-cols-1 md:grid-cols-2 justify-items-center gap-4">
                    <vs-input v-model="form.name" placeholder="User name"
                              :danger="errors['name']!=null ? true : false  "
                              :state="errors['name'] ? 'danger' : ''" >
                        <template #icon>
                            <i class='bx bx-user'></i>
                        </template>
                    </vs-input>
                    <vs-input v-model="form.email" placeholder="Email"
                              :danger="errors['email']!=null ? true : false "
                              :state="errors['email'] ? 'danger' : ''" >
                        <template #icon>
                            @
                        </template>
                    </vs-input>
                    <vs-input v-model="form.password" placeholder="Password"
                              :danger="errors['password']!=null ? true : false  "
                              :state="errors['password'] ? 'danger' : ''" >
                        <template #icon>
                            <i class='bx bx-key'></i>
                        </template>
                    </vs-input>
                    <vs-input v-model="form.password_confirmation" placeholder="Confirm Password"
                              :danger="errors['password_confirmation']!=null ? true : false "
                              :state="errors['password_confirmation'] ? 'danger' : ''" >
                        <template #icon>
                            <i class='bx bx-key'></i>
                        </template>
                    </vs-input>
                </div>
                <div class="grid justify-items-center pt-4 sm:pt-2">
                    <vs-button
                        size="large"
                        :loading="form.processing"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    >
                        <i class='bx bx-log-in' ></i>  &nbsp;&nbsp;Register
                    </vs-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

    export default {
        components: {
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
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
                this.form.post(this.route('register'), {
                    onFinish: () => {
                        this.form.reset('password', 'password_confirmation')
                        for(var key in this.errors) {
                            this.openNotification('top-right', 'danger', `<i class='bx bxs-bug' ></i>`,
                                'Whoops! Something went wrong.', this.errors[key] );
                        }
                    },
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
