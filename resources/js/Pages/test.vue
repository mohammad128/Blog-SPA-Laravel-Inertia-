<template>

    <DashboardLayout>

        <div class="flex flex-row justify-between gap-4">
            <div class="bg-white rounded-lg shadow-lg w-full">

                <DraggableTree ref="tree1" @change="tree1Change" :data="data" draggable="draggable" cross-tree="cross-tree" class="w-full">
                    <div slot-scope="{data, store}">
                        <template v-if="!data.isDragPlaceHolder">
                            <div class="flex flex-row justify-between">
                                <div class="flex flex-row items-center">
                                    <div>
                                        <vs-button size="mini" icon transparent dark v-if="data.children && data.children.length" @click="store.toggleOpen(data)">
                                            <i class="bx" :class="{'bx-minus': data.open, 'bx-plus': !data.open}"></i>
                                        </vs-button>
                                    </div>
                                    <span class="pl-2 font-bold text-gray-700 text-md">{{ data.text }}</span>
                                </div>
                                <vs-button icon transparent danger @click="addItem(data)">
                                    <i class="bx bx-trash"></i>
                                </vs-button>
                            </div>
                        </template>
                    </div>
                </DraggableTree>

            </div>

            <div class="bg-white rounded-lg shadow-lg w-full">
            </div>

        </div>
<!--        <json-viewer-->
<!--            :expand-depth=5-->
<!--            copyable-->
<!--            boxed :value="data"></json-viewer>-->

    </DashboardLayout>

</template>
<script>
import JsonViewer from 'vue-json-viewer'
import {DraggableTree, Tree} from 'vue-draggable-nested-tree'

export default {
    components: {
        JsonViewer,
        DraggableTree,
        Tree
    },
    data() {
        return {
            data: [ { "id": 1, "text": "dolor", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": null, "children": [ { "id": 2, "text": "quaerat", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 1, "children": [] }, { "id": 3, "text": "est", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 1, "children": [] }, { "id": 4, "text": "eveniet", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 1, "children": [] }, { "id": 5, "text": "recusandae", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 1, "children": [] } ] }, { "id": 6, "text": "explicabo", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": null, "children": [ { "id": 7, "text": "sapiente", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 6, "children": [] }, { "id": 8, "text": "accusamus", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 6, "children": [] }, { "id": 9, "text": "optio", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 6, "children": [] }, { "id": 10, "text": "at", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 6, "children": [] }, { "id": 11, "text": "possimus", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 6, "children": [] } ] }, { "id": 12, "text": "deserunt", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": null, "children": [ { "id": 13, "text": "voluptatum", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 12, "children": [] }, { "id": 14, "text": "reprehenderit", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": 12, "children": [] } ] }, { "id": 15, "text": "placeat", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": null, "children": [] }, { "id": 17, "text": "nihil", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": null, "children": [] }, { "id": 19, "text": "dignissimos", "href": "http://127.0.0.1:8000", "menu_id": 1, "parent_id": null, "children": [] } ]
        }
    },
    computed: {
    },
    methods: {
        tree1Change(node, targetTree) {
            // this.data = targetTree.getPureData()
            // console.log(targetTree.getPureData());
        },
        addItem(item) {
            console.log(item);
        }
    },
    watch: {
        data: function (val) {
            console.log( this.$refs.tree1.getPureData() );
        }
    },
    mounted() {


    }
}
</script>
<style >
.fr-floating-btn {
    padding-top: 1px;
}

.he-tree {
    border: 1px solid #ccc;
    padding: 20px;
}

.tree-node-inner {
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 1em;
    cursor: pointer;
}

.draggable-placeholder-inner {
    border: 1px dashed #0088F8;
    box-sizing: border-box;
    background: rgba(0, 136, 249, 0.09);
    color: #0088f9;
    text-align: center;
    padding: 0;
    display: flex;
    align-items: center;
}

.tree3 .tree-node-inner {
    border: none;
    padding: 0px;
}
.tree3 .tree-node-inner-back:hover {
    background: #ddd;
}

.tree4 .tree-node-inner {
    border: none;
    border-bottom: 1px solid #ccc;
    padding: 5px 10px;
    backgroud: #ccc;
}
.tree4 .draggable-placeholder-inner {
    background-color: orangered;
}
</style>

