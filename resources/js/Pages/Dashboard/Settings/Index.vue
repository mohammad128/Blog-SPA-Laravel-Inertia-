<template>
    <DashboardLayout>
        <div class="flex flex-col gap-4">
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">App Name</span>
                <vs-input v-model="form.app_name" placeholder="App Name"
                          :danger="errors['app_name']!=null  ? true : false"
                          :state="errors['app_name'] ? 'danger' : ''">
                    <template v-if="errors['app_name']" #message-danger>
                        {{ errors['app_name'] }}
                    </template>
                </vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Site Address (URL)</span>
                <vs-input v-model="form.app_url" placeholder="Site Address (URL)"
                          :danger="errors['app_url']!=null  ? true : false"
                          :state="errors['app_url'] ? 'danger' : ''">
                    <template v-if="errors['app_url']" #message-danger>
                        {{ errors['app_url'] }}
                    </template>
                </vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Site Title:</span>
                <vs-input v-model="form.site_title" placeholder="Site Title"
                          :danger="errors['site_title']!=null  ? true : false"
                          :state="errors['site_title'] ? 'danger' : ''">
                    <template v-if="errors['site_title']" #message-danger>
                        {{ errors['site_title'] }}
                    </template>
                </vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Tagline:</span>
                <vs-input v-model="form.tag_line" placeholder="Tagline"
                          :danger="errors['tag_line']!=null  ? true : false"
                          :state="errors['tag_line'] ? 'danger' : ''">
                    <template v-if="errors['tag_line']" #message-danger>
                        {{ errors['tag_line'] }}
                    </template>
                </vs-input>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">New User Default Role</span>
                <vs-select
                    filter
                    multiple
                    v-model="form.new_user_default_role"
                    placeholder="Default User Role"
                >
                    <template v-for="item in _roles" >
                        <vs-option :label="item.name" :value="item.id">
                            {{ item.name }}
                        </vs-option>
                    </template>
                </vs-select>
            </div>
            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Menu Locations</span>
                <div class="flex flex-row gap-4 pt-4">
                    <vs-select
                        v-model="form.menu.header_menu"
                        label-placeholder="Header Menu"
                    >
                        <template v-for="item in menus" >
                            <vs-option :label="item.name" :value="item.id">
                                {{ item.name }}
                            </vs-option>
                        </template>
                    </vs-select>
                    <vs-select
                        v-model="form.menu.mobile_menu"
                        label-placeholder="Mobile Menu"
                    >
                        <template v-for="item in menus" >
                            <vs-option :label="item.name" :value="item.id">
                                {{ item.name }}
                            </vs-option>
                        </template>
                    </vs-select>
                    <vs-select
                        v-model="form.menu.footer_menu"
                        label-placeholder="Footer Menu"
                    >
                        <template v-for="item in menus" >
                            <vs-option :label="item.name" :value="item.id">
                                {{ item.name }}
                            </vs-option>
                        </template>
                    </vs-select>
                </div>
            </div>

            <div class="flex flex-row justify-start items-center gap-4 border border-l-2 border-l-amber-600 pl-4">
                <span class="md:basis-1/4 text-gray-900 font-bold text-lg">Social Link Address:</span>

                <collapse :selected="false" class="w-full bg-white">
                    <div slot="collapse-header">
                        All Socials
                    </div>
                    <div slot="collapse-body">
                        <transition name="fade">
                            <div class="gap-x-4 gap-y-8 w-full">
                                <div v-for="(item, index) in allSocials" :key="index" class="p-4 relative inline-block">
                                    <vs-input type="text"
                                              :color="item.color"
                                              state="dark"
                                              v-model="item.value"
                                              :label-placeholder="item.name">
                                        <template #icon>
                                            <i :class='item.icon'></i>
                                        </template>
                                    </vs-input>
                                </div>
                            </div>
                        </transition>
                    </div>
                </collapse>
            </div>
            <div></div>
            <div>
                <vs-button flat @click="submit" :loading="form.processing">
                    &nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;
                </vs-button>
            </div>
        </div>
    </DashboardLayout>
</template>

<script>
import Collapse from 'vue-collapse'
export default {
    name: "Settings",
    components: {
        Collapse,
    },
    props:{
        menus: Array,
        _roles: Array,
        APP_NAME: String,
        APP_URL: String,
        site_config: Object,
        socials: Array
    },
    data() {
        return {
            form: this.$inertia.form({
                app_name: '',
                app_url: '',
                new_user_default_role: '',
                site_title: '',
                tag_line: '',
                menu: {
                    header_menu: '',
                    mobile_menu: '',
                    footer_menu: '',
                }
            }),
            allSocials: [
                {value: '', name: 'facebook', color: 'facebook', icon:'bx bxl-facebook-square'},
                {value: '', name: 'twitter', color: 'twitter', icon:'bx bxl-twitter'},
                {value: '', name: 'youtube', color: 'youtube', icon:'bx bxl-youtube'},
                {value: '', name: 'linkedin', color: 'linkedin', icon:'bx bxl-linkedin'},
                {value: '', name: 'whatsapp', color: 'whatsapp', icon:'bx bxl-whatsapp'},
                {value: '', name: 'twitch', color: 'twitch', icon:'bx bxl-twitch'},
                {value: '', name: 'medium', color: 'medium', icon:'bx bxl-medium'},
                {value: '', name: 'skype', color: 'skype', icon:'bx bxl-skype'},
                {value: '', name: 'slack', color: 'slack', icon:'bx bxl-slack-old'},
                {value: '', name: 'messenger', color: 'messenger', icon:'bx bxl-messenger'},
                {value: '', name: 'tumblr', color: 'tumblr', icon:'bx bxl-tumblr'},
                {value: '', name: 'dribbble', color: 'dribbble', icon:'bx bxl-dribbble'},
                {value: '', name: 'google-plus', color: 'google-plus', icon:'bx bxl-google-plus'},
                {value: '', name: 'vimeo', color: 'vimeo', icon:'bx bxl-vimeo'},
                {value: '', name: 'pinterest', color: 'pinterest', icon:'bx bxl-pinterest'},
                {value: '', name: 'spotify', color: 'spotify', icon:'bx bxl-spotify'},
                {value: '', name: 'discord', color: 'discord', icon:'bx bxl-discord'},
                {value: '', name: 'amazon', color: 'amazon', icon:'bx bxl-amazon'},
                {value: '', name: 'reddit', color: 'reddit', icon:'bx bxl-reddit'},
            ]
        }
    },
    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ... data,
                    socials: this.allSocials
                    // new_user_default_role: this.form.new_user_default_role.join(',')
                }))
                .post(this.route('dashboard.settings.update'), {
                    preserveState: false,
                    preserveScroll: true,
                    onFinish: () => {
                    }
                })
            console.log(this.form);
        }
    },
    mounted() {
        for(let i=0;i<this.socials.length; i++) {
            for (let j=0; j<this.allSocials.length; j++) {
                if( this.socials[i].name == this.allSocials[j].name )
                    this.allSocials[j].value = this.socials[i].value;
            }
        }

        this.form.app_name = this.APP_NAME;
        this.form.app_url = this.APP_URL;
        this.form.tag_line = this.site_config.tag_line;
        this.form.site_title = this.site_config.site_title;
        this.form.new_user_default_role = this.site_config.new_user_default_role;//this.NEW_USER_DEFUALT_ROLE.split(',').map(item=>parseInt(item));
        this.form.menu = this.site_config.menu;
    },
}
</script>

<style scoped>

</style>
