<template>

    <vs-dialog v-model="active" blur>
        <template #header>
            <h4 class="not-margin">
                <b>Create Menu</b>
            </h4>
        </template>


        <div class="con-form">
            <vs-input block type="name" :danger="errors['name'] ? true : false" :state="errors['name'] ? 'danger' : ''"  v-model="form.name" placeholder="Menu Name...">
                <template v-if="errors['name']" #message-danger>
                    {{ errors['name'] }}
                </template>
            </vs-input>
        </div>

        <template #footer>
            <div class="footer-dialog flex flex- items-center flex-row-reverse">
                <vs-button @click="submit" :loading="form.processing">
                    Create
                </vs-button>
                <vs-button @click="active = false" dark transparent>
                    Cancel
                </vs-button>
            </div>
        </template>
    </vs-dialog>

</template>

<script>
export default {
    name: "CreateMenu",
    props: ['value'],
    data() {
        return {
            active: false,
            form: this.$inertia.form({
                name: '',
            })
        }
    },
    methods: {
        submit() {
            this.form.post( route('dashboard.appearance.store'),{
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.active = false;
                    this.form.name = '';
                }
            } )
        }
    },
    mounted() {
        this.active = this.value;
    },
    watch: {
        active: function () {
            this.$emit('input', this.active);
        },
        value: function (){
            this.active = this.value;
        }
    }
}
</script>

<style scoped>

</style>
