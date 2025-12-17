<script setup>
import AddPartnerForm from "@/ClientTg/Components/V2/Admin/Partners/AddPartnerForm.vue";
import PartnerList from "@/ClientTg/Components/V2/Admin/Partners/PartnerList.vue";
import SelfConfigForm from "@/ClientTg/Components/V2/Admin/Partners/SelfConfigForm.vue";

</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input"
                           type="checkbox"
                           @change="updatePartnersSettings"
                           v-model="form.is_active"
                           role="switch" id="partners-is_active">
                    <label class="form-check-label" for="partners-is_active">Режим работы с партнерами <span
                        v-bind:class="{'text-primary fw-bold':form.is_active}">вкл</span> \ <span
                        v-bind:class="{'text-primary fw-bold':!form.is_active}">выкл</span></label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input"
                           type="checkbox"
                           @change="updatePartnersSettings"
                           v-model="form.display_self"
                           role="switch" id="partners-display_self">
                    <label class="form-check-label" for="partners-display_self">Отображать себя в списке <span
                        v-bind:class="{'text-primary fw-bold':form.display_self}">вкл</span> \ <span
                        v-bind:class="{'text-primary fw-bold':!form.display_self}">выкл</span></label>
                </div>
            </div>


            <div class="col-12">
                <button
                    v-if="form.display_self"
                    @click="openSelfConfigModal"
                    type="button" class="btn btn-outline-primary p-3 w-100 mb-2">Свои параметы отображения
                </button>
            </div>

            <div class="col-12">
                <ul class="nav nav-tabs justify-content-center catalog-tabs">

                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            @click="tab=0"
                            style="font-weight:bold;"
                            v-bind:class="{'active':tab===0}"
                            aria-current="page">Партнеры
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            @click="tab=1"
                            style="font-weight:bold;"
                            v-bind:class="{'active':tab===1}"
                        >Статистика продаж
                        </button>
                    </li>

                </ul>
            </div>
            <div class="col-12" v-show="tab===0">
                <PartnerList></PartnerList>
                <AddPartnerForm></AddPartnerForm>
            </div>

            <div class="col-12" v-if="tab===1">

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="self-config-partner-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Настройка партнера</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <SelfConfigForm v-on:success="closeSelfConfigModal"></SelfConfigForm>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            config_modal: null,
            tab: 0,
            form: {
                is_active: false,
                display_self: false
            }

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
        this.config_modal = new bootstrap.Modal(document.getElementById('self-config-partner-modal'));
        this.form.is_active = this.currentBot?.settings?.partners?.is_active ?? false
        this.form.display_self = this.currentBot?.settings?.partners?.display_self ?? false
        // this.loadPartners()

    },


    methods: {
        openSelfConfigModal() {
            this.config_modal.show()
        },
        closeSelfConfigModal() {
            this.config_modal.hide()
        },
        updatePartnersSettings() {
            this.$store.dispatch("updatePartnersSettings", this.form).then(resp => {

            }).catch(() => {

            })
        },
        loadPartners() {

            this.$store.dispatch("loadPartners", {}).then(resp => {

            }).catch(() => {

            })
        },

    }
}
</script>
