<template>
    <div>

        <vs-select
            filter
            :multiple="multiselect"
            collapse-chips
            placeholder="Category"
            v-model="selected"
            :style="{'min-width': '100%'}"
        >
            <template v-for="item in listItem" >
                <vs-option :label="item.name" :value="item.id"  :style="{'margin-left': `${item.padding}em`}">
                    {{ item.name }}
                </vs-option>
            </template>

        </vs-select>
    </div>
</template>

<script>
export default {
    name: "CategoryCheckboxGroup",
    props: [
        'items',
        'value',
        'multiselect'
    ],
    data: () =>({
        selected: [],
        listItem: [],
    }),
    beforeMount() {
        this.listItem = this.getItems( this.items, 0, 0 );
    },
    mounted() {
        this.selected = this.value
    },
    methods: {
        getItems( items, padding, parent ) {
            let result = [];
            for(let i=0; i<items.length; i++) {
                let item = items[i];
                result.push({
                    'name': item.name,
                    'id': item.id,
                    'padding': padding,
                    'parent': parent,
                });
                if(item.children) {
                    result = result.concat( this.getItems(item.children, padding+1, item.id) );
                }
            }
            return result;
        },
    },
    watch: {
        value: function(newVal){
            console.log('value');
            this.selected = newVal;
        },
        selected: function(val) {
            console.log('selected');
            this.$emit('input', val);
        },
        items: function (){
            this.selected= "";
            this.listItem = this.getItems( this.items, 0, 0 );
        }
    }
}
</script>

<style scoped>

</style>
