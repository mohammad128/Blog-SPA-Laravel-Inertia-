<template>
    <DashboardLayout>
        <Head>
            <title>Edit Menu {{ menu.name }}</title>
            <meta name="description" head-key="description" :content="`Edit Menu `+menu.name">
        </Head>

        <div class="flex justify-between lg:items-center mb-4 mt-4 flex-col  gap-4 md:flex-row flex bg-white p-4 rounded-2xl">
            <div class="flex space-x-4 flex space-x-4">
                <div class="text-5xl font-extrabold">
                      <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-purple-800">
                        Edit {{ menu.name }}
                      </span>
                </div>
                <vs-button
                    circle
                    icon
                    floating
                    @click="activeCreateMenuDialog = true"
                >
                    <i class='bx bx-plus' ></i>
                </vs-button>
            </div>
        </div>

        <div class="flex flex-col justify-between gap-4">

            <div>
                <div class="bg-white p-4 rounded-lg shadow-lg flex flex-col ">
                    <div class="flex flex-row gap-6 overflow-hidden overflow-x-auto px-4">
                        <div v-for="(item, index) in menus" :key="index"  class="flex  cursor-pointer rounded-lg gap-3 justify-between items-center pl-2 "
                             :class="{'transform bg-gray-200 scale-110 border-t-gray-300 border-t-2 bg-white border-l-gray-300 border-r-gray-300 border-l-4 border-r-4 ': item.id==menu.id, 'border-l-white': item.id!=menu.id, }"
                             @click="link($event)" method="get" :url="route('dashboard.appearance.menu.edit', {'name': item.name})">
                            <span  class="text-lg text-gray-900" :class="{'font-bold': item.id==menu.id}">{{ item.name }}</span>
                            <div class="flex flex-row">
                                <vs-button circle size="mini" icon @click.stop="activeUpdateMenuDialog = true; editMenu=item" >
                                    <i class="bx bx-edit"></i>
                                </vs-button>
                                <vs-button circle size="mini" icon danger @click.stop="link($event)" method="delete" :url="route('dashboard.appearance.menu.delete', {'id': item.id})">
                                    <i class="bx bx-trash"></i>
                                </vs-button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 flex-1 flex flex-row divide-x divide-x-2 divide-gray-200 bg-gray-200  -mt-1 rounded-lg">
                        <div class="w-1/2 bg-white">
                            <h2 class="text-2xl text-center text-gray-700 py-4 font-bold">Add item</h2>
                            <collapse :selected="false">
                                <div slot="collapse-header">
                                    Custom Link
                                </div>
                                <div slot="collapse-body">
                                    <transition name="fade">
                                        <div class="flex flex-col gap-6 w-full">
                                            <vs-input label-placeholder="Text" v-model="customLinkForm.text" block :danger="errors['text'] ? true : false" :state="errors['text'] ? 'danger' : ''" >
                                                <template v-if="errors['text']" #message-danger>
                                                    {{ errors['text'] }}
                                                </template>
                                            </vs-input>
                                            <vs-input label-placeholder="Link" v-model="customLinkForm.href" block :danger="errors['href'] ? true : false" :state="errors['href'] ? 'danger' : ''" >
                                                <template v-if="errors['href']" #message-danger>
                                                    {{ errors['href'] }}
                                                </template>
                                            </vs-input>
                                            <vs-button block transparent dark @click="addCustomLink" :loading="customLinkForm.processing">
                                                Add Menu item
                                            </vs-button>
                                        </div>
                                    </transition>
                                </div>
                            </collapse>
                            <collapse :selected="false">
                                <div slot="collapse-header">
                                    Post
                                </div>
                                <div slot="collapse-body">
                                    <PostPicker :button-text="'Add to Menu'" @onOk="addPostsToMenu($event)"/>
                                </div>
                            </collapse>
                            <collapse :selected="false">
                                <div slot="collapse-header">
                                    Page
                                </div>
                                <div slot="collapse-body">
                                    <PagePicker :button-text="'Add to Menu'" @onOk="addPageToMenu($event)"/>
                                </div>
                            </collapse>
                            <collapse :selected="false">
                                <div slot="collapse-header">
                                    Categories
                                </div>
                                <div slot="collapse-body">
                                    <CategoryPicker :button-text="'Add to Menu'" @onOk="addCategoriesToMenu($event)"/>
                                </div>
                            </collapse>
                        </div>

                        <div class="w-1/2 pl-2" ref="treeContainer">
                            <h2 class="text-2xl text-center text-gray-700 py-4 font-bold">{{ menu.name }} items</h2>
                            <tree
                                ref="tree"
                                :data="treeData"
                                :options="treeOptions"
                                @node:dragging:start="logDragStart"
                                @node:dragging:finish="logDragFinish"
                            >
                                <div class="flex justify-between items-center w-full" slot-scope="{ node }">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-md text-gray-800">{{ node.text }}</span>
                                        <span class="pl-2 text-gray-600 text-xs">{{ node.data.href }}</span>
                                    </div>
                                    <div class="flex flex-row">
                                        <vs-button icon size="mini" circle @click.stop="editNode(node)">
                                            <i class="bx bx-edit"></i>
                                        </vs-button>
                                        <vs-button icon danger size="mini" circle
                                                   @click.stop="link($event)" method="delete" :url="route('dashboard.appearance.menuitem.delete',{'id': node.data.id})" >
                                            <i class="bx bx-trash"></i>
                                        </vs-button>
                                    </div>
                                </div>
                            </tree>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <CreateMenu v-model="activeCreateMenuDialog"/>
        <EditMenu v-model="activeUpdateMenuDialog" :menu="editMenu"/>
        <EditMenuItem v-model="activeEditMenuItem" :data="editMenuItemData"/>
    </DashboardLayout>
