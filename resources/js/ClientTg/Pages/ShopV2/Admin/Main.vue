<script setup>

import Mail from '@/ClientTg/Components/ShopV2/Admin/Mail/Mail.vue'
</script>
<template>

    <div class="container my-3 g-2" v-if="tab===0">
        <h3>Административное меню</h3>
        <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
            <button type="button"
                    @click="tab=1"
                    class="btn btn-primary">Рассылки</button>
            <button
                @click="tab=2"
                type="button" class="btn btn-primary">Работа с магазином</button>
        </div>
    </div>

    <div class="container" v-if="tab===1">
        <button type="button" @click="tab=0">Назад</button>
        <Mail></Mail>
    </div>
</template>
<script>


import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab:0,
        }
    },
    watch:{
        'getSelf': {
            handler: function (newValue) {


            },
            deep: true
        },

    },
    computed:{
        ...mapGetters(['getSelf']),
        self(){
            return this.getSelf
        }
    },
    mounted() {


    },
    methods: {

    }
}
</script>
