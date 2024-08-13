<script setup>
import PromoCodesForm from "@/ClientTg/Components/V2/Admin/Promocodes/PromoCodesForm.vue";
import PromoCodesList from "@/ClientTg/Components/V2/Admin/Promocodes/PromoCodesList.vue";
</script>
<template>

    <div class="container py-2">
        <PromoCodesList
            v-if="!loadTable"
            v-on:create="createPromoCode"
            v-on:select="selectPromoCode"
            :bot="bot"></PromoCodesList>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="promocode-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <PromoCodesForm
                            v-if="!loadForm"
                            v-on:callback="callbackForm"
                            :code="selectedPromoCode"/>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="fixed-footer p-3">
        <button type="button"
                @click="createPromoCode"
                class="btn btn-primary w-100 p-3">
            Создать новый промокод
        </button>
    </div>
</template>
<script>
export default {
    props: ["bot"],
    data() {
        return {
            part: 0,
            tab: 0,
            promocodeFormModal:null,
            selectedPromoCode:null,
            loadForm:false,
            loadTable:false,
        }
    },
    computed:{
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        this.promocodeFormModal = new bootstrap.Modal('#promocode-form', {})

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {

        callbackForm() {
            this.loadTable = true
            this.selectedPromoCode = null
            this.promocodeFormModal.hide();
            this.$nextTick(() => {
                this.loadTable = false
            })
        },
        createPromoCode() {
            this.selectedPromoCode = null
            this.loadForm = true
            this.$nextTick(() => {
                this.loadForm = false


                this.promocodeFormModal.show();
            })

        },
        selectPromoCode(code) {
            this.loadForm = true
            this.$nextTick(() => {
                this.loadForm = false

                this.selectedPromoCode = code
                this.promocodeFormModal.show()
            })

        }
    }
}
</script>
