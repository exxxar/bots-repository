<template>
    <h3>Настройка FrontPad</h3>
    <form
        v-on:submit.prevent="submit">

        <div class="form-floating mb-2">
            <input type="text" class="form-control"
                   placeholder="Токен"
                   aria-label="Токен"
                   v-model="frontPadForm.token"
                   aria-describedby="token">

            <label id="token">
                Токен интеграции
            </label>
        </div>

        <!--
                <div class="col-md-6 mb-2">
                    <label class="form-label" id="hook_url">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>Url для отправки вебхука по заказам</div>
                            </template>
                        </Popper>
                        Url для отправки вебхука по заказам
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>

                    <input type="text" class="form-control"
                           placeholder="Hook url"
                           aria-label="Hook url"
                           v-model="frontPadForm.hook_url"
                           aria-describedby="hook_url" required>

                </div>
        -->

        <div class="alert alert-light">
            Данные брать из справочника FrontPad "Каналы продаж". Создать канал. Telegram. Указать значение для API
        </div>

        <div class="form-floating mb-2">
            <input type="text" class="form-control"
                   placeholder="channel"
                   aria-label="channel"
                   v-model="frontPadForm.channel"
                   aria-describedby="channel">
            <label class="form-label" id="channel">
                Канал продаж
            </label>
        </div>

        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   placeholder="Филиал"
                   aria-label="Филиал"
                   v-model="frontPadForm.affiliate"
                   aria-describedby="Филиал">
            <label id="affiliate">
                Филиал
            </label>
        </div>

        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   placeholder="Точка продаж"
                   aria-label="Точка продаж"
                   v-model="frontPadForm.point"
                   aria-describedby="Точка продаж">
            <label class="form-label" id="point">
                Точка продаж
            </label>
        </div>
        <!--        <div class="col-md-12 col-12" v-if="!hasConnect">
                    <button  class="btn btn-outline-info p-3 w-100" ><i class="fa-solid fa-plug mr-1"></i> Проверить подключение</button>
                </div>-->


            <div class="alert alert-light" role="alert">
                Данные брать из справочника FrontPad "Статусы". Указать значение для API
            </div>
            <div class="card mb-2 p-0" v-if="frontPadForm.statuses">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Статусы</h6>
                </div>
                <div class="card-body">

                    <table class="table">
                        <thead>
                        <tr>

                            <th scope="col">Название</th>
                            <th scope="col">Ключ</th>
                            <th scope="col">Значение</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in frontPadForm.statuses">
                            <td>{{ item.title || '-' }}</td>
                            <td>{{ item.key || '-' }}</td>
                            <td>
                                <input type="text" class="form-control"
                                       placeholder="Статус"
                                       aria-label="Статус"
                                       v-model="frontPadForm.statuses[index].value"
                                       aria-describedby="Статус">
                            </td>
                        </tr>
                        </tbody>
                    </table>


                </div>
            </div>


        <div class="alert alert-light" role="alert">
            Данные брать из справочника FrontPad "Варианты оплат". Указать значение для API
        </div>
        <div class="card mb-2 p-0" v-if="frontPadForm.pays">
            <div class="card-header">
                <h6 class="fw-bold mb-0">Типы оплаты</h6>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>

                        <th scope="col">Название</th>
                        <th scope="col">Ключ</th>
                        <th scope="col">Значение</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in frontPadForm.pays">

                        <td>{{ item.title || '-' }}</td>
                        <td>{{ item.key || '-' }}</td>
                        <td>
                            <input type="text" class="form-control"
                                   placeholder="Статус"
                                   aria-label="Статус"
                                   v-model="frontPadForm.pays[index].value"
                                   aria-describedby="Статус">
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <button type="submit" class="btn btn-primary p-3 w-100">
            <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку
        </button>
    </form>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["data"],
    data() {
        return {
            hasConnect: false,
            bot: null,

            frontPadForm: {
                hook_url: null,
                channel: null,
                affiliate: null,
                point: null,
                token: null,
                statuses: [
                    {
                        value: null,
                        key: "new",
                        title: "Новый",
                    },

                ],
                bot_id: null,
                pays: [
                    {
                        value: null,
                        key: "cash",
                        title: "Наличные",
                    },
                    {
                        value: null,
                        key: "card",
                        title: "Перевод на карту",
                    },
                ],
            },
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        this.bot = this.getCurrentBot

        if (this.data)
            this.$nextTick(() => {
                this.frontPadForm.hook_url = this.data.hook_url || null
                this.frontPadForm.channel = this.data.channel || null
                this.frontPadForm.affiliate = this.data.affiliate || null
                this.frontPadForm.point = this.data.point || null
                this.frontPadForm.token = this.data.token || null
                this.frontPadForm.bot_id = this.data.bot_id || this.bot.id || null
                if (this.data.pays != null)
                    this.frontPadForm.pays = this.data.pays
                if (this.data.statuses != null)
                    this.frontPadForm.statuses = this.data.statuses

            })
        else {
            this.frontPadForm.bot_id = this.bot.id || null
        }


    },
    methods: {
        submit() {
            /*    if (!this.hasConnect) {
                    return;
                }*/
            let data = new FormData();
            Object.keys(this.frontPadForm)
                .forEach(key => {
                    const item = this.frontPadForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("saveFrontPad", {
                frontPadForm: data
            }).then((response) => {
                this.$notify("Данные CRM успешно сохранены");
            }).catch(err => {

            })


        }
    }
}
</script>
