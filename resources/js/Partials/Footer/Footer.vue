<template>

    <footer class="flex flex-col bg-white px-4 py-8 mt-16 gap-4">
        <Socials :data="$page.props.socials"/>
        <FooterMenu :data="$page.props.site_menus.footer_menu"/>
        <Copyright/>
    </footer>
</template>

<script>
import Socials from "@/Partials/Footer/Components/Socials";
import FooterMenu from "@/Partials/Footer/Components/FooterMenu";
import Copyright from "@/Partials/Footer/Components/Copyright";

export default {
    name: "Footer",
    components: {
        Socials,
        FooterMenu,
        Copyright
    },
    data() {
        return {
        }
    }
}
</script>

<style scoped>

</style>
