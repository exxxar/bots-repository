<script setup>
import MailToChannel from "@/ClientTg/Components/V2/Admin/Mail/MailToChannel.vue";
import MailToBot from "@/ClientTg/Components/V2/Admin/Mail/MailToBot.vue";
</script>
<template>

    <div class="container py-3" v-if="getSelf">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs justify-content-center catalog-tabs">
                    <li class="nav-item">
                        <a class="nav-link"
                           @click="tab=0"
                           style="font-weight:bold;"
                           v-bind:class="{'active':tab===0}"
                           aria-current="page" href="javascript:void(0)"><i class="fa-solid fa-robot mr-2"></i>В бота</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           @click="tab=1"
                           style="font-weight:bold;"
                           v-bind:class="{'active':tab===1}"
                           href="javascript:void(0)"><i class="fa-regular fa-message mr-2"></i>В канал</a>
                    </li>

                </ul>
            </div>

            <div class="col-12" v-if="tab===1">
                <MailToChannel></MailToChannel>

            </div>

            <div class="col-12" v-if="tab===0">
                <MailToBot></MailToBot>
            </div>
        </div>
    </div>



</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab:0,
            loading: false,
        }
    },
    computed: {
        ...mapGetters(['getSelf']),
    },

}
</script>
