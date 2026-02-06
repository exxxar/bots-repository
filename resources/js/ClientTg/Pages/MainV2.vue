<script setup>

defineProps({
    bot: {
        type: Object,
    },
    theme: {
        type: String,
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
            <notifications position="top right"
                           ignoreDuplicates="true"
                           max="3"
                           width="100%" speed="10"/>

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
        window.theme = this.theme


        const schedule = window.currentBot.company.schedule || [];

/*        window.isCorrectSchedule = (schedule) => {
            if ((schedule || []).length < 7)
                return false

            let isCorrect = true
            schedule.forEach(day => {
                isCorrect = isCorrect && typeof day == 'object'
            })

            return isCorrect
        }*/

        window.isCorrectSchedule = (schedule) => {

            if (!Array.isArray(schedule) || schedule.length !== 7) {
                return false;
            }

            return schedule.every(day => {
                if (!day || typeof day !== "object") {
                    return false;
                }

                // closed может быть true/false
                if (typeof day.closed !== "boolean") {
                    return false;
                }

                // если день не закрыт — должны быть start_at и end_at
                if (!day.closed) {
                    if (typeof day.start_at !== "string") return false;
                    if (typeof day.end_at !== "string") return false;

                    // простая проверка формата HH:MM
                    if (!/^\d{2}:\d{2}$/.test(day.start_at)) return false;
                    if (!/^\d{2}:\d{2}$/.test(day.end_at)) return false;
                }

                return true;
            });
        };


        if (window.isCorrectSchedule(schedule)) {

            // Если расписания нет или оно пустое — считаем, что компания работает
            if (!schedule || schedule.length === 0) {
                window.currentBot.company.is_work = true;
            } else {

                const now = new Date();
                const day = now.getDay(); // локальный день недели (0–6)
                const hours = now.getHours();
                const minutes = now.getMinutes();

                const today = schedule[day];

                // Если для текущего дня нет настроек — считаем закрытым
                if (!today) {
                    window.currentBot.company.is_work = false;
                } else {

                    const [startH, startM] = (today.start_at || "08:00")
                        .split(":")
                        .map(Number);

                    const [endH, endM] = (today.end_at || "20:00")
                        .split(":")
                        .map(Number);

                    const nowMinutes = hours * 60 + minutes;
                    const startMinutes = startH * 60 + startM;
                    const endMinutes = endH * 60 + endM;

                    let isWork = false;

                    if (!today.closed) {

                        if (startMinutes <= endMinutes) {
                            // Обычный график (например 08:00–20:00)
                            isWork =
                                nowMinutes >= startMinutes &&
                                nowMinutes < endMinutes;
                        } else {
                            // График через полночь (например 20:00–04:00)
                            isWork =
                                nowMinutes >= startMinutes ||
                                nowMinutes < endMinutes;
                        }

                    }

                    window.currentBot.company.is_work = isWork;
                }
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


        this.$notify({
            title: 'Главная',
            text: "Успешно!",
            type: "success",
        });

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.push({name: 'MenuV2'})
        })
    },
    mounted() {
      // this.tg.addToHomeScreen()
    },
    methods: {
        open(url) {
            this.tg.openLink(url)
        },
    }

}
</script>


