<script setup>
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
import MailToChannel from "@/ClientTg/Components/Admin/Mail/MailToChannel.vue";
import MailToBot from "@/ClientTg/Components/Admin/Mail/MailToBot.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style" v-if="botUser.is_admin">
            <div class="content mb-2">
                <p class="mb-2">Выберите куда вы хотите отправить сообщение</p>
                <a href="javascript:void(0)"
                   @click="tab=1"
                   v-bind:class="{'border-green2-dark color-green2-dark':tab===1,'border-teal-dark color-gray2-dark':tab!==1}"
                   class="btn btn-border btn-m btn-full mb-2 rounded-0 text-uppercase font-900 bg-theme ">
                    Рассылка в канал
                </a>
                <a href="javascript:void(0)"
                   @click="tab=0"
                   v-bind:class="{'border-green2-dark color-green2-dark':tab===0,'border-teal-dark color-gray2-dark':tab!==0}"
                   class="btn btn-border btn-m btn-full mb-2 rounded-0 text-uppercase font-900 bg-theme">
                    Рассылка в бота
                </a>
                <MailToChannel v-if="tab===1"></MailToChannel>
                <MailToBot v-if="tab===0"></MailToBot>
                <div class="divider divider-small my-3 bg-highlight "></div>

                <ReturnToBot class="mb-2"/>
            </div>
        </div>

        <div class="card card-style bg-red2-dark" v-else>
            <div class="content">
                <h4 class="color-white">Внимание!</h4>
                <p class="color-white">
                    Данная страница доступа только администраторам заведения!
                </p>
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
            botUser: null,
            statistic: null,
            loading: false,
        }
    },
    computed: {
        ...mapGetters(['getSelf']),
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf
            this.prepareStatistic()
        }
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
            this.prepareStatistic()
        }
    },
    methods: {
        prepareStatistic() {
            return this.$store.dispatch("statisticLoad")
                .then((response) => {
                    this.statistic = response.statistic

                })
        },


    }
}
</script>
