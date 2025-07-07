<script setup>
import SelectOffice from "@/ClientTg/Components/V2/Admin/Cdek/SelectOffice.vue";
import SelectBoxSize from "@/ClientTg/Components/V2/Admin/Cdek/SelectBoxSize.vue";
</script>
<template>


    <h6>Офис получения</h6>
    <SelectOffice v-on:callback="selectOffice"/>

    <div class="alert alert-light my-2" v-if="calcTariffForm.tariff&&needDeliveryPrice">
        <p class="mb-0">Доставка от <span
            class="fw-bold text-primary">{{ calcTariffForm.tariff.period_min || 0 }}</span> до <span
            class="fw-bold text-primary">{{ calcTariffForm.tariff.period_max || 0 }}</span> рабочих дней</p>
        <p class="mb-0">Стоимость доставки <span
            class="fw-bold text-primary">{{ calcTariffForm.tariff.delivery_sum || 0 }}</span> руб.</p>

    </div>


</template>
<script>

import {mapGetters} from "vuex";

export default {
    props:["needDeliveryPrice"],
    data() {
        return {
            calcTariffForm: {
                tariff: null,
                to: {
                    region: null,
                    city: null,
                    office: null,
                },
            }
        }
    },
    watch: {
        'calcTariffForm.to': {
            handler: function (newValue) {
                this.calcTariffForm.tariff = null

                if (this.calcTariffForm.to.region != null &&
                    this.calcTariffForm.to.city != null &&
                    this.calcTariffForm.to.office != null
                )
                this.loadTariffForCdek()
            },
            deep: true
        }
    },
    computed: {
        canCalcTariff() {
            return this.calcTariffForm.packages.length > 0 &&
                (
                    this.calcTariffForm.to.region != null &&
                    this.calcTariffForm.to.city != null //&&
                    // this.calcTariffForm.to.office != null
                )
        }
    },
    mounted() {
        this.loadTariffForCdek()
    },
    methods: {
        selectOffice(event) {
            this.calcTariffForm.to.region = event.region
            this.calcTariffForm.to.city = event.city
            this.calcTariffForm.to.office = event.office

            this.calcTariffForm.tariff = null

            //this.loadCdekOffices(direction)
        },
        loadTariffForCdek() {
            this.$store.dispatch("calcCdekTariffFromCart", this.calcTariffForm).then(resp=>{
                this.calcTariffForm.tariff = resp.tariff
                this.$emit("calc",  this.calcTariffForm)
            })
        }

    },


}
</script>
