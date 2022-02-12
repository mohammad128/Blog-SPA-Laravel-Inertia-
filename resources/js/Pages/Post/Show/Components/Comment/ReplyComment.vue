<template>
    <div class="flex flex-row gap-2">
        <vs-input :state="state" aria-required="true" :loading="form.processing" icon-after dark v-model="form.content" :placeholder="comment ? `Reply ${comment.user.name}...` : 'Add a comment'" block
                  @click-icon="sendComment" @keyup.enter="sendComment"
                  @click.stop="activeEmojiBox=false"
        >
            <template #icon v-if="!form.processing">
                <i class='bx bxs-send' ></i>
            </template>
        </vs-input>
        <div class="selectEmoji relative">
            <vs-button
                circle
                icon
                floating
                active
                gradient
                :animation-type="!activeEmojiBox ? 'scale' : 'rotate'"
                dark
                transparent
                @click.stop="activeEmojiBox=!activeEmojiBox"
            >
                <i class='bx transform scale-125' :class="[activeEmojiBox ? 'bx-happy-heart-eyes scale-150' : 'bx-happy']" ></i>
                <template #animate >
                    <i class='bx bx-happy-heart-eyes transform scale-150' ></i>
                </template>
            </vs-button>
            <transition name="slide-fade">
                <div v-click-outside="clickOutSide" v-if="activeEmojiBox" class="selectEmojiContainer absolute right-0 z-10 rounded-2xl ring ring-2 ring-gray-300 ring-offset-8 shadow-2xl">
                    <VEmojiPicker @select="selectEmoji" />
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import { VEmojiPicker } from 'v-emoji-picker';

export default {
    name: "ReplyComment",
    components: {
        VEmojiPicker
    },
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
            activeEmojiBox: false,
        }
    },
    methods: {
        clickOutSide() {
            this.activeEmojiBox = false;
        },
        selectEmoji(emoji) {
            this.form.content += emoji.data;
        },
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
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
