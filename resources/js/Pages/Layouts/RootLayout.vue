<template>
    <div>
        <Head>
            <title>{{siteTitle}}</title>
            <meta name="description" head-key="description" :content="tagLine">
        </Head>

        <slot/>

        <div
            class="fixed bottom-2.5 right-2.5"
        >
            <vs-button
                icon
                color="danger"
                border
                @click="showPlayer = !showPlayer"
            >
                <i class='bx bxs-music' ></i>
            </vs-button>
        </div>
        <div  id="PlayerPanel" ref="PlayerPanel" v-bind:class="{ 'hidePlayer': !showPlayer }" class="  vs-notification-parent vs-notification-parent--bottom-right "><div class="vs-notification vs-notification--width-auto vs-notification--undefined notification-enter-to" style="max-height: 240px; padding: 20px;"><div class="vs-notification__content p-2">
<!--            <iframe width="100%" height="300" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/62702149&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe><div style="font-size: 10px; color: #cccccc;line-break: anywhere;word-break: normal;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; font-family: Interstate,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Garuda,Verdana,Tahoma,sans-serif;font-weight: 100;"><a href="https://soundcloud.com/jaleh-2" title="Jaleh" target="_blank" style="color: #cccccc; text-decoration: none;">Jaleh</a> · <a href="https://soundcloud.com/jaleh-2/shadmehr-aghili-paeez" title="Shadmehr Aghili - Paeez" target="_blank" style="color: #cccccc; text-decoration: none;">Shadmehr Aghili - Paeez</a></div>-->
            <iframe data-v-557708a0="" height="200px" width="100%" frameborder="no" scrolling="no" seamless="seamless" src="https://player.simplecast.com/8510f7e5-d7b2-4c5b-87de-5193a8b928c3?dark=false"></iframe>
        </div><button class="vs-notification__close"><i class="vs-icon-close vs-icon-hover-less" @click="showPlayer = !showPlayer"></i></button><div class="vs-notification__progress"></div></div></div>
    </div>
</template>

<script>
export default {
    name: "Layout",
    props: {
        flash_message: Array
    },
    data() {
        return {
            showPlayer: false,
        }
    },
    methods: {
        showFlashMessages(messages){
            console.log(messages);
            if( messages && Array.isArray(messages) )
            for(let i=0; i<messages.length; i++) {
                let message = messages[i];
                let title = message['title'];
                let msg = message['msg'];
                let color = message['color'];
                let icon = `<i class="bx ${message['icon']}"></i>`;
                let time_out = message['time_out'];
                let position = message['position'];
                let type = message['type'];
                let message_time = message['message_time'];
                const noti = this.$vs.notification({
                    icon,
                    color,
                    position,
                    time_out,
                    sticky: true,
                    progress: 'auto',
                    title: title,
                    text: msg
                })
            }
        }
    },
    mounted() {
        this.showFlashMessages(this.$page.props.flash_message);
    },
    watch:{
        flash_message: function () {
            this.showFlashMessages(this.$page.props.flash_message);
        }
    }
}
</script>

<style scoped>
#PlayerPanel {
    transition: all 1s;
}
.hidePlayer {
    height: 0 !important;
    width: 0 !important;
    right: -100px !important;
    bottom: -100px !important;
    opacity: 0 !important;
}
</style>
