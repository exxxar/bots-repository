<script setup>
import PromoCodesForm from "@/AdminPanel/Components/Constructor/PromoCodes/PromoCodesForm.vue";
import PromoCodesTable from "@/AdminPanel/Components/Constructor/PromoCodes/PromoCodesTable.vue";
</script>
<template>


    <div v-if="part===0" class="py-2 container">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="createPromoCode"
                        class="btn btn-primary">
                    Создать новый промокод
                </button>
            </div>
        </div>

        <PromoCodesTable
            v-if="!loadTable"
            v-on:create="createPromoCode"
            v-on:select="selectPromoCode"
            :bot="bot"></PromoCodesTable>
    </div>

    <div v-if="part===2" class="py-2 container">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="part=0"
                        class="btn btn-outline-primary">
                    Назад
                </button>
            </div>
        </div>

        <PromoCodesForm
            v-if="!loadForm"
            v-on:callback="callbackForm"
            :bot="bot"/>
    </div>

    <div v-if="part===1" class="py-2 container">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="part=0"
                        class="btn btn-outline-primary">
                    Назад
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item" @click="tab=0">
                <a class="nav-link"
                   v-bind:class="{'active':tab===0}"
                   aria-current="page"
                   href="javascript:void(0)">Информация о Промокоде</a>
            </li>

            <li class="nav-item" @click="tab=1">
                <a class="nav-link"
                   v-bind:class="{'active':tab===1}"
                   href="javascript:void(0)">Статистика использования</a>
            </li>

        </ul>

        <div class="row py-3" v-if="tab===0">
            <div class="col-12">
                <PromoCodesForm
                    v-if="!loadForm"
                    v-on:callback="callbackForm"
                    :code="selectedPromoCode"
                    :bot="bot"/>
            </div>
        </div>

        <div class="row py-3" v-if="tab===1">
            <div class="col-12 py-3">
                <h4>Статистика</h4>

            </div>

        </div>



    </div>

</template>
<script>
export default {
    props: ["bot"],
    data() {
        return {
            part: 0,
            tab: 0,

            selectedPromoCode:null,
            loadForm:false,
            loadTable:false,
        }
    },
    methods: {

        callbackForm() {
            this.loadTable = true
            this.selectedPromoCode = null
            this.part = 0
            this.$nextTick(() => {
                this.loadTable = false
            })
        },
        createPromoCode() {
            this.part = 2
        },
        selectPromoCode(code) {
            this.loadForm = true
            this.$nextTick(() => {
                this.loadForm = false
                this.selectedPromoCode = code
                this.part = 1
                this.tab = 0

            })

        }
    }
}
</script>
