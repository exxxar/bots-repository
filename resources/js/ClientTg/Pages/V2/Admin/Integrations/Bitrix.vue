<script setup>
import BitrixForm from "@/ClientTg/Components/V2/Admin/Bitrix/BitrixForm.vue";
</script>
<template>


    <div class="container my-3">
        <div class="row">
            <div class="col-12">
                <h6 class="fw-bold d-flex justify-content-between align-items-center pb-2">Доступные подключения
                    <a href="javascript:void(0)"
                       class="text-decoration-none text-primary px-1"
                       v-if="selected!=null"
                       @click="selectItem(null)"><i class="fa-solid fa-plus"></i></a>
                </h6>
                <ul class="list-group mb-2" v-if="(currentBot.bitrix||[]).length>0">
                    <li class="list-group-item"
                        @click="selectItem(item)"
                        v-bind:class="{'border-success':item.is_active,'border-error':!item.is_active, 'bg-success text-white':(selected||{id:null}).id===item.id}"
                        v-for="item in currentBot.bitrix">{{ item.url || 'не указан' }}
                    </li>

                </ul>
                <div class="alert alert-light" v-else>
                    <strong class="fw-bold text-primary">Внимание!</strong> Еще нет добавленных данных по CRM
                </div>
            </div>
            <div class="col-12">
                <h6 class="fw-bold d-flex justify-content-between align-items-center pb-2">Данные подключения</h6>
                <BitrixForm
                    :bot="currentBot"
                    :data="selected"
                    v-if="!load&&currentBot"
                />
            </div>
        </div>
    </div>


</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            selected: null,
            load: false,
        }
    },
    computed: {
        currentBot() {
            return window.currentBot || null
        }
    },

    mounted() {


    },
    methods: {
        selectItem(item) {
            this.selected = item
            this.load = true
            this.$nextTick(() => {
                this.load = false
            })
        }
    }
}
</script>

