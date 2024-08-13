<script setup>
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="container" v-if="botUser">

        <div class="row">
            <div class="col-12">
                <h6 class="my-3 fw-bold">Основная статистика</h6>
                <p class="fst-italic">
                    Сводка всех показателей эффективности работы системы
                </p>
                <table class="table" v-if="statistic">
                    <thead>
                    <tr class="bg-light">
                        <th scope="col" style="width:200px;">Ключ</th>
                        <th scope="col">Значение</th>
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
                   class="btn w-100 btn-outline-info p-3">
                    <i class="fa-regular fa-file-excel mr-2"></i> Скачать статистику
                </a>
            </div>
        </div>

    </div>

</template>
<script>
import {mapGetters} from "vuex";
import {saveAs} from 'file-saver';

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
        tg() {
            return window.Telegram.WebApp;
        },
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

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
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
