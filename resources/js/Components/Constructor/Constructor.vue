<script setup>
import InitialStepper from "@/Components/Constructor/InitialStepper.vue";
import BotEditor from "@/Components/Constructor/BotEditor.vue";

</script>

<template>

    <div class="container">
        <div class="row">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link"
                       v-bind:class="{'active':tab===0}"
                       @click="tab=0"
                       aria-current="page" href="#">Конструктор</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       v-bind:class="{'active':tab===1}"
                       @click="tab=1"
                       href="#">Редактирование ботов</a>
                </li>
<!--                <li class="nav-item">
                    <a class="nav-link disabled"
                       v-bind:class="{'active':tab===2}"
                       @click="tab=2"
                       href="#">Пользователи</a>
                </li>-->
            </ul>
        </div>
        <div class="row" v-if="tab===0">
            <div class="col-12 pt-2 pb-2">
                <InitialStepper/>
            </div>
        </div>
        <div class="row" v-if="tab===1">
            <div class="col-12 pt-2 pb-2">
                <BotEditor/>
            </div>
        </div>
        <div class="row" v-if="tab===2">

        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab: 0
        }
    },
    computed: {
        ...mapGetters(['getErrors']),
    },
    watch: {
        getErrors: function (newVal, oldVal) {
            Object.keys(newVal).forEach(key => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: newVal[key],
                    type: 'warn'
                });
            })

        }
    },
    methods: {}
}
</script>
<style>
:root {
    --popper-theme-background-color: #333333;
    --popper-theme-background-color-hover: #333333;
    --popper-theme-text-color: #ffffff;
    --popper-theme-border-width: 0px;
    --popper-theme-border-style: solid;
    --popper-theme-border-radius: 6px;
    --popper-theme-padding: 5px 10px;
    --popper-theme-box-shadow: 0 6px 30px -6px rgba(0, 0, 0, 0.25);
}
</style>
