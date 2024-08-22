<script setup>
import IikoForm from "@/ClientTg/Components/V2/Admin/Iiko/IikoForm.vue";
import IikoProducts from "@/ClientTg/Components/V2/Admin/Iiko/IikoProducts.vue";

</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs d-flex justify-content-center mb-2">
                    <li class="nav-item">
                        <a class="nav-link"
                           v-bind:class="{'active':tab===0}"
                           @click="tab=0"
                           aria-current="page"
                           href="javascript:void(0)"><i class="fa-solid fa-gears"></i> Настройка</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           v-bind:class="{'active':tab===1}"
                           @click="tab=1"
                           href="javascript:void(0)">
                            <i class="fa-brands fa-shopify"></i>
                            Продукты</a>
                    </li>
                </ul>
            </div>
            <div class="col-12" v-if="tab===0">
                <IikoForm
                    v-model="currentBot.iiko"
                    v-if="!load&&currentBot"
                />
            </div>
            <div class="col-12" v-if="tab===1">
                <IikoProducts></IikoProducts>
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
            load: false,

        }
    },
    computed:{
        currentBot(){
            return window.currentBot || null
        }
    },

    mounted() {


    },
    methods: {

    }
}
</script>

