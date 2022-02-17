<template>
    <div class="min-h-screen w-full  flex flex-col items-center justify-center bgLock">
        <div class="gap-8 px-16 py-8 card flex flex-col">
            <h3 class="text-2xl text-white font-bold ">This content is protected</h3>
            <div class="flex flex-col gap-2">
                <vs-input type="password" label-placeholder="Password" icon-after block transparent  border danger
                          v-model="form.password"
                          :loading="form.processing"
                          :danger="errors['password']!=null  ? true : false"
                          :state="errors['password'] ? 'danger' : ''"
                          @keyup.enter="checkPassword"
                          @click-icon="checkPassword">
                    <template #icon>
                        <i class='bx bx-right-arrow-alt p-2 rounded-lg text-2xl ml-20 bg-white'></i>
                    </template>
                    <template #message-danger v-if="errors['password']">
                        {{ errors['password'] }}
                    </template>
                </vs-input>
            </div>
        </div>
        <i class="bx bx-home font-bold text-5xl text-white rounded-lg card p-2 my-4 cursor-pointer" @click="link($event)" method="get" :url="route('index')"></i>
    </div>
</template>

<script>
export default {
    name: "HasPassword",
    props: {
        className: String,
        column: String,
        id: String,
    },
    data() {
        return {
            form: this.$inertia.form({
                password: ''
            })
        }
    },
    mounted() {
    },
    methods: {
        checkPassword() {
            this.form.transform(data=>({
                ...data,
                'className': this.className,
                'column': this.column,
                'id': this.id,
                'redirect': window.location.href
            })).post(route('hasPassword.check'));
        }
    }
}
</script>

<style scoped>
.bgLock {
    background-image: url('/uploads/images/bgLock.jpg');
    background-position: center;
    background-size: cover;
}

/* Glassmorphism card effect */
.card {
    backdrop-filter: blur(8px) saturate(180%);
    -webkit-backdrop-filter: blur(8px) saturate(180%);
    background-color: rgba(255, 255, 255, 0.5);
    border-radius: 12px;
    border: 1px solid rgba(209, 213, 219, 0.3);
}
</style>
