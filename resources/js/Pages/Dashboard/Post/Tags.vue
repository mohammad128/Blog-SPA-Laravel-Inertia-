<template>
    <DashboardLayout>
        <div class="flex flex-col lg:flex-row gap-4">
            <form @submit.prevent="submit">
                <div class="py-8 px-4 bg-white rounded-2xl lg:shadow-2xl lg:sticky lg:top-16 grid gap-8 lg:w-64">
                    <h3 class="text-lg font-bold text-gray-600">Add New Tag:</h3>
                    <vs-input label-placeholder="Name" v-model="form.name">
                    </vs-input>
                    <vs-input label-placeholder="Slug" v-model="form.slug">
                    </vs-input>

                    <vs-button
                        circle
                        floating
                        icon
                        color="facebook"
                        :loading="form.processing"
                        :disabled="!form.name"
                    >
                        Add Tag
                        <i class='bx bx-plus' ></i>
                    </vs-button>
                </div>
            </form>
            <div class="flex-1 bg-white rounded-2xl py-8 px-4">
                <p v-for="i in 40">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque consectetur deserunt, eos facilis incidunt ipsum iste laborum nemo officiis porro quam quasi quia quis reiciendis similique tempora tempore vel vero!</p>
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import DashboardLayout from "@/Pages/Layouts/DashboardLayout";

export default {
    name: "Tags",
    components: {
        DashboardLayout,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                slug: ''
            })
        }
    },
    methods: {
        submit() {
            alert()
        }
    }
}
</script>

<style scoped>

</style>
