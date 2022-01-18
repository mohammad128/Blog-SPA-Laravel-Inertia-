<template>

    <div class="flex justify-center items-center w-screen h-screen">

        <div class="p-4 bg-white rounded-lg flex flex-row gap-4">
            <vs-input
                primary
                v-model="password"
                type="password"
                state="primary"
                placeholder="Password" >
                <template #icon>
                    <i class='bx bx-lock-open-alt'></i>
                </template>
            </vs-input>

            <vs-button>
                Confirm
            </vs-button>

        </div>

    </div>

</template>

<script>
export default {
    name: "ConfirmPasswordDialog",
    data() {
        return {
            active: true,
            password: ''
        }
    }
}
</script>

<style scoped>

</style>
