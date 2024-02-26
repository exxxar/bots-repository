<script setup>

</script>
<template>
    <form v-on:submit.prevent="submitForm" class="py-3">
        <div class="row ">
            <div class="col-12 mb-3">
                <label class="form-label" id="quiz-title">
                    <Popper
                        content="Название вашего квиза">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Промокод (вписать или сгенерировать)

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Название"
                       aria-label="Название"
                       v-model="promoCodeForm.code"
                       maxlength="255"
                       aria-describedby="quiz-title" required>
            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-description">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация о назначении промокода
                            </div>
                        </template>
                    </Popper>
                    Назначение промокода
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="promoCodeForm.description">
                        Длина текста {{ promoCodeForm.description.length }}/255</small>
                </label>
                <textarea class="form-control"
                          placeholder="Описание промокода"
                          aria-label="Описание промокода"
                          maxlength="255"
                          v-model="promoCodeForm.description"
                          aria-describedby="quiz-description" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="promoCodeForm.is_active"
                           type="checkbox" id="is_active">
                    <label class="form-check-label" for="is_active">
                        Доступен для активации
                    </label>
                </div>
            </div>


            <div class="col-12 mb-3">

                <label class="form-label " id="promoCodeForm-max_activation_count">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Максимальное число активация кода
                            </div>
                        </template>
                    </Popper>
                    Количество активация кода
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Число попыток"
                       aria-label="Число попыток"
                       step="1"
                       min="1"
                       v-model="promoCodeForm.max_activation_count"
                       aria-describedby="promoCodeForm-max_activation_count" required>


            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_cashback"
                           type="checkbox" id="promoCodeForm-cashback_amount">
                    <label class="form-check-label" for="promoCodeForm-cashback_amount">
                        Нужно начислить CashBack по промокоду
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3" v-if="need_cashback">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Сколько % нужно ответить чтоб пройти
                            </div>
                        </template>
                    </Popper>
                    Сколько CashBack начислить по промокоду?
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Процент для победы"
                       aria-label="Процент для победы"
                       step="1"
                       min="0"
                       v-model="promoCodeForm.cashback_amount"
                       aria-describedby="promoCodeForm-cashback_amount" required>


            </div>

            <div class="col-12 mb-3" v-if="getSelf.is_admin">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_slots"
                           type="checkbox" id="promoCodeForm-need_slots">
                    <label class="form-check-label" for="promoCodeForm-need_slots">
                        Нужны слоты для активации
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3" v-if="need_slots">

                <label class="form-label " id="promoCodeForm-slot_amount">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Укажите количество слотов, которые получит пользователь при активации (внимание, доступно только для администраторов)
                            </div>
                        </template>
                    </Popper>
                    Сколько нужно добавить слотов?
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Количество слотов"
                       aria-label="Количество слотов"
                       step="1"
                       min="0"
                       v-model="promoCodeForm.slot_amount"
                       aria-describedby="promoCodeForm-slot_amount" required>


            </div>

        </div>


        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="promoCodeForm.id==null">Создать промокод</span>
                    <span v-else>Обновить промокод</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: ["code", "bot"],
    data() {
        return {
            step: 0,
            load: false,
            need_reset: false,
            need_cashback: false,
            need_slots: false,
            promoCodeForm: {
                id: null,
                code:null,
                description:null,
                slot_amount:0,
                cashback_amount:0,
                max_activation_count:1,
                is_active: false

            }
        }
    },
    computed:{
      getSelf(){
          return window.profile
      }
    },
    watch: {
        promoCodeForm: {
            handler(val) {
                this.need_reset = true
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
                    code:this.code.code || null,
                    slot_amount:this.code.slot_amount || 0,
                    cashback_amount:this.code.cashback_amount || 0,
                    max_activation_count:this.code.max_activation_count || 1,
                    is_active: this.code.is_active || false,
                }

                if (this.promoCodeForm.slot_amount>0)
                    this.need_slots = true;

                if (this.promoCodeForm.cashback_amount>0)
                    this.need_slots = true;

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
                    code:null,
                    description:null,
                    slot_amount:0,
                    cashback_amount:0,
                    max_activation_count:1,
                    is_active: false
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
