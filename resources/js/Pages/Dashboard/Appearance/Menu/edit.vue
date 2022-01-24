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
                    @click="link($event)"
                    method="get"
                    :url="route('dashboard.post.create')"
                >
                    <i class='bx bx-plus' ></i>
                </vs-button>
            </div>
        </div>

        <div class="flex flex-row justify-between gap-4">

            <div class="bg-white p-4 rounded-lg shadow-lg flex flex-col gap-4">


                <div v-for="(item, index) in menus" :key="index" class="flex border-l border-l-amber-800 border-l-2 gap-3 justify-between items-center pl-2 ">
                    <span class="text-lg text-gray-900">{{ item.name }}</span>
                    <span class="text-xs text-gray-500">{{ item.created_at.substring(0,10) }} ({{ item.diff_for_human }})</span>
                    <div>
                        <vs-button icon @click="link($event)" method="get" :url="route('dashboard.appearance.menu.edit', {'name': item.name})">
                            <i class="bx bx-edit"></i>
                        </vs-button>
                        <vs-button icon danger @click="link($event)" method="delete" :url="route('dashboard.appearance.menu.delete', {'id': item.id})">
                            <i class="bx bx-trash"></i>
                        </vs-button>
                    </div>
                </div>

            </div>

            <div class="bg-white p-4 rounded-lg shadow-lg flex-1 flex flex-row">
                <div class="w-1/2">
                    <collapse :selected="true">
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
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, neque, quae? Aliquid atque consequatur exercitationem ipsa odio! Cumque ducimus, eum fugit magnam minus quas quidem repudiandae tempora temporibus veniam! Labore.
                        </div>
                    </collapse>
                    <collapse :selected="false">
                        <div slot="collapse-header">
                            Page
                        </div>
                        <div slot="collapse-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, neque, quae? Aliquid atque consequatur exercitationem ipsa odio! Cumque ducimus, eum fugit magnam minus quas quidem repudiandae tempora temporibus veniam! Labore.
                        </div>
                    </collapse>
                    <collapse :selected="false">
                        <div slot="collapse-header">
                            Categories
                        </div>
                        <div slot="collapse-body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, neque, quae? Aliquid atque consequatur exercitationem ipsa odio! Cumque ducimus, eum fugit magnam minus quas quidem repudiandae tempora temporibus veniam! Labore.
                        </div>
                    </collapse>
                </div>

                <div class="w-1/2" ref="treeContainer">
                    <tree
                        ref="tree"
                        :data="treeData"
                        :options="treeOptions"
                        @node:dragging:start="logDragStart"
                        @node:dragging:finish="logDragFinish"
                    >
                        <div class="flex justify-between items-center w-full" slot-scope="{ node }">
                            <div>
                                <i class="bx bx-trash"></i>
                                {{ node.text }} {{node.id}}
                            </div>
                            <div class="flex flex-row">
                                <vs-button icon size="mini" circle @click.stop="editNode(node)">
                                    <i class="bx bx-edit"></i>
                                </vs-button>
                                <vs-button icon danger size="mini" circle
                                    @click.stop="link($event)" method="delete" :url="route('dashboard.appearance.menuitem.delete',{'id': node.id})" >
                                    <i class="bx bx-trash"></i>
                                </vs-button>
                            </div>
                        </div>
                    </tree>
                </div>
            </div>

        </div>
    </DashboardLayout>
</template>

<script>
import LiquorTree from 'liquor-tree'
import Collapse from 'vue-collapse'

export default {
    name: "MenuIndex",
    components: {
        Collapse,
        tree: LiquorTree
    },
    props: {
        menus: Array,
        menu: Object,
    },
    data() {
        return {
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
            flatData: null,
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
            console.log(node);
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
            let id = targetNode.id;
            let parent_id = targetNode.parent ? targetNode.parent.id : -1;

            let selection = this.$refs.tree;
            selection.loading = true;
            console.log( selection);
            // console.log(selection.toJSON() );
            console.log( selection.tree.model );

            let findId = function (name) {
                for(const[key, value] of Object.entries(this.menu.flatItems)) {
                    console.log(key, value)
                }
            }

            let mapId = function (obj) {
                return obj.map(function (item){
                    if(item.children && item.children.length) {
                        console.warn(item);
                        return { 'id': item.id, 'name': item.data.text, 'children': mapId(item.children) };
                    }
                    else
                        return { 'id': item.id, 'name': item.data.text };
                })
            }

            let newMenuItems = mapId( selection.tree.model );
            // this.$inertia.post(route('dashboard.appearance.menuitem.rebuildMenuItems', {'menu': this.menu.id}), {
            //     'items': newMenuItems
            // });

            console.log(newMenuItems);
            // this.$inertia.post( route('dashboard.appearance.menuitem.moveNode', {'id':id,'parent_id': parent_id}) );
        }
    },
    beforeMount() {
        this.treeData = this.menu.items.map(item=>item);
        this.$store.state.dashboard.activeSidebarItem = 'Appearance_Menu';
        setTimeout(()=>this.$refs.tree.expandAll(), 100);


        let findId = function (name, that) {
            for(const[key, value] of Object.entries(that.menu.flatItems)) {
                console.log(key, value)
            }
        }
        findId('aa', this);
    },
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
    transition: all .3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
    transform: translateY(-25%);
}
</style>
