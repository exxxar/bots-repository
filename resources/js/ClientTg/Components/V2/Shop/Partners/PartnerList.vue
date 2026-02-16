<script setup>

import PartnerCard from "@/ClientTg/Components/V2/Shop/Partners/PartnerCard.vue";
</script>
<template>

    <template v-if="(partnerList||[]).length>0">
        <div
            class="row my-3 row-cols-1 g-1">
            <div class="col" v-if="settings.partners?.display_self||false">
                <PartnerCard :partner="settings.partners"
                             v-on:select="selectPartner"
                             :key="'partner-0'"></PartnerCard>
            </div>
            <div class="col "

                 v-for="(partner, i) in partnerList">

                <template v-if="partner.is_active">
                    <PartnerCard :partner="partner"
                                 v-on:select="selectPartner"
                                 :key="'partner-'+i"></PartnerCard>
                </template>
            </div>
        </div>
    </template>
    <p class="alert alert-light my-3" v-else>
        У вас нет добавленных партнеров
    </p>


</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: 'PartnerList',
    data() {
        return {
            partnerList: null,
        };
    },
    computed: {
        ...mapGetters(['getPartners', 'getPartnersPaginateObject']),
        bot() {
            return window.currentBot || null
        },
        settings() {
            return this.bot.settings
        },

    },
    mounted() {

        this.partnerList = this.bot.partners
        this.loadPartners(0)

        console.log("parnters", this.partnerList)


    },
    methods: {
        loadPartners(pageIndex = 0) {
            this.loading = true
            this.$store.dispatch("loadPartners", {
                dataObject: {},
                page: pageIndex
            }).then(resp => {
                this.partnerList = this.getPartners || []
                this.partners_paginate_object = this.getPartnersPaginateObject || null
                this.loading = false;
            }).catch(err => {
                this.loading = false;
                console.error("LOAD PARTNERS ERROR:", err);
            })
        },
        selectPartner(partner) {
            this.$emit('select', partner)

        }
    },
};
</script>

<style lang="scss" scoped>
.carousel__item {
    padding: 5px;
}

</style>