</template>

<script>
import LiquorTree from 'liquor-tree'
import Collapse from 'vue-collapse'
import CreateMenu from "@/Pages/Dashboard/Appearance/Menu/Components/CreateMenu";
import EditMenu from "@/Pages/Dashboard/Appearance/Menu/Components/EditMenu";
import EditMenuItem from "@/Pages/Dashboard/Appearance/Menu/Components/EditMenuItem";
import PostPicker from "@/Pages/Dashboard/Appearance/Menu/Components/PostPicker";
import PagePicker from "@/Pages/Dashboard/Appearance/Menu/Components/PagePicker";
import CategoryPicker from "@/Pages/Dashboard/Appearance/Menu/Components/CategoryPicker";

export default {
    name: "MenuIndex",
    components: {
        Collapse,
        CreateMenu,
        EditMenu,
        EditMenuItem,
        PostPicker,
        PagePicker,
        CategoryPicker,
        tree: LiquorTree
    },
    props: {
        menus: Array,
        menu: Object,
    },
    data() {
        return {
            activeCreateMenuDialog : false,
            activeEditMenuItem : false,
            editMenuItemData: null,

            activeUpdateMenuDialog: false,
            editMenu: {
                name: '',
                id: 0
            },

            treeOptions: {
                propertyNames: {
                    text: 'text',
                    id: 'id',
                    children: 'children',
                    state: 'OPTIONS'
                },
                expanded: true,
                dnd: true,
                checkbox: false
            },
            treeData: null,
            customLinkForm: this.$inertia.form({
                text: '',
                href: '',
            })
        }
    },
    computed: {
        errors() {
            return this.$page.props.errors;
        },
    },
    methods: {
        editNode(node){
            this.activeEditMenuItem = true;
            console.log(node.data);
            this.editMenuItemData = node.data;
        },
        addCustomLink() {
            this.customLinkForm.post( route('dashboard.appearance.menuitem.store', {'menu': this.menu.id}),{
                preserveState: false,
                preserveScroll: true,
            });
        },
        logDragStart(node) {
            // console.log('Start dragging: ' + node.text)
        },
        logDragFinish(targetNode, distinationNode) {
            let selection = this.$refs.tree;
            selection.loading = true;
            let mapId = function (obj) {
                return obj.map(function (item){
                    if(item.children && item.children.length) {
                        return { 'id': item.data.id, 'name': item.data.text, 'children': mapId(item.children) };
                    }
                    else
                        return { 'id': item.data.id, 'name': item.data.text };
                })
            }

            let newMenuItems = mapId( selection.tree.model );
            this.$inertia.post(route('dashboard.appearance.menuitem.rebuildMenuItems', {'menu': this.menu.id}), {
                'items': newMenuItems
            });

        },
        initData() {
            let covnertToTreeData = function (obj) {
                return obj.map(function (item){
                    if(item.children && item.children.length) {
                        return { 'text': item.text, 'data': item, 'children': covnertToTreeData(item.children) };
                    }
                    else
                        return { 'text': item.text, 'data': item, };
                })
            }

            this.treeData = covnertToTreeData(this.menu.items);
        },
        addPostsToMenu(selected) {
            let items = selected.map( function (item) {
                return {'text': item.title, 'href': item.url};
            });
            this.$inertia.post( route('dashboard.appearance.menuitems.store', {'menu': this.menu.id}), {'items': items},{
                preserveState: false,
                preserveScroll: true,
            });
        },
        addPageToMenu(selected) {
            let items = selected.map( function (item) {
                return {'text': item.title, 'href': item.url};
            });
            this.$inertia.post( route('dashboard.appearance.menuitems.store', {'menu': this.menu.id}), {'items': items},{
                preserveState: false,
                preserveScroll: true,
            });
        },
        addCategoriesToMenu(selected) {
            let items = selected.map( function (item) {
                return {'text': item.name, 'href': '/'};
            });
            this.$inertia.post( route('dashboard.appearance.menuitems.store', {'menu': this.menu.id}), {'items': items},{
                preserveState: false,
                preserveScroll: true,
            });
        }

    },
    beforeMount() {
        this.initData();
        this.$store.state.dashboard.activeSidebarItem = 'Appearance_Menu';
        setTimeout(()=>this.$refs.tree.expandAll(), 100);
    },
    watch: {
    }
}
</script>

<style>
.collapse .collapse-content-box {
    padding: 1em 0.5em;
}
.fade-enter-active, .fade-leave-active {
    transition: all .3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
    transform: translateY(-25%);
}
</style>
