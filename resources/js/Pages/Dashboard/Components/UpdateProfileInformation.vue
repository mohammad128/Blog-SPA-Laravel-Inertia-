<template>
    <div class="flex flex-col p-8">
        <div class="flex flex-col items-center justify-center">
            <div>
                <div class="pl-6 flex flex-col justify-center items-center w-38 h-38 relative">
                    <input type="file" class="hidden"
                           ref="photo"
                           @change="updatePhotoPreview">

                    <img v-if="!photoPreview" class="ring-offset-4 ring-2 transform scale-95 hover:scale-100 hover:ring-offset-2 hover:ring-4 transition-all duration-700 rounded-full w-24 h-24 cursor-pointer"
                         :src="editData.profile_photo_url"
                         alt=""
                         @click="selectImage()"
                    >
                    <span v-else
                          class="block ring-offset-4 ring-2 transform scale-95 hover:scale-100 hover:ring-offset-2 hover:ring-4 transition-all duration-700 rounded-full w-24 h-24 cursor-pointer"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"
                          @click="selectImage()"
                    >
                    </span>

                    <div class="flex flex-row gap-2 -mt-6 pb-6">
                            <span class="text-lg text-rose-700 font-bold" v-if="editData.profile_photo_path" >
                                <vs-button danger size="mini" @click="link($event)" method="delete" :url="route('dashboard.user.profile.removeImage')">
                                    <i class="bx bx-trash "></i>&nbsp;Remove Image
                                </vs-button>
                            </span>
                        <span v-show="photoPreview" class="text-lg text-rose-700 ">
                                <vs-button circle icon flat warn :disabled="!photoPreview" @click="removePhoto">
                                    <i class="bx bx-reset font-bold text-4xl"></i>
                                </vs-button>
                            </span>
                    </div>
                    <span class="text-xs text-rose-600" v-show="errors['photo']">{{ errors['photo'] }}</span>
                </div>
            </div>
            <div class="mt-4 flex flex-col md:flex-row w-full gap-4">
                <vs-input block v-model="editData.username" placeholder="UserName" state="primary" disabled>
                    <template #icon>
                        <i class='bx bx-user'></i>
                    </template>
                    <template v-if="errors['username']" #message-danger>
                        {{ errors['username'] }}
                    </template>
                    <template v-else #message-danger>&nbsp;</template>
                </vs-input>
                <vs-input type="email" block v-model="editData.email" placeholder="Email" state="primary" disabled>
                    <template #icon>
                        @
                    </template>
                    <template v-if="errors['email']" #message-danger>
                        {{ errors['email'] }}
                    </template>
                    <template v-else #message-danger>&nbsp;</template>
                </vs-input>
            </div>
            <div class="mt-4 flex flex-col md:flex-row w-full gap-4">
                <vs-input block v-model="editData.first_name" placeholder="First Name" state="primary">
                    <template #icon>
                        <i class='bx bx-user'></i>
                    </template>
                    <template v-if="errors['first_name']" #message-danger>
                        {{ errors['first_name'] }}
                    </template>
                    <template v-else #message-danger>&nbsp;</template>
                </vs-input>
                <vs-input block v-model="editData.last_name" placeholder="Last Name" state="primary">
                    <template #icon>
                        <i class='bx bx-user'></i>
                    </template>
                    <template v-if="errors['last_name']" #message-danger>
                        {{ errors['last_name'] }}
                    </template>
                    <template v-else #message-danger>&nbsp;</template>
                </vs-input>
            </div>
            <div class="w-full flex justify-end">
                <div class="flex flex-row justify-between">
                    <vs-button @click="doEdit()">
                        &nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;
                    </vs-button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "UpdatePofileInformation",
    props: {
        user: Object
    },
    data() {
        return {
            editData: this.$inertia.form({
                _method: 'put',
                // id: 0,
                // email: '',
                // username: '',
                first_name: '',
                last_name: '',
                profile_photo_url: '',
                profile_photo_path: '',
                photo: null
            }),
            photoPreview: null
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
    },
    mounted() {
        // this.editData.id = this.user.id;
        // this.editData.email = this.user.email;
        // this.editData.username = this.user.username;
        this.editData.first_name = this.user.first_name;
        this.editData.last_name = this.user.last_name;
        this.editData.profile_photo_url = this.user.profile_photo_url;
        this.editData.profile_photo_path = this.user.profile_photo_path;
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
                    this.editData.photo = this.$refs.photo.files[0];
                }
            };
            reader.readAsDataURL(this.$refs.photo.files[0]);
        },
        removePhoto() {
            this.photoPreview = null;
            this.editData.photo = null;
            this.$refs.photo.value=null;
        },
        showEditUserDialog(tr) {
            let tmp = tr;//Object.assign({}, tr);
            // this.editData.id  = tmp.id
            this.editData.email  = tmp.email
            this.editData.name  = tmp.name
            this.editData.profile_photo_url  = tmp.profile_photo_url
            this.editData.profile_photo_path  = tmp.profile_photo_path
            this.editData.roles  = tmp.roles.map(item=>item['name']);
            this.editActive=true;
        },
        doEdit() {
            this.editData.post( route('dashboard.user.profile.update'), {
                preserveState: true,
                preserveScroll: true
            });
        }
    }
}
</script>

<style scoped>

</style>
