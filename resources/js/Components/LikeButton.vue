<template>
    <div :class="buttonType">
        <div class="buttonType">
            <a href="javascript:void(0)" class="like-button"  :class="{'active': active}" ref="btn" @click="handleClick">
                <svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M320 1344q0-26-19-45t-45-19q-27 0-45.5 19t-18.5 45q0 27 18.5 45.5t45.5 18.5q26 0 45-18.5t19-45.5zm160-512v640q0 26-19 45t-45 19h-288q-26 0-45-19t-19-45v-640q0-26 19-45t45-19h288q26 0 45 19t19 45zm1184 0q0 86-55 149 15 44 15 76 3 76-43 137 17 56 0 117-15 57-54 94 9 112-49 181-64 76-197 78h-129q-66 0-144-15.5t-121.5-29-120.5-39.5q-123-43-158-44-26-1-45-19.5t-19-44.5v-641q0-25 18-43.5t43-20.5q24-2 76-59t101-121q68-87 101-120 18-18 31-48t17.5-48.5 13.5-60.5q7-39 12.5-61t19.5-52 34-50q19-19 45-19 46 0 82.5 10.5t60 26 40 40.5 24 45 12 50 5 45 .5 39q0 38-9.5 76t-19 60-27.5 56q-3 6-10 18t-11 22-8 24h277q78 0 135 57t57 135z"/></svg>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "LikeButton",
    props: {
        buttonType: {
            type: String,
            default: 'like'//like Or dislike
        },
        active: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        handleClick() {
            this.$refs.btn.classList.toggle("active");
            this.$refs.btn.classList.add("animated");
            this.generateClones(this.$refs.btn);
            this.$emit('handleClick', this.$refs.btn.classList.contains('active'));
        },

        generateClones(button) {
            let clones = this.randomInt(3, 6);
            let that = this;
            for (let it = 1; it <= clones; it++) {
                let clone = button.querySelector("svg").cloneNode(true),
                    size = this.randomInt(5, 16);
                button.appendChild(clone);
                clone.setAttribute("width", size);
                clone.setAttribute("height", size);
                clone.style.position = "absolute";
                clone.style.transition =
                    "transform 0.5s cubic-bezier(0.12, 0.74, 0.58, 0.99) 0.3s, opacity 1s ease-out .5s";
                let animTimeout = setTimeout(function() {
                    clearTimeout(animTimeout);
                    clone.style.transform =
                        "translate3d(" +
                        (that.plusOrMinus() * that.randomInt(10, 25)) +
                        "px," +
                        (that.plusOrMinus() * that.randomInt(10, 25)) +
                        "px,0)";
                    clone.style.opacity = 0;
                }, 1);
                let removeNodeTimeout = setTimeout(function() {
                    clone.parentNode.removeChild(clone);
                    clearTimeout(removeNodeTimeout);
                }, 900);
                let removeClassTimeout = setTimeout( function() {
                    button.classList.remove("animated")
                }, 600);
            }
        },
        plusOrMinus() {
            return Math.random() < 0.5 ? -1 : 1;
        },
        randomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1) + min);
        }
    }
}
</script>

<style scoped>
.dislike {
    transform: rotatex(180deg);
}
.like-button {
    display: flex;
    align-items: center;
    justify-content: center;
}
.like-button.animated {
    -webkit-animation: pop 0.9s both;
    animation: pop 0.9s both;
}
.like-button svg {
    opacity: 1;
}
.like-button svg path {
    fill: #333;
    transition: fill .4s ease-out;
}
.like-button.active svg path {
    fill: #2196f3;
}

@-webkit-keyframes pop {
    0% {
        -webkit-transform: scale3d(1, 1, 1);
        transform: scale3d(1, 1, 1);
    }
    30% {
        -webkit-transform: scale3d(1.25, 0.75, 1);
        transform: scale3d(1.25, 0.75, 1);
    }
    40% {
        -webkit-transform: scale3d(0.75, 1.25, 1);
        transform: scale3d(0.75, 1.25, 1);
    }
    50% {
        -webkit-transform: scale3d(1.15, 0.85, 1);
        transform: scale3d(1.15, 0.85, 1);
    }
    65% {
        -webkit-transform: scale3d(0.95, 1.05, 1);
        transform: scale3d(0.95, 1.05, 1);
    }
    75% {
        -webkit-transform: scale3d(1.05, 0.95, 1);
        transform: scale3d(1.05, 0.95, 1);
    }
    100% {
        -webkit-transform: scale3d(1, 1, 1);
        transform: scale3d(1, 1, 1);
    }
}

@keyframes pop {
    0% {
        -webkit-transform: scale3d(1, 1, 1);
        transform: scale3d(1, 1, 1);
    }
    30% {
        -webkit-transform: scale3d(1.25, 0.75, 1);
        transform: scale3d(1.25, 0.75, 1);
    }
    40% {
        -webkit-transform: scale3d(0.75, 1.25, 1);
        transform: scale3d(0.75, 1.25, 1);
    }
    50% {
        -webkit-transform: scale3d(1.15, 0.85, 1);
        transform: scale3d(1.15, 0.85, 1);
    }
    65% {
        -webkit-transform: scale3d(0.95, 1.05, 1);
        transform: scale3d(0.95, 1.05, 1);
    }
    75% {
        -webkit-transform: scale3d(1.05, 0.95, 1);
        transform: scale3d(1.05, 0.95, 1);
    }
    100% {
        -webkit-transform: scale3d(1, 1, 1);
        transform: scale3d(1, 1, 1);
    }
}
</style>
