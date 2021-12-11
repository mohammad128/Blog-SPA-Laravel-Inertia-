
<template>
    <div ref="loadings" class="con-loadings" v-show="show">
        <div
            v-for="(type, i) in types"
            :ref="`box${i}`"
            class="box-loading" />
    </div>
</template>
<script>
export default {
    data: () => ({
        color: '#000',
        hasOpenLoading: false,
        types: [
            // 'default',
            // 'waves',
            // 'corners',
            // 'border',
            // 'points',
            // 'square',
            // 'gradient',
            // 'rectangle',
            'circles',
            // 'square-rotate',
            // 'scale'
        ]
    }),
    props: {
        show: Boolean,
    },
    methods: {
        handleClickLoading(type) {
            const loading = this.$vs.loading({
                type,
                color: this.color
            })
            this.hasOpenLoading = true
            setTimeout(() => {
                loading.close()
                this.hasOpenLoading = false
            }, 3000)
        },
        openLoading(type, ref) {
            this.$vs.loading({
                target: this.$refs[ref][0],
                text: '',
                type,
                color: this.color
            })
        }
    },
    watch: {
    },
    mounted() {
        this.types.forEach((type, i) => {
            this.openLoading(type, `box${i}`)
        })
    }
}
</script>
<style scoped >
.con-input {
    border-radius: 10px;
    border: 4px solid var(--vs-theme-layout);
    box-shadow: 0px 4px 10px 0px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}
.con-input:hover {
    transform: translate(0, -4px);
    box-shadow: 0px 8px 10px 0px rgba(0,0,0,0.1);
}
.con-input input {
    opacity: 0;
    width: 30px;
    height: 30px;
    border: 0px;
    cursor: pointer;
}
.con-input i {
    position: absolute;
    color: var(--vs-theme-layout);
    pointer-events: none;
}
.con-loadings {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}
.center {
    flex-wrap: wrap;
    z-index: 200;
    position: relative;
    padding: 20px 10px;
}
.center.hasOpenLoading .box-loading {
    opacity: 0;
    transform: scale(0.7);
}
.box-loading {
    width: 120px;
    height: 120px;
    margin: 5px;
    border-radius: 20px;
    box-shadow: 0px 10px 20px -10px rgba(0,0,0,0.07);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.25s ease;
    top: 0;
    position: absolute;

}
/*.box-loading:hover {*/
/*    transform: translate(0, -5px);*/
/*    box-shadow: 0px 15px 20px -10px rgba(0,0,0,0.09);*/
/*}*/
.box-loading >>>.vs-loading {
    padding: 0px;
    background: transparent !important;
}
.box-loading >>>.vs-loading.vs-loading--gradient .vs-loading__load__animation__2,
.box-loading >>>.vs-loading.vs-loading--square .vs-loading__load__animation__2 {
    background: var(--vs-theme-layout) !important;
}

</style>

