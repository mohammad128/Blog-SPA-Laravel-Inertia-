<template>

    <input ref='tagifyInput' id="onlyMarvel" type="text" name="mcu" autocomplete placeholer="Tag`s" >

</template>
<script>
import Tagify from '@yaireo/tagify'
import '@yaireo/tagify/dist/tagify.css';

export default {
    components: {
    },
    props: {
        tags: {
            type: Array,
            default: () => []
        },
        value: Array
    },
    data() {
        return {
        }
    },
    methods: {
    },
    watch: {
    },
    mounted() {
        let that = this;
        var input = this.$refs.tagifyInput;// document.getElementById("onlyMarvel");
        var tagify = new Tagify(input, {
            whitelist: this.tags,
            enforceWhitelist: false
        });


        input.addEventListener('change', onChange)
        function onChange(e){
            that.$emit('input', tagify.value)
        }
        tagify.addTags(this.value);
        // tagify.on("add", function(e) {
        //     console.log(tagify.value);
        //     // console.log(e.detail.data);
        // });
    }
}
</script>
<style >
.tagify{
    background: #FFF;
    border-radius: 16px;
}

</style>

