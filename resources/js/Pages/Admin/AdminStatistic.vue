<script setup>

defineProps({
    bot: {
        type: Object,
    },
    botUser: {
        type: Object
    },
    statistic: {
        type: Object
    }
});
</script>
<template>
    <div class="container" v-if="botUser.is_admin">
        <div class="row mb-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Статистика бота</h5>
                    </div>
                    <div class="card-body">
                        <p> Всего пользователей в БД: <strong>{{statistic.users_in_bd || 0}}</strong></p>
                        <p> Всего VIP: <strong>{{statistic.vip_in_bd || 0}}</strong></p>
                        <p> Администраторы в БД: <strong>{{statistic.admin_in_bd || 0}}</strong></p>
                        <p> Администраторы за работой: <strong>{{statistic.work_admin_in_bd || 0}}</strong></p>
                        <p> Пользователей за день: <strong>{{statistic.users_in_bd_today || 0}}</strong></p>
                        <p> VIP за день: <strong>{{statistic.vip_in_bd_today || 0}}</strong></p>
                        <p> Выдано кэшбэка за день: <strong>{{statistic.cashback_day_up || 0}} руб.</strong></p>
                        <p> Списано кэшбэка за день: <strong>{{statistic.cashback_day_down || 0}} руб.</strong></p>
                        <p> Всего кэшбэка на счету у пользователей: <strong>{{statistic.summary_cashback || 0}} руб.</strong></p>
                        <p> Всего кэшбэка начислено пользователям: <strong>{{statistic.cashback_summary_up || 0}} руб.</strong></p>
                        <p> Всего кэшбэка списано у пользователей: <strong>{{statistic.cashback_summary_down || 0}} руб.</strong></p>
                        <p> Всего за день начислено кэшбэка первого уровня: <strong>{{statistic.cashback_day_up_level_1 || 0}} руб.</strong></p>
                        <p> Всего за день начислено кэшбэка второго уровня: <strong>{{statistic.cashback_day_up_level_2 || 0}} руб.</strong></p>
                        <p> Всего за день начислено кэшбэка третьего уровня: <strong>{{statistic.cashback_day_up_level_3 || 0}} руб.</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container" v-else>
        <div class="row">
            <div class="alert alert-warning" role="alert">
                Вы не являетесь администратором
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    methods: {

    }
}
</script>
