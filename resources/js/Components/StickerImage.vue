<template>

    <VueSticker :d="250" className="example" prefix="--" />

</template>

<script>
import VueSticker from 'vue-sticker'

export default {
    name: "StickerImage",
    components: {
        VueSticker
    },
    props: {
        imageUrl: String
    },
    mounted() {
        let image_url = this.$props.imageUrl;//'http://127.0.0.1:8000/uploads/images/foto6.png';
        var css = `
        .example--main-image {
            background-image: url('`+image_url+`');
          background-position: center; /* Center the image */
          background-repeat: no-repeat; /* Do not repeat the image */
          background-size: cover;
        }

        .example--backside-image {
            background-image: url('`+image_url+`');
          background-position: center; /* Center the image */
          background-repeat: no-repeat; /* Do not repeat the image */
          background-size: cover;
            -webkit-filter: grayscale(100%) ;
            filter: grayscale(100%) ;
        }
    `,
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        head.appendChild(style);

        style.type = 'text/css';
        if (style.styleSheet){
            // This is required for IE8 and below.
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }
    },
}
</script>

<style scoped>

</style>
