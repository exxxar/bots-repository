<script setup>
import FrontPadForm from "@/ClientTg/Components/V2/Admin/FrontPad/FrontPadForm.vue";


</script>
<template>

    <div class="container my-3">
        <div class="row">
            <div class="col-12">
                <FrontPadForm/>
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

    mounted() {


    },
    methods: {
       /* loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

                this.amo = this.bot.amo


            })
        },*/
    }
}
</script>

