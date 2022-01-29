<template>

    <vs-dialog v-model="active" blur>
        <template #header>
            <h4 class="not-margin">
                <b>Update Menu {{menu.name}}</b>
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
                    Update
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
    name: "EditMenu",
    props: {
        'value': {
            type: Boolean,
            default: false
        },
        menu: {
            type: Object,
            default: {
                name: '',
                id: 0
            }
        }
    },
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
            this.form.put( route('dashboard.appearance.menu.update', {'menu':this.menu.id}),{
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
        },
        menu: function () {
            this.form.name = this.menu.name;
        }
    }
}
</script>

<style scoped>

</style>
