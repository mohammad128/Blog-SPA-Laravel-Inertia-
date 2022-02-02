<template>

    <div class="pt-20">
        <Nav/>
        <slot/>

        <Footer/>
    </div>

</template>

<script>
import Footer from "@/Partials/Footer/Footer";

export default {
    name: "Layout2",
    components: {
        Footer
    },
}
</script>

<style scoped>

</style>
