<template>
    <div  class="pl-3 pt-2 gap-2 flex flex-col">
        <div v-for="(item, index) in data">
            <inertia-link v-if="item.href!=='' && item.href!=='#'"
                          class="text-sm text-gray-700 font-bold relative block transform hover:translate-x-2 duration-300 hover:text-gray-900"
                          method="get" :href="item.href">
                <i v-if="item.icon" class='bx ' :class="item.icon"></i>
                {{ item.text }}
            </inertia-link>
            <span v-else class="text-sm text-gray-500 font-bold relative block transform hover:translate-x-2 duration-300 hover:text-gray-900" >
                <i v-if="item.icon" class='bx ' :class="item.icon"></i>
                {{ item.text }}
            </span>
            <FooterMenuChildItem v-if="item.children && item.children.length" :data="item.children"></FooterMenuChildItem>
        </div>
    </div>
</template>

<script>
export default {
    name: "FooterMenuChildItem",
    props: ['data']
}
</script>

<style scoped>

</style>
