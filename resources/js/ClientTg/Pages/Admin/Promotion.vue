<script setup>
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
import Mail from "@/ClientTg/Components/Admin/Mail/Mail.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style" v-if="botUser.is_admin">
            <div class="content mb-2">
                <Mail></Mail>
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
            return this.$store.dispatch("cashmanAdminStatisticPrepare")
                .then((response) => {
                    this.statistic = response.statistic

                })
        },


    }
}
</script>
