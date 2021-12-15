<template>

    <vs-navbar color="#101827"  fixed text-white square center-collapsed v-model="active">
        <template #left>
            <vs-button color="#fff" @click="link($event)"
                       :url="route('index')"
                       method="get" flat circle  >
                <i class='bx bx-home-alt'></i>
            </vs-button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <vs-navbar-group>
                <i class='bx bx-add-to-queue'></i> New
                <template #items>
                    <vs-navbar-item :active="active == 'Post'" id="Post">
                        Post
                    </vs-navbar-item>
                    <vs-navbar-item :active="active == 'Page'" id="Page">
                        Page
                    </vs-navbar-item>
                    <vs-navbar-item :active="active == 'Video'" id="Video">
                        Video
                    </vs-navbar-item>
                    <vs-navbar-item :active="active == 'Media'" id="Media">
                        Media
                    </vs-navbar-item>
                </template>
            </vs-navbar-group>
        </template>

        <template #right>
            <vs-tooltip shadow interactivity left bottom>
                <vs-avatar circle size="30">
                    <img :src="$page.props.user.profile_photo_url" alt="">
                </vs-avatar>
                <template #tooltip>
                    <div class="content-tooltip">
                        <div class="body">
                            <div class="text">
                                Cosed Tasks
                                <span>
                                89
                                </span>
                            </div>
                            <vs-avatar circle size="80" @click="activeTooltip1=!activeTooltip1">
                                <img :src="$page.props.user.profile_photo_url" alt="">
                            </vs-avatar>
                            <div class="text">
                                Open Tasks
                                <span>
                                8
                                </span>
                            </div>
                        </div>
                        <footer>
                            <vs-button circle icon border>
                                <i class='bx bxs-share-alt'></i>
                            </vs-button>
                            <vs-button circle>
                                Message
                            </vs-button>
                            <vs-button circle icon border>
                                <i class='bx bx-like' ></i>
                            </vs-button>
                        </footer>
                    </div>
                </template>
            </vs-tooltip>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <vs-button
                danger
                color="#FFF"
                transparent
                animation-type="scale"
                @click="link($event)"
               :url="route('logout')"
               method="post" >
                Logout

                <template #animate >
                    <i class='bx bx-log-out' ></i>
                </template>
            </vs-button>
        </template>
    </vs-navbar>

</template>
<style scoped>
.vs-tooltip-content{

    width: auto;
    display: inline-block;
    position: relative;

}
.content-tooltip .body {
    display: flex;
    align-items: flex-start;
    justify-content: center;
}
.content-tooltip .body .vs-avatar-content {
    margin-top: -30px;
    border: 3px solid var(--vs-theme-layout);
    box-shadow: 0px 4px 15px 0px rgba(0,0,0,0.1);
}
.content-tooltip .body .text {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    font-size: 0.55rem;
    padding: 10px;
    font-weight: normal;
}
.content-tooltip .body .text span {
    font-weight: bold;
    font-size: 0.7rem;
}
.content-tooltip footer {
    display: flex;
    align-items: center;
    justify-content: center;
}
.content-tooltip h4 {
    padding: 8px;
    margin: 0px;
    text-align: left;
}
.content-tooltip p {
    text-align: left;
    padding: 0px;
    margin: 0px;
    line-height: 1rem;
    padding-bottom: 5px;
    padding-left: 8px;
}
.content-tooltip{
    color: #FFF;
    box-shadow: 0 32px 34px #10182747;
    border: 1px solid #0d2756;
    background: #101827;
    border-radius: 15px;
    padding-bottom: 8px;
    margin-top: -13px;
}
</style>

<script>
export default {
    name: "DashboardNav",
    data:() => ({
        active: 'guide'
    })
}
</script>

<style scoped>

</style>
