<template>
    <DashboardLayout>

        <Head>
            <title>Add User</title>
            <meta name="description" head-key="description" content="Add User">
        </Head>

        <div class="flex flex-col w-full h-full items-center">
            <div class="w-full max-w-3xl m-auto bg-white shadow-lg rounded-lg flex flex-col justify-center items-center p-8 gap-9">

                <input type="file" class="hidden"
                       ref="photo"
                       @change="updatePhotoPreview">

                <div class="flex flex-col justify-center items-center relative">

                    <img v-if="!photoPreview" class="ring-offset-4 ring-2 transform scale-95 hover:scale-100 hover:ring-offset-2 hover:ring-4 transition-all duration-700 rounded-full w-36 h-36 cursor-pointer"
                         src="https://ui-avatars.com/api/?rounded=true&size=320&background=ddd&length=6&name=Avatar&font-size=0.22&uppercase=false&"
                         alt=""
                         @click="selectImage()"
                    >
                    <span v-else
                          class="block ring-offset-4 ring-2 transform scale-95 hover:scale-100 hover:ring-offset-2 hover:ring-4 transition-all duration-700 rounded-full w-36 h-36 cursor-pointer"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"
                          @click="selectImage()"
                    >
                    </span>
                    <span class="text-lg text-rose-700 -mt-6">
                        <vs-button circle icon flat danger :disabled="!photoPreview" @click="removePhoto">
                            <i class="bx bxs-trash font-bold text-4xl"></i>
                        </vs-button>
                    </span>
                    <span class="text-xs text-rose-600" v-show="errors['photo']">{{ errors['photo'] }}</span>

                    <vs-select
                        filter
                        multiple
                        collapse-chips
                        placeholder="Role"
                        v-model="form.roles"
                    >
                        <vs-option v-for="(role, index) in _roles" :key="index" :data="role" :label="role.name" :value="role.name">
                            {{ role.name }}
                        </vs-option>
                    </vs-select>

                </div>

                <div class="flex flex-row gap-4">
                    <vs-input v-model="form.name" placeholder="User name" :state="errors['name'] ? 'danger' :'primary'">
                        <template #icon>
                            <i class='bx bx-user'></i>
                        </template>
                        <template v-if="errors['name']" #message-danger>
                            {{ errors['name'] }}
                        </template>
                        <template v-else #message-danger>&nbsp;</template>
                    </vs-input>
                    <vs-input type="email" icon-after v-model="form.email" placeholder="Email"  :state="errors['email'] ? 'danger' :'primary'">
                        <template #icon>
                            <i class='bx bxl-gmail'></i>
                        </template>
                        <template v-if="errors['email']" #message-danger>
                            {{ errors['email'] }}
                        </template>
                        <template v-else #message-danger>&nbsp;</template>
                    </vs-input>
                </div>

                <div class="flex flex-row gap-4">
                    <vs-input type="password" v-model="form.password" placeholder="Password"  :state="errors['password'] ? 'danger' :'primary'">
                        <template #icon>
                            <i class='bx bx-lock-open-alt'></i>
                        </template>
                        <template v-if="errors['password']" #message-danger>
                            {{ errors['password'] }}
                        </template>
                        <template v-else #message-danger>&nbsp;</template>
                    </vs-input>

                    <vs-input type="password" icon-after v-model="form.password_confirmation" placeholder="Confirm Password"  :state="errors['password_confirmation'] ? 'danger' :'primary'">
                        <template #icon>
                            <i class='bx bx-lock-open-alt'></i>
                        </template>
                        <template v-if="errors['password_confirmation']" #message-danger>
                            {{ errors['password_confirmation'] }}
                        </template>
                        <template v-else #message-danger>&nbsp;</template>
                    </vs-input>
                </div>

                <div class="w-full flex justify-center text-gray-800">
                    <vs-button class="w-2/4 font-bold" transparent state="primary" @click="submit">
                        <i class="text-2xl bx bxs-user-plus"></i>&nbsp;&nbsp;
                        Add User
                    </vs-button>
                </div>

            </div>
        </div>

    </DashboardLayout>

</template>

<script>
export default {
    name: "Create",
    props: {
        _roles: Array
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                roles: [],
                password_confirmation: '',
                photo: null
            }),
            photoPreview: null
        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'User_Add_New';
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
    },
    methods: {
        selectImage() {
            this.$refs.photo.click();
        },
        updatePhotoPreview() {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photoPreview = e.target.result;
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0];
                }
            };
            reader.readAsDataURL(this.$refs.photo.files[0]);
        },
        removePhoto() {
            this.photoPreview = null;
            this.form.photo = null;
            this.$refs.photo.value=null;
        },
        submit() {
            this.form.post(route('dashboard.user.store'), {
                preserveScroll: true,
                preserveState:true
            })
        }
    }
}
</script>

<style scoped>

</style>
