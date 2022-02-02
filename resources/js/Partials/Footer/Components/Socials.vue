<template>
    <div class="flex flex-row justify-center items-center" v-if="data.length">

        <div class="socials flex flex-row justify-center p-2 shadow-inner shadow-2xl px-6 bg-gradient-to-t from-white to-gray-200">
            <vs-button v-for="(item,index) in data" :key="index" icon circle :color="item.color" @click.stop="openInNewTab(item.value)">
                <i :class='item.icon'></i>
            </vs-button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Socials",
    props: ['data'],
    methods: {
        openInNewTab(url) {
            window.open(url, '_blank').focus();
        }
    }
}
</script>

<style scoped>
.socials{
    /*background: #e4e7eb;*/
}

</style>
