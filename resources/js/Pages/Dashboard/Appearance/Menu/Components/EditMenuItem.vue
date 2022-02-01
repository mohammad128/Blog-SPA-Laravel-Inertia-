<template>

    <vs-dialog v-model="active" blur>
        <template #header>
            <h4 class="not-margin">
                <b>Edit item</b>
            </h4>
        </template>


        <div class="con-form gap-8 flex flex-col">
            <vs-input block type="name" :danger="errors['text'] ? true : false" :state="errors['text'] ? 'danger' : ''"  v-model="form.text" label-placeholder="Text">
                <template v-if="errors['text']" #message-danger>
                    {{ errors['text'] }}
                </template>
            </vs-input>
            <vs-input block type="url" :danger="errors['href'] ? true : false" :state="errors['href'] ? 'danger' : ''"  v-model="form.href" label-placeholder="Link">
                <template v-if="errors['href']" #message-danger>
                    {{ errors['href'] }}
                </template>
            </vs-input>
            <div class="flex flex-row">
                <vs-input v-model="icon" label-placeholder="Icon" block/>
                <IconPicker v-model="icon"/>
            </div>
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

<script>import IconPicker from "@/Pages/Dashboard/Appearance/Menu/Components/IconPicker";

export default {
    name: "CreateMenu",
    props: ['value', 'data'],
    components: {
        IconPicker
    },
    data() {
        return {
            active: false,
            icon: '',
            form: this.$inertia.form({
                text: '',
                icon: '',
                href: ''
            })
        }
    },
    methods: {
        submit() {
            this.form.icon = this.icon;
            this.form.put( route('dashboard.appearance.menuitem.update', {'menuItem': this.data.id}),{
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    this.active = false;
                    this.form.text = '';
                    this.form.icon = '';
                    this.form.href = '';
                }
            } )
        }
    },
    mounted() {
        this.active = this.value;
        if( this.data ) {
            this.form.text = this.data.text;
            this.form.href = this.data.href;
            this.icon = this.data.icon;
        }
    },
    watch: {
        active: function () {
            this.$emit('input', this.active);
        },
        value: function (){
            this.active = this.value;
        },
        data: function (){
            this.form.text = this.data.text;
            this.form.href = this.data.href;
            this.icon = this.data.icon;
        }
    }
}
</script>

<style scoped>

</style>
