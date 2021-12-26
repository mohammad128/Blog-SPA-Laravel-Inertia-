<template>
    <vs-input
        type="password"
        v-model="password_value"
        label-placeholder="Password"
        :progress="getProgress"
        :visiblePassword="hasVisiblePassword"
        icon-after
        @click-icon="hasVisiblePassword = !hasVisiblePassword">
        <template #icon>
            <i v-if="!hasVisiblePassword" class='bx bx-show-alt'></i>
            <i v-else class='bx bx-hide'></i>
        </template>

        <template v-if="getProgress >= 100" #message-success>
            Secure password
        </template>

    </vs-input>

</template>

<script>
export default {
    name: "ProgressPassword",
    props: ['value'],
    data() {
        return {
            password_value: '',
            hasVisiblePassword: false
        }
    },
    computed: {
        getProgress() {
            let progress = 1
            if (/\d/.test(this.value)) {
                progress += 20
            }
            if (/(.*[A-Z].*)/.test(this.value)) {
                progress += 20
            }
            if (/(.*[a-z].*)/.test(this.value)) {
                progress += 20
            }
            if (this.value.length >= 6) {
                progress += 20
            }
            if (/[^A-Za-z0-9]/.test(this.value)) {
                progress += 20
            }
            return progress
        }
    },
    watch: {
        password_value: function (val) {
            this.$emit('input', val);
        }
    },
    mounted() {
        this.value = this.password_value;
    }

}
</script>

<style scoped>

</style>
