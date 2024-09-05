<script setup>
import ScheduleList from "@/ClientTg/Components/V2/Shop/ScheduleList.vue";
</script>
<template>

    <div class="container py-3" v-if="bot">
        <div
            v-if="(company.links||{map_link:null}).map_link"
            class="d-flex" style="min-height:300px;overflow:hidden;">

            <div v-html="company.links.map_link"></div>
        </div>
        <div class="alert alert-danger" role="alert" v-else>
            <p class="mb-0 fw-bold">Администратор еще не добавили виджет Яндекс.Карты с расположением заведения!</p>
        </div>

        <h6 class="my-3">{{ bot.company.address || 'Адрес вашего заведения' }}</h6>
        <h6 class="opacity-75 mb-3 d-flex justify-content-between align-items-center">Контактная информация <a
            v-if="(getSelf||{is_admin:false}).is_admin"
            data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
            href="javascript:void(0)" class="text-primary ml-2"><i class="fa-solid fa-pen-to-square"></i></a></h6>

        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span class="text-primary fw-bold" style="font-size:12px;">{{ phone || '-' }}</span>
            </li>
            <li
                v-if="links.inst"
                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Инста</span>
                <span class="text-primary fw-bold" style="font-size:12px;">{{ links.inst || '-' }}</span>
            </li>
            <li
                v-if="links.vk"
                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Вконтакте</span>
                <span class="text-primary fw-bold" style="font-size:12px;">{{ links.vk || '-' }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Почта</span>
                <span class="text-primary fw-bold" style="font-size:12px;">{{ company.email || '-' }}</span>
            </li>
            <li
                v-if="links.site"
                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Почта</span>
                <span class="text-primary fw-bold" style="font-size:12px;">{{ links.site || '-' }}</span>
            </li>
        </ul>

        <h6 class="opacity-75 my-3 d-flex justify-content-between align-items-center">Прием заказов осуществляется <a
            v-if="(getSelf||{is_admin:false}).is_admin"
            data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
            href="javascript:void(0)" class="text-primary ml-2"><i class="fa-solid fa-pen-to-square"></i></a></h6>

        <ScheduleList
            v-if="isCorrectSchedule"
            :schedule="company.schedule"></ScheduleList>
        <div class="alert alert-danger" v-else>
            <p class="mb-0 fw-bold">График работ еще не составлен:)</p>
        </div>
        <!--        <h6 class="opacity-75 my-3">Специальные возможности</h6>
                <button class="btn btn-outline-primary w-100 mb-2">Пригласить администратора</button>
                <button class="btn btn-outline-primary w-100 mb-2">Запросить CashBack</button>
                <button class="btn btn-outline-primary w-100 mb-2">Забронировать столик</button>-->
    </div>

</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            settings: {
                can_use_cash: true,
                can_use_card: true,
                delivery_price_text: null,
                min_price: 0,
                min_price_for_cashback: 0,
                menu_list_type: 0,
                payment_info: 0,
                need_category_by_page: false,
                need_pay_after_call: false,
                free_shipping_starts_from: 0,
                yandex_map_link: null,
            },

        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
        bot() {
            return window.currentBot
        },
        isCorrectSchedule() {
            return window.isCorrectSchedule(this.bot.company.schedule);
        },
        company() {
            return this.bot.company
        },
        phone() {
            if (!this.bot.company.phones)
                return null
            return this.bot.company.phones[0] || null
        },
        links() {
            return {
                inst: (this.bot.company.links || {inst: null}).inst || null,
                vk: (this.bot.company.links || {vk: null}).vk || null,
                map_link: (this.bot.company.links || {map_link: null}).map_link || null,
                site: (this.bot.company.links || {site: null}).site || null,
            }
        }
    },
    mounted() {

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {}
}
</script>
<style>
.current-day {
    background-image: repeating-linear-gradient(-45deg, #eee 0, #eee 15px, #fff 15px, #fff 25px);
}
</style>
