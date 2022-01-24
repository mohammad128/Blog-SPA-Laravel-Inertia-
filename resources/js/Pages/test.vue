<template>

    <DashboardLayout>

        <div class="flex flex-row justify-between gap-4">
            <div class="w-full">
                <div class="bg-white rounded-lg shadow-lg w-full">
                    <tree
                        :data="treeData"
                        :options="treeOptions"
                        @node:dragging:start="logDragStart"
                        @node:dragging:finish="logDragFinish"
                    >
                        <div class="flex justify-between items-center w-full" slot-scope="{ node }">
                            <div>
                                <i class="bx bx-trash"></i>
                                {{ node.text }}
                            </div>
                            <div class="flex flex-row">
                                <vs-button icon size="mini" circle @click.stop="editNode(node)">
                                    <i class="bx bx-edit"></i>
                                </vs-button>
                                <vs-button icon danger size="mini" circle>
                                    <i class="bx bx-trash"></i>
                                </vs-button>
                            </div>
                        </div>
                    </tree>

                </div>
            </div>
        </div>

        <json-viewer
            :expand-depth=5
            copyable
            boxed :value="treeData"></json-viewer>

    </DashboardLayout>

</template>
<script>
import JsonViewer from 'vue-json-viewer'
import LiquorTree from 'liquor-tree'

export default {
    components: {
        JsonViewer,
        tree: LiquorTree
    },
    props: {
        menus: Array,
        menu: Object
    },
    data() {
        return {
            treeOptions: {
                // propertyNames: {
                //     text: 'text',
                //     children: 'children',
                //     state: 'OPTIONS'
                // },
                expanded: true,
                dnd: true,
                checkbox: false
            },
            treeData: null
        }
    },
    methods: {
        editNode(node){
            console.log(node);
        },
        logDragStart(node) {
            console.log('Start dragging: ' + node.text)
        },

        logDragFinish(targetNode, distinationNode) {
            console.log(`Stop dragging: [TARGET]${targetNode.text}`, distinationNode.text)
        }
    },
    watch: {
    },
    created() {
        this.treeData = this.menu.items.map(item=>item);
        setTimeout(function (){

            this.treeData = [];
        },3000);
    },
    mounted() {
    }
}
</script>
<style >
</style>

