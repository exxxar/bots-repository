<script setup>
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style" v-if="botUser.is_admin">
            <div class="content mb-2">
                <h3>Основная статистика</h3>
                <p>
                    Сводка всех показателей эффективности работы системы
                </p>
                <table class="table table-borderless  rounded-sm shadow-l" style="overflow: hidden;" v-if="statistic">
                    <thead>
                    <tr class="bg-gray1-dark">
                        <th scope="col" class="color-theme">Ключ</th>
                        <th scope="col" class="color-theme">Значение</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Всего пользователей в БД</th>
                        <td class="font-weight-bold">{{ statistic.users_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Всего VIP</th>
                        <td class="font-weight-bold">{{ statistic.vip_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Администраторы в БД</th>
                        <td class="font-weight-bold">{{ statistic.admin_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Администраторы за работой</th>
                        <td class="font-weight-bold">{{ statistic.work_admin_in_bd || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Пользователей за день</th>
                        <td class="font-weight-bold">{{ statistic.users_in_bd_today || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">VIP за день</th>
                        <td class="font-weight-bold">{{ statistic.vip_in_bd_today || 0 }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Выдано кэшбэка за день, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_day_up_people_count">({{statistic.cashback_day_up_people_count}} чел)</strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Списано кэшбэка за день, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_down || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_day_down_people_count">({{statistic.cashback_day_down_people_count}} чел)</strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Всего кэшбэка на счету у пользователей, руб</th>
                        <td class="font-weight-bold">{{ (statistic.summary_cashback || 0).toFixed(2) }}
                            <strong v-if="statistic.summary_cashback_people_count">({{statistic.summary_cashback_people_count}} чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего кэшбэка начислено пользователям, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_summary_up || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_summary_up_people_count">({{statistic.cashback_summary_up_people_count}} чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего кэшбэка списано у пользователей, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_summary_down || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_summary_down_people_count">({{statistic.cashback_summary_down_people_count}} чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего за день начислено кэшбэка первого уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up_level_1 || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_day_up_level_1_people_count">({{statistic.cashback_day_up_level_1_people_count}} чел)</strong>
                        </td>

                    </tr>

                    <tr>
                        <th scope="row">Всего за день начислено кэшбэка второго уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up_level_2 || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_day_up_level_2_people_count">({{statistic.cashback_day_up_level_2_people_count}} чел)</strong>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Всего за день начислено кэшбэка третьего уровня, руб</th>
                        <td class="font-weight-bold">{{ (statistic.cashback_day_up_level_3 || 0).toFixed(2) }}
                            <strong v-if="statistic.cashback_day_up_level_3_people_count">({{statistic.cashback_day_up_level_3_people_count}} чел)</strong>
                        </td>
                    </tr>

                    </tbody>
                </table>
                <p class="text-danger" v-else>Статистика еще не загружена</p>

                <a href="javascript:void(0)"
                   @click="downloadBotStatistic"
                   class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-blue1-dark color-blue1-dark bg-theme">
                    <i class="fa-regular fa-file-excel mr-2"></i> Скачать статистику
                </a>
                <ReturnToBot/>
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
            return this.$store.dispatch("statisticLoad")
                .then((response) => {
                    this.statistic = response.statistic

                })
        },
        downloadBotStatistic() {
            this.$botNotification.notification("Внимание!", "Начался формироваться документ статистики!");
            this.$store.dispatch("downloadBotStatistic").then((resp) => {
               // saveAs(resp.data, 'result.xlsx');

                this.$botNotification.success("Отлично!", "Документ успешно сформирован");

            }).catch(() => {
                this.$botNotification.warning("Упс...", "Что-то пошло не так...");
            })
        },

    }
}
</script>
