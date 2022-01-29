<template>
    <DashboardLayout>
        <div class="flex flex-col gap-4">
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Site Title</span>
                <vs-input placeholder="Site Title"></vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Tagline</span>
                <vs-input placeholder="Tagline"></vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Site Address (URL)</span>
                <vs-input placeholder="Site Address (URL)"></vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">New User Default Role</span>

                <vs-select
                    filter
                    multiple
                    v-model="selectedRoles"
                    placeholder="Filter Multiple"
                >
                    <template v-for="item in _roles" >
                        <vs-option :label="item.name" :value="item">
                            {{ item.name }}
                        </vs-option>
                    </template>

                </vs-select>
            </div>
        </div>

    </DashboardLayout>
</template>

<script>
export default {
    name: "Settings",
    props:{
        menus: Array,
        _roles: Array
    },
    data() {
        return {
            selectedRoles: []
        }
    }
}
</script>

<style scoped>

</style>
