<template>
    <div class="p-2 flex flex-col gap-4min-w-full">
        <div v-for="(item, index) in data" class="p-1">
            <span v-if="item.href && item.href!='#'" @click.stop="link($event)" method="get" :url="item.href"
                  class="flex flex-row items-center text-gray-600 hover:text-gray-900 text-ssm font-bold cursor-pointer duration-300 hover:translate-x-1">
<!--                <i v-if="item.children && item.children.length" class="bx text-gray-400 bxs-down-arrow text-xs pr-2"></i>&nbsp;-->
                <i v-if="item.icon" class="bx" :class="item.icon"></i>&nbsp;
                {{ item.title }}
            </span>
            <span v-else class="flex flex-row items-center text-gray-500 hover:text-gray-900 text-ssm font-bold cursor-pointer duration-300 hover:translate-x-1">
<!--                <i v-if="iitem.children && tem.children.length" class="bx text-gray-400 bxs-down-arrow text-xs pr-2"></i>&nbsp;-->
                <i v-if="item.icon" class="bx" :class="item.icon"></i>&nbsp;
                {{ item.title }}
            </span>
            <div class="pl-4" v-if="item.children && item.children.length">
                <VsmMenuContent :data="item.children"></VsmMenuContent>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "VsmMenuContent",
    props: {
        data: Array
    },
}
</script>

<style scoped>

</style>
