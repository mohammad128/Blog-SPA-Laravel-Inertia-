<template>
    <DashboardLayout  v-slot="scope">

        <Head>
            <title>Dashboard</title>
            <meta name="description" head-key="description" content="Dashboard Page">
        </Head>

        <div class="prose prose-stone prose-h1:text-gray-500 prose-p:text-gray-900 prose-invert max-w-none">
            <div v-for="i in 10" :key="i">
                <h1 class="text-2xl">This is Title {{i}}</h1>
                <p>aabbcc</p>
                <p>
                    Fusce id magna id erat tincidunt volutpat. Aliquam velit turpis, semper at hendrerit sed, aliquet eu ante. Morbi nec nulla sollicitudin, porttitor nulla eu, auctor enim. Proin volutpat, quam non placerat consectetur, lorem urna venenatis nulla, vehicula lobortis tortor odio in risus. Donec quis augue dolor. Cras sagittis feugiat mauris sed gravida. Nulla euismod odio sem, vel rutrum neque pharetra ac. Integer sagittis ex eros, eu molestie magna vestibulum tristique. Donec tempor, lectus vitae posuere feugiat, velit leo tristique massa, ac feugiat erat erat vitae purus. Nunc vel scelerisque massa. In hendrerit, urna sed faucibus gravida, libero arcu pulvinar sapien, sed placerat lectus metus vel nisl. Fusce aliquam turpis imperdiet, vulputate augue ut, auctor dolor. Duis tincidunt dui nibh, sit amet suscipit odio imperdiet in.
                </p>
            </div>
        </div>
    </DashboardLayout>


<!--    <app-layout>-->
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->

<!--        <div class="py-12">-->
<!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </app-layout>-->
</template>


<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import DashboardLayout from '@/Pages/Layouts/DashboardLayout'

    export default {
        components: {
            AppLayout,
            Welcome,
        },
        data:() => ({
            activeTooltip1: false,
        }),
        methods: {
            toId(str) {
                return str.replace(/\s+/g, '_');
            }
        },
        beforeMount() {
            this.$store.state.dashboard.activeSidebarItem = 'Dashboard';
        }
    }
</script>
