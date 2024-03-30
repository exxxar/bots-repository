<script setup>
import ChannelMailForm from "@/AdminPanel/Components/Constructor/Mail/ChannelMailForm.vue";
import PersonalMailForm from "@/AdminPanel/Components/Constructor/Mail/PersonalMailForm.vue";
import MailingTable from "@/AdminPanel/Components/Constructor/Mail/MailingTable.vue";
</script>

<template>
    <div class="py-2">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link"
                   v-bind:class="{'active':tab===0}"
                   @click="tab=0"
                   aria-current="page" href="#">Рассылка в канал</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   v-bind:class="{'active':tab===1}"
                   @click="tab=1"
                   href="#">Рассылка в бота</a>
            </li>
        </ul>

        <div class="row" v-if="tab===0">
            <div class="col-12 py-2">
                <ChannelMailForm></ChannelMailForm>
            </div>
        </div>

        <div class="row" v-if="tab===1">
            <div class="col-12 py-2">
                <PersonalMailForm v-on:callback="callbackPersonalMail"></PersonalMailForm>
            </div>
            <div class="col-12">
                <MailingTable :bot="bot" v-if="!loadMailingTable"></MailingTable>
            </div>
        </div>
    </div>

</template>
<script>

import {mapGetters} from "vuex";

export default {
    props:["bot"],
    data() {
        return {
            tab:1,
            load: false,
            loadMailingTable:false
        }
    },

    mounted() {

    },

    methods: {
        callbackPersonalMail(){
            this.loadMailingTable = true
            this.$nextTick(()=>{
                this.loadMailingTable = false
            })
        }

    }
}
</script>
