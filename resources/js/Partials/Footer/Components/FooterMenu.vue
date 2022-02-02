<template>
    <div class="w-full bg-white flex flex-row justify-around py-6">
        <div v-for="(item, index) in data" class="flex flex-col">
            <inertia-link v-if="item.href!=='' && item.href!=='#'" class="text-sm text-gray-700 font-bold" method="get" :href="item.href">
                <i v-if="item.icon" class='bx ' :class="item.icon"></i>
                {{ item.text }}
            </inertia-link>
            <span v-else class="text-sm text-gray-500 font-bold" >
                <i v-if="item.icon" class='bx ' :class="item.icon"></i>
                {{ item.text }}
            </span>
            <FooterMenuChildItem v-if="item.children && item.children.length" :data="item.children"></FooterMenuChildItem>
        </div>
    </div>
</template>

<script>
import FooterMenuChildItem from "@/Partials/Footer/Components/FooterMenuChildItem";
export default {
    name: "FooterMenu",
    props: ['data'],
    components: {
        FooterMenuChildItem
    }
}
</script>

<style scoped>

</style>
