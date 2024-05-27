<template>

    <form
        v-on:submit.prevent="submit"
        class="row">

        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="token">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Токен интеграции</div>
                    </template>
                </Popper>
                Токен интеграции
                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
            </label>
            <input type="text" class="form-control"
                   placeholder="Токен"
                   aria-label="Токен"
                   v-model="frontPadForm.token"
                   aria-describedby="token">
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

        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="channel">
                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>канал продаж</div>
                    </template>
                </Popper>
                Канал продаж
            </label>
            <input type="text" class="form-control"
                   placeholder="channel"
                   aria-label="channel"
                   v-model="frontPadForm.channel"
                   aria-describedby="channel">
        </div>

        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="affiliate">

                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Филиал</div>
                    </template>
                </Popper>
                Филиал
            </label>
            <input type="text" class="form-control"
                   placeholder="Филиал"
                   aria-label="Филиал"
                   v-model="frontPadForm.affiliate"
                   aria-describedby="Филиал">
        </div>

        <div class="col-md-6 col-12 mb-2">
            <label class="form-label" id="point">

                <Popper>
                    <i class="fa-regular fa-circle-question mr-1"></i>
                    <template #content>
                        <div>Филиал</div>
                    </template>
                </Popper>
                Точка продаж
            </label>
            <input type="text" class="form-control"
                   placeholder="Точка продаж"
                   aria-label="Точка продаж"
                   v-model="frontPadForm.point"
                   aria-describedby="Точка продаж">
        </div>
        <!--        <div class="col-md-12 col-12" v-if="!hasConnect">
                    <button  class="btn btn-outline-info p-3 w-100" ><i class="fa-solid fa-plug mr-1"></i> Проверить подключение</button>
                </div>-->

        <div class="col-12">
            <div class="card mb-2 p-0" v-if="frontPadForm.statuses">
                <div class="card-header">
                    <h6>Статусы</h6>
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
        </div>

        <div class="col-12">
            <div class="card mb-2 p-0" v-if="frontPadForm.pays">
                <div class="card-header">
                    <h6>Типы оплаты</h6>
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
        </div>

        <div class="col-md-12 col-12">
            <button type="submit" class="btn btn-outline-primary p-3 w-100">
                <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку
            </button>
        </div>
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
