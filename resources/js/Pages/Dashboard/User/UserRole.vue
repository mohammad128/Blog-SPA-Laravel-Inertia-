<template>
    <DashboardLayout>
        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
            <div class="flex space-x-4 flex space-x-4">
                <div class="text-5xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">Roles</span>
                </div>

            </div>
            <vs-button
                floating
                class="font-bold"
                @click="link($event)"
                method="get"
                :url="route('dashboard.user.role.create')"
                state="primary"
            >
                Create Role
                <i class='bx bx-plus font-bold text-2xl' ></i>
            </vs-button>
        </div>
        <div class="flex flex-col space-y-10">
            <div class="center relative bg-white rounded-lg">

                <transition name="bounce">
                    <div class="absolute flex flex-row -top-8 -left-6"  v-if="selected.length">
                        <vs-button floating icon danger size="small" @click="delteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>
                <transition name="bounce">
                    <div class="absolute flex flex-row -bottom-8 -left-6"  v-if="selected.length">
                        <vs-button floating icon danger size="small" @click="delteSelected()" :loading="loading">
                            Delete ({{selected.length}})
                        </vs-button>
                    </div>
                </transition>

                <vs-table
                    v-model="selected"
                >
                    <template #header>
                        <vs-input v-model="search"  placeholder="Search" state="primary" type="serach" />
                    </template>
                    <template #thead>
                        <vs-tr>
                            <vs-th class="width0">
                                <vs-checkbox
                                    :indeterminate="selected.length == rolePermissions.length" v-model="allCheck"
                                    @change="selected = $vs.checkAll(selected, rolePermissions)"
                                />
                            </vs-th>
                            <vs-th sort @click="rolePermissions = $vs.sortData($event ,rolePermissions, 'name')">
                                Name
                            </vs-th>
                            <vs-th sort @click="rolePermissions = $vs.sortData($event ,rolePermissions, 'created_at')">
                                Created At
                            </vs-th>
                            <vs-th sort @click="rolePermissions = $vs.sortData($event ,rolePermissions, 'count')">
                                Permissions
                            </vs-th>
                            <vs-th sort @click="rolePermissions = $vs.sortData($event ,rolePermissions, 'id')">
                                Id
                            </vs-th>
                        </vs-tr>
                    </template>
                    <template #tbody>
                        <vs-tr
                            :key="i"
                            v-for="(tr, i) in $vs.getPage($vs.getSearch(rolePermissions, search), page, max)"
                            :data="tr"
                            :is-selected="!!selected.includes(tr)"
                            not-click-selected
                            open-expand-only-td
                        >
                            <vs-td checkbox>
                                <vs-checkbox :disabled="tr.name == 'admin'" :val="tr" v-model="selected" />
                            </vs-td>
                            <vs-td class="font-bold">
                                {{ tr.name }}
                            </vs-td>
                            <vs-td>
                                {{ tr.created_at.substring(0,10) }}
                            </vs-td>
                            <vs-td>
                                {{ tr.count }}
                            </vs-td>
                            <vs-td>
                                {{ tr.id }}
                            </vs-td>

                            <template #expand>
                                <div class="con-content flex flex-col">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div v-for="(permissions, key) in tr.permissions" :key="key" class="bg-gray-100 p-4 block relative group rounded-lg">
                                            <b class="font-bold text-md">{{key}}</b>
                                            <ul class="list-disc pl-4">
                                                <li class="text-gray-800" v-for="(permission, index) in permissions" :key="index">{{permission.desc}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="flex flex-row justify-end p-4 gap-4">
                                        <vs-button transparent danger border class="font-bold"
                                                   @click="link($event)" method="delete" :url="route('dashboard.user.role.delete',{'id': tr.id})"
                                        >
                                            Delete
                                        </vs-button>
                                        <vs-button floating gradient class="font-bold" @click="link($event)" method="get" :url="route('dashboard.user.role.edit', {'name': tr.name})">
                                            &nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;
                                        </vs-button>
                                    </div>
                                </div>
                            </template>
                        </vs-tr>
                    </template>
                    <template #footer>
                        <vs-pagination v-model="page" :length="$vs.getLength($vs.getSearch(rolePermissions, search), max)" />
                    </template>
                    <template #notFound>
                        <span class="flex items-center justify-center space-x-2">
                            <b>Not Found Post</b><i class="text-3xl text-gray-800 bx bx-sleepy"></i>
                        </span>
                    </template>
                </vs-table>

            </div>
        </div>
    </DashboardLayout>
</template>

<script>
export default {
    name: "UserRole",
    props: {
        permissions: Object,
        roles: Array,
    },
    data() {
        return {
            page: 1,
            max: 15,
            search: '',
            allCheck: false,

            loading: false,
            selected: [],

            editActive: false,
            edit: {},

            rolePermissions: [],
        }
    },
    methods: {
        initRolePermissions() {
            let that = this;

            const groupBy = (array, key) => {
                return array.reduce((result, currentValue) => {
                    (result[currentValue[key]] = result[currentValue[key]] || []).push(
                        currentValue
                    );
                    return result;
                }, {}); // empty object is the initial value for result object
            };

            for(let i=0; i<this.roles.length; i++) {
                let _result = [];
                let role = this.roles[i];
                let role_name = role['name'];
                let role_id = role['id'];
                let role_created_at = role['created_at'];
                let permissions = role.permissions.map(function(item){
                    let permission_name = item['name']
                    for(const [key,value] of Object.entries(that.permissions)  ) {
                        let cat_permission_name = key;
                        let cat_permission_desc = value['desc'];
                        let cat_permissions = value['permissions'];

                        for( const [_p_key, _p_value] of Object.entries(cat_permissions)) {
                            let _p_value_name = _p_value['name'];
                            let _p_value_desc = _p_value['desc'];
                            if( _p_value_name == permission_name )
                                _result.push({
                                    'cat': cat_permission_name,
                                    'permission': permission_name,
                                    'desc': _p_value_desc
                                });
                        }
                    }
                })
                this.rolePermissions.push({
                    'name': role_name,
                    'id': role_id,
                    'created_at': role_created_at,
                    'count': role.permissions.length,
                    'permissions': groupBy(_result, 'cat')
                })
            }
        },
        delteSelected() {
            let ids = this.selected.map(item=>item['id']);
            this.$inertia.post(route('dashboard.user.role.multiDelete'), {'ids': ids }, {
                preserveState: false
            });
        }

    },
    beforeMount() {
        this.$store.state.dashboard.activeSidebarItem = 'Users_User_Roles';
    },
    mounted() {
        this.initRolePermissions();
    }

}
</script>
<style>
.vs-input{
    width: 100%;
}
</style>
<style scoped>
.width0{
    width: 0 !important;
}
.bounce-enter-active {
    animation: bounce-in .5s;
}
.bounce-leave-active {
    animation: bounce-in .5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(1);
        opacity: 0;
    }
    50% {
        transform: scale(1.5);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

