<template>
    <DashboardLayout>
        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
            <div class="flex space-x-4 flex space-x-4">
                <div class="text-5xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">Create Role</span>
                </div>

            </div>
            <vs-button
                floating
                class="font-bold"
                @click="link($event)"
                method="get"
                :url="route('dashboard.user.role')"
                state="primary"
            >
                Roles
                <i class='bx bx-list-ul font-bold text-2xl' ></i>
            </vs-button>
        </div>
        <div class="flex flex-col ">
            <form class="center relative w-full p-4 bg-white rounded-lg shadow-2xl"
                @submit.prevent="submit">

                <vs-input v-model="form.name" placeholder="Role Name" state="primary">
                    <template v-if="errors['name']" #message-danger>
                        {{ errors['name'] }}
                    </template>
                </vs-input>

                <h2 class="mt-8 mb-4 text-lg font-bold text-gray-800">Permissions: <span class="text-xs text-red-400" v-if="errors['permissions']">{{ errors['permissions'] }}</span></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-4">
                    <div class="bg-gray-100 p-4 block relative group rounded-lg"
                         v-for="(cat_permissions, cat_permissions_name) in permissions" :key="cat_permissions_name">
                        <b class="text-lg text-gray-800">{{cat_permissions_name}}</b>
                        <ul class="pl-4">
                            <li class="text-gray-600" v-for="(permission,index) in cat_permissions.permissions" :key="index">
                                <vs-checkbox success :val="permission.name" v-model="form.permissions">
                                    <template #icon>
                                        <i class='bx bx-check-double'></i>
                                    </template>
                                    {{ permission.desc }}
                                </vs-checkbox>
                            </li>
                        </ul>
                    </div>
                </div>
                <vs-button
                    :disabled="form.processing || !form.name || !form.permissions.length"
                    :loading="form.processing">
                    &nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;
                </vs-button>

            </form>
        </div>
    </DashboardLayout>
</template>

<script>
export default {
    name: "UserRole",
    props: {
        permissions: Object,
        role: Object
    },
    data() {
        return {
            loading: false,

            form: this.$inertia.form({
                    name: '',
                    permissions: [],
                })

        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
    },
    methods: {
        submit() {
            this.form.put( route('dashboard.user.role.update', {'name':this.role.name}), {
                preserveState: true
            })
        }
    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Users_User_Roles';
    },
    mounted() {
        this.form.name = this.role.name;
        this.form.permissions = this.role.permissions.map(item=>item['name']);
    }

}
</script>

<style >
.vs-input{
    width:100%;
}
</style>
