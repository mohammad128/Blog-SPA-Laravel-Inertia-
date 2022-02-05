<template>
    <div class="flex flex-col gap-8">
        <vs-input :state="state" aria-required="true" :loading="form.processing" icon-after dark v-model="form.content" :placeholder="comment ? `Reply ${comment.user.name}...` : 'Add a comment'" block
                  @click-icon="sendComment" @keyup.enter="sendComment">
            <template #icon v-if="!form.processing">
                <i class='bx bxs-send' ></i>
            </template>
        </vs-input>
    </div>
</template>

<script>

export default {
    name: "ReplyComment",
    props: {
        comment: {
            type: Object,
            default: null
        },
        postId: {
            type: Number,
            default: 0
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                content: '',
            }),
            state: 'dark',
        }
    },
    methods: {
        sendComment() {
            if( ! this.form.content ) {
                this.state = 'danger';
                return;
            }
            let parent_id = this.comment ? this.comment.id : 0;
            this.form.transform(data=>({...data, parent_id: parent_id}))
                .post(route('Post.createComment', {post: this.postId}),{
                    preserveState: true,
                    preserveScroll: true,
                    onFinish: () => {
                        this.form.content = '';
                        this.$emit('commentSend');
                    }
                })
        }
    },
    watch: {
        form: {
            handler: function (){
                if( this.state === 'danger' ) {
                    if( this.form.content )
                        this.state = 'dark';
                }
            },
            deep: true
        }
    }
}
</script>

<style scoped>

</style>
