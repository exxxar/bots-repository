<template>


</template>

<script>
import Layout from "@/Layouts/ShopLayout.vue";

export default {
    name: "App",
    components: {
        "shop-layout": Layout,
    },
    data() {
        return {
            layout: "shop-layout",
        };
    },
};
</script>
