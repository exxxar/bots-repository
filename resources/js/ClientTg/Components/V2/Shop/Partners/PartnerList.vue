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
            <div class="col " v-for="(partner, i) in partnerList">
                <PartnerCard :partner="partner"
                             v-on:select="selectPartner"
                             :key="'partner-'+i"></PartnerCard>
            </div>
        </div>
    </template>
    <p class="alert alert-light" v-else>
        У вас нет добавленных партнеров
    </p>


</template>

<script>

export default {
    name: 'PartnerList',
    data() {
        return {};
    },
    computed: {
        bot() {
            return window.currentBot || null
        },
        settings(){
          return this.bot.settings
        },
        partnerList() {
            return this.bot.partners || []
        }
    },
    mounted() {

    },
    methods: {
        selectPartner(partner){

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
