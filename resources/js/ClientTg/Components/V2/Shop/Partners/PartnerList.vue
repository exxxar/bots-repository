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
                                 v-on:change-fav="loadPartners"
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
        this.$nextTick(()=>{
            const fav =   window.self.config.fav_partners ?? []


            this.partnerList = [...this.bot.partners].sort((a, b) => {
                if (fav.length) {
                    const ai = fav.indexOf(a.id);
                    const bi = fav.indexOf(b.id);

                    // если оба есть в списке — сортируем по позиции
                    if (ai !== -1 && bi !== -1) return ai - bi;

                    // тот, кто есть в fav — идет выше
                    if (ai !== -1) return -1;
                    if (bi !== -1) return 1;

                    // оба не в fav → fallback
                }

                // fallback сортировка
                return b.order_position -a.order_position;
            });


            if (this.partnerList.length === 0)
                this.loadPartners(0)
        })

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
