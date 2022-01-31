<template>


    <vsm-menu
        :menu="menu"
        :base-width="380"
        :base-height="400"
        :screen-offset="10"
        element="header"
        handler="hover"
        @open-dropdown="onOpenDropdown"
        @close-dropdown="onCloseDropdown"
    >
        <template #default="{ item }">
            <!--Dropdown Content-->
            <!--You can replace it with a separate component if each menu item has its own style-->
            <!--Dynamic Component Example: https://codepen.io/Alexeykhr/pen/YzPKxpX-->
            <div class="wrap-content">
                <div class="wrap-content__block">
                    Header: {{ item.title }}
                </div>
                <div class="wrap-content__item">
                    {{ item }}
                </div>
            </div>
        </template>
        <template #before-nav>
            <!--Image or svg of website logo-->
            <li class="vsm-section logo-section">
                <img
                    src="https://vuejs.org/images/logo.png"
                    alt="My Logo"
                >
            </li>
        </template>
        <template #title="data">
            <!--Display menu items through slots-->
            {{ data.item.title }}
        </template>
        <template #after-nav>
            <!--Mobile Burger, buttons, etc-->
            <!--For the same styles - add the vsm-section-->
            <li class="vsm-section vsm-mob-hide">
                <button>My Button</button>
            </li>
            <!--Display when user media screen below from $vsm-media (scss)-->
            <vsm-mob>Mobile Content</vsm-mob>
        </template>
    </vsm-menu>

</template>
<script>
export default {
    components: {
    },
    props: {
    },
    data() {
        return {
            menu: [
                {
                    title: 'News',
                    dropdown: 'news',
                    element: 'span',
                    attributes: {
                        class: ['my-class1', { 'my-class2': true }],
                        'data-big': 'yes'
                    },
                    listeners: {
                        mouseover: (evt) => {
                            console.log('news hover', evt)
                        }
                    },
                    new_section: false,
                },
                {
                    title: 'External Link',
                    attributes: {
                        href: 'https://github.com/Alexeykhr/vue-stripe-menu',
                        target: '_blank'
                    }
                }
            ]
        }
    },
    methods: {
        onOpenDropdown() {
            console.log('onOpenDropdown')
        },
        onCloseDropdown() {
            console.log('onCloseDropdown')
        }
    },
    watch: {
    },
    created() {

    },
    mounted() {
    }
}
</script>
<style lang="scss">
// Styles, to quickly start using the component
// You can delete, change or add your own

// Limit the width to 1024px and center

.vsm-menu {
    margin: 10px;
    ul {
        max-width: 1024px;
        margin: 0 auto;
    }
}

// Let's simplify the work with menu items (logo, menu, buttons, etc)
.vsm-root {
    display: flex;
    align-items: center;
    justify-content: center;
}


// Move all the content to the right and reduce the logo
.logo-section {
    flex: 1 1 auto;
    img {
        user-select: none;
        max-width: 40px;
    }
}

// All menu items (element props: a, button, span, etc) are
// made the same in style
.vsm-section_menu {
    > * {
        padding: 0 25px;
        font-weight: 500;
        font-family: inherit;
    }
}

// Styles for Dropdown Content:
.wrap-content {
    padding: 30px;
    // Set the width manually so that it does not depend
    // on changing content
    width: 400px;
}
.wrap-content__block {
    font-weight: bold;
}
.wrap-content__item {
    font-style: italic;
    font-size: .8rem;
}
.vsm-background {
    background: #ffffff82;
    backdrop-filter: blur(30px);
}
</style>

