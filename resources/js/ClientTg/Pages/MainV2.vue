<script setup>

defineProps({
    bot: {
        type: Object,
    },
    slug_id: {
        type: String,
    },
});


import Layout from "@/ClientTg/Layouts/V2Layout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <notifications position="top right" width="100%" speed="100"/>

            <router-view
                :bot="bot"/>
        </template>
    </Layout>
</template>

<script>
import {mapGetters} from "vuex";


export default {
    computed: {
        ...mapGetters(['getSelf']),
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
        self() {
            return window.self || null
        },
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        },
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        },
        link() {
            return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("001" + this.self.telegram_chat_id);
        }
    },
    created() {
        window.currentBot = this.bot.data

        const schedule = window.currentBot.company.schedule || [];

        window.isCorrectSchedule = (schedule) => {
            if ((schedule || []).length < 7)
                return false

            let isCorrect = true
            schedule.forEach(day => {
                isCorrect = isCorrect && typeof day == 'object'
            })

            return isCorrect
        }

        if (window.isCorrectSchedule(schedule)) {

            if (!schedule || (schedule || []).length === 0) {
                window.currentBot.company.is_work = true
            }


            if (schedule && (schedule || []).length > 0) {

                const day = (new Date()).getUTCDay();

                const hours = (new Date()).getHours();
                const minutes = (new Date()).getMinutes();

                let tmpStartAt = schedule[day].start_at || "08:00";
                let tmpStartHours = parseInt(tmpStartAt.split(":")[0]);
                let tmpStartMinutes = parseInt(tmpStartAt.split(":")[1]);

                let tmpEndAt = schedule[day].end_at || "20:00";
                let tmpEndHours = parseInt(tmpEndAt.split(":")[0]);
                let tmpEndMinutes = parseInt(tmpEndAt.split(":")[1]);

                let isWork = false

                if (tmpStartHours === hours)
                    isWork = minutes >= tmpStartMinutes

                if (tmpEndHours === hours)
                    isWork = minutes < tmpEndMinutes


                if (hours > tmpStartHours && hours < tmpEndHours && tmpStartHours <= tmpEndHours)
                    isWork = true;


                if ((hours > tmpStartHours || hours <= tmpEndHours) && tmpStartHours >= tmpEndHours)
                    isWork = true;


                window.currentBot.company.is_work = !(schedule[day].closed || false) && isWork
            }

        }


        /*  if (!($schedule[$day]["closed"] ?? false)) {
              $startHour = explode(":", $schedule[$day]["start_at"])[0] ?? 0;
              $endHour = explode(":", $schedule[$day]["end_at"])[0] ?? 23;

              return response()->json(
                  [
                      'schedule' => $schedule,
                  'current_day' => $day,
                  'opened_comment' => $openedComment,
                  'closed_comment' => $closedComment,
                  'is_work' => $hour >= (int)$startHour && $hour <= (int)$endHour,
          ]

          );*/
        window.currentScript = this.slug_id || null

        this.$store.dispatch("loadSelf").then(() => {
            window.self = this.getSelf
        })

        this.$notify({
            title: 'Главная',
            text: "Успешно!",
            type: "success",
        });

    },
    methods: {
        open(url) {
            this.tg.openLink(url)
        },
    }

}
</script>

<style>

</style>

