<script setup>

import StoryAdmin from "@/ClientTg/Components/V2/Admin/Stories/StoryAdmin.vue";
</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <StoryAdmin></StoryAdmin>
            </div>

        </div>
    </div>

</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {

        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        },
        tg() {
            return window.Telegram.WebApp;
        },

    },

    mounted() {



    },


    methods: {



    }
}
</script>
