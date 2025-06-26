<script setup>
import GlobalSlugList from "@/AdminPanel/Components/Constructor/Slugs/GlobalSlugList.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm">

        <div class="form-floating mb-2">
            <input type="text"
                   v-model="promoCodeForm.code"
                   maxlength="255"
                   class="form-control"
                   required id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Текст промокода</label>
        </div>

        <div class="form-floating mb-2">
            <textarea class="form-control"
                      maxlength="255"
                      v-model="promoCodeForm.description"
                      placeholder="Leave a comment here" id="floatingTextarea" required></textarea>
            <label for="floatingTextarea"> Назначение промокода</label>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   v-model="promoCodeForm.is_active"
                   type="checkbox" role="switch" id="is_active">
            <label class="form-check-label"
                   for="is_active">Доступен для активации</label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   step="1"
                   min="1"
                   v-model="promoCodeForm.max_activation_count"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput"> Максимальное число активаций кода</label>
        </div>

        <div class="alert alert-light mb-2">
            Данный параметр позволяет настроить скидку при покупке товаров в магазине в
            <span
                class="text-primary"
                v-bind:class="{'fw-bold':promoCodeForm.config.discount_in_percent}">%</span> или в
            <span
                class="text-primary"
                v-bind:class="{'fw-bold':!promoCodeForm.config.discount_in_percent}">рублях</span>
        </div>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   v-model="promoCodeForm.config.discount_in_percent"
                   type="checkbox" role="switch" id="discount_in_percent">
            <label class="form-check-label"
                   for="discount_in_percent">Скидка в процентах</label>
        </div>

        <div
            v-if="!promoCodeForm.config.discount_in_percent"
            class="form-floating mb-2">
            <input type="number"
                   step="1"
                   min="1"
                   v-model="promoCodeForm.cashback_amount"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput"> Величина скидки \ баллов, руб</label>
        </div>

        <div
            v-if="promoCodeForm.config.discount_in_percent"
            class="form-floating mb-2">
            <input type="number"
                   step="1"
                   min="1"
                   max="100"
                   v-model="promoCodeForm.cashback_amount"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput"> Величина скидки \ баллов, %</label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   step="1"
                   min="0"
                   v-model="promoCodeForm.activate_price"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput"> Сумма активации от... руб</label>
        </div>

        <div class="form-floating mb-2">
            <input type="datetime-local"
                   v-model="promoCodeForm.available_to"
                   class="form-control"
                   id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Доступен до...</label>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   v-model="promoCodeForm.need_certificate"
                   type="checkbox" role="switch" id="need_certificate">
            <label class="form-check-label"
                   for="is_active">Сгенерировать красивый сертификат</label>
        </div>

        <button
            type="submit" class="btn btn-primary w-100 p-3">
            <span v-if="promoCodeForm.id==null">Создать промокод</span>
            <span v-else>Обновить промокод</span>
        </button>


    </form>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: ["code"],
    data() {
        return {
            step: 0,
            load: false,
            need_reset: false,
            promoCodeForm: {
                id: null,
                code: null,
                description: null,
                cashback_amount: 0,
                max_activation_count: 1,
                is_active: true,
                available_to: null,
                activate_price: 0,
                need_certificate: true,
                config: {
                    discount_in_percent: false,
                },

            }
        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        bot() {
            return window.currentBot
        }

    },
    watch: {
        promoCodeForm: {
            handler(val) {
                this.need_reset = true

            },
            deep: true
        },
        'promoCodeForm.config.discount_in_percent': {
            handler(val) {
                if (this.promoCodeForm.config.discount_in_percent && this.promoCodeForm.cashback_amount > 100)
                    this.promoCodeForm.cashback_amount = 50
            },
            deep: true
        },

    },
    mounted() {

        if (this.code)
            this.$nextTick(() => {
                this.promoCodeForm = {
                    id: this.code.id || null,
                    description: this.code.description || null,
                    code: this.code.code || null,
                    cashback_amount: this.code.cashback_amount || 0,
                    max_activation_count: this.code.max_activation_count || 1,
                    is_active: this.code.is_active || false,
                    available_to: this.code.available_to ? this.$filters.local(this.code.available_to) : null

                }

                if (this.code.config) {
                    this.promoCodeForm.config = {
                        discount_in_percent: this.code.config.discount_in_percent || false
                    }
                }


            })

    },
    methods: {

        submitForm() {
            let data = new FormData();
            Object.keys(this.promoCodeForm)
                .forEach(key => {
                    const item = this.promoCodeForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);

            this.$store.dispatch("storePromoCodes",
                {
                    promoCodeForm: data
                }).then((response) => {

                this.promoCodeForm = {
                    id: null,
                    code: null,
                    description: null,
                    cashback_amount: 0,
                    max_activation_count: 1,
                    is_active: true,
                    need_certificate: true,
                    config: {
                        discount_in_percent: false,
                    }
                }

                this.$emit("callback", response.data)
                this.$notify("Промокод успешно создан");
            }).catch(err => {
                this.$notify("Ошибка создания промокода");
            })

        },

    }
}
</script>
