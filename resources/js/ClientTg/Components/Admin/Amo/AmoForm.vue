<template>

    <div class="card card-style">
        <div class="content mb-0">
            <h3>Настройка AMO</h3>

            <form
                v-on:submit.prevent="submitAmo">

                <p class="mb-2"><a target="_blank" href="https://www.amocrm.ru/developers/content/oauth/step-by-step">Документация
                    для
                    разработчика </a> по шагам</p>

                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="client_id">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>ID интеграции</div>
                                </template>
                            </Popper>
                            client_id
                        </div>

                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="client_id"
                           aria-label="client_id"
                           v-model="amoForm.client_id"
                           aria-describedby="client_id" required>
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="client_secret">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Секрет интеграции</div>
                                </template>
                            </Popper>
                            client_secret
                        </div>
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="client_secret"
                           aria-label="client_secret"
                           v-model="amoForm.client_secret"
                           aria-describedby="client_secret" required>
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="auth_code">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Полученный код авторизации</div>
                                </template>
                            </Popper>
                            auth_code
                        </div>
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
                    </label>
                    <textarea class="form-control font-12"
                              style="min-height:250px;"
                              placeholder="auth_code"
                              aria-label="auth_code"
                              v-model="amoForm.auth_code"
                              aria-describedby="auth_code" required>
                                    </textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label d-flex justify-content-between mt-2" id="subdomain2">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div>Ваш поддомен в системе AMO CRM</div>
                                </template>
                            </Popper>
                            subdomain
                        </div>
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>
                    </label>
                    <input type="text" class="form-control"
                           placeholder="subdomain"
                           aria-label="subdomain"
                           v-model="amoForm.subdomain"
                           aria-describedby="subdomain" required>
                </div>

                <button
                    type="button"
                    :disabled="!amoForm.client_id||!amoForm.auth_code||!amoForm.client_secret||!amoForm.subdomain"
                    @click="loadAmoFields"
                    class="btn w-100 btn-m btn-full rounded-s mb-1 rounded-0 text-uppercase font-900 shadow-s bg-green2-dark">
                    Загрузить справочную информацию
                </button>

                <Vue3JsonEditor
                    v-if="!load&&custom_fields"
                    :mode="'tree'"
                    v-model="custom_fields"
                    :show-btns="false"
                    :expandedOnStart="false"
                />

                <div
                    v-if="amoForm.fields"
                    v-for="(field, index) in amoForm.fields">

                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between mt-0 mb-0" :id="'field-key-'+index">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Ключевое значение из справочника</div>
                                        </template>
                                    </Popper>
                                    Ключевое значение из справочника
                                </div>
                                <Popper>
                                    <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                                    <template #content>
                                        <div>Нужно
                                        </div>
                                    </template>
                                </Popper>

                        </label>
                        <input type="number" class="form-control"
                               placeholder="Ключ"
                               aria-label="Ключ"
                               v-model="amoForm.fields[index].key"
                               aria-describedby="Ключ" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between mt-0 mb-0" :id="'field-field-'+index">

                            <div>
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Поле из списка для связи</div>
                                    </template>
                                </Popper>
                                Поле из списка для связи
                            </div>
                            <Popper>
                                <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                                <template #content>
                                    <div>Нужно
                                    </div>
                                </template>
                            </Popper>
                        </label>

                        <select class="form-control font-12"
                                v-model="amoForm.fields[index].field">
                            <option
                                v-for="item in fields"
                                :value="item.field">{{ item.title || 'Ошибка' }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label d-flex justify-content-between mt-0 mb-0" :id="'field-enum-'+index">
                            Перечисление (enum) из справочника

                        </label>
                        <input type="text" class="form-control"
                               placeholder="Перечисление"
                               aria-label="Перечисление"
                               v-model="amoForm.fields[index].enum"
                               aria-describedby="Перечисление">
                    </div>
                    <button
                        type="button"
                        @click="removeField(index)"
                        class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-red1-dark color-red1-dark bg-theme w-100">
                        Удалитьс связь полей
                    </button>
                    <div class="divider divider-small my-3 bg-highlight "></div>
                </div>
                <button
                    type="button"
                    @click="addField"
                    class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                    Добавить связь полей
                </button>


                <button type="submit"
                        class="btn btn-m btn-full mt-3 mb-3 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                    <i class="fa-solid fa-cloud-arrow-down mr-1"></i> Сохранить настройку AMO
                </button>

            </form>

            <div class="divider divider-small my-3 bg-highlight "></div>
            <a href="javascript:void(0)"
               @click="syncAmo"
               class="btn btn-m btn-full rounded-s mb-3 rounded-0 text-uppercase font-900 shadow-s bg-green2-dark">
                Синохрозировать
            </a>


        </div>
    </div>


</template>
<script>
import {Vue3JsonEditor} from 'vue3-json-editor'
import {mapGetters} from "vuex";

export default {
    props: ["data", "bot"],
    components: {
        Vue3JsonEditor
    },
    data() {
        return {
            load: false,
            custom_fields: null,
            fields: [
                {
                    title: 'Телефонный номер',
                    field: 'phone',
                },
                {
                    title: 'Ф.И.О.',
                    field: 'fio_from_telegram',
                },
                {
                    title: 'Почта',
                    field: 'email',
                },
                {
                    title: 'Дата рождения',
                    field: 'birthday',
                },
                {
                    title: 'Возраст',
                    field: 'age',
                },
                {
                    title: 'Страна',
                    field: 'country',
                },
                {
                    title: 'Адрес',
                    field: 'address',
                },
                {
                    title: 'Пол',
                    field: 'sex',
                },
                {
                    title: 'Идентификатор чата',
                    field: 'fio_from_telegram',
                },

            ],
            hasConnect: false,
            amoForm: {
                client_id: null,
                client_secret: null,
                auth_code: null,
                redirect_uri: null,
                subdomain: null,
                bot_id: null,
                fields: null,
            },
        }
    },

    mounted() {


        if (this.data)
            this.$nextTick(() => {
                this.amoForm.client_id = this.data.client_id || null
                this.amoForm.client_secret = this.data.client_secret || null
                this.amoForm.auth_code = this.data.auth_code || null
                this.amoForm.redirect_uri = this.data.redirect_uri || null
                this.amoForm.subdomain = this.data.subdomain || null
                this.amoForm.bot_id = this.data.bot_id || this.bot.id || null
                this.amoForm.fields = this.data.fields || null
            })


    },
    methods: {
        loadAmoFields() {
            this.load = true
            this.custom_fields = null
            this.$store.dispatch("loadAmoFields").then((response) => {
                this.custom_fields = response._embedded.custom_fields.contacts || null
                this.$nextTick(() => {
                    this.load = false
                })
                this.$botNotification.success("Работа с AMO", "Справочная информация успешно загружена");
            }).catch(err => {
                this.$botNotification.warning("Работа с AMO", "Ошибка работы со справочной информацией");
            })
        },
        removeField(index){
            if (!this.amoForm.fields)
                return;

            this.amoForm.fields.splice(index, 1)
        },
        addField() {
            if (!this.amoForm.fields)
                this.amoForm.fields = []

            this.amoForm.fields.push({
                key: null,
                field: null,
                enum: null,

            })
        },
        syncAmo() {
            this.$store.dispatch("syncAmo").then((response) => {
                this.$botNotification.success("Работа с AMO", "Данные CRM успешно сохранены");
            }).catch(err => {
                this.$botNotification.warning("Работа с AMO", "Ошибка работы");
            })
        },
        submitAmo() {
            /*    if (!this.hasConnect) {
                    return;
                }*/
            let data = new FormData();
            Object.keys(this.amoForm)
                .forEach(key => {
                    const item = this.amoForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("saveAmoCRM", {
                amoForm: data
            }).then((response) => {
                this.$botNotification.success("Работа с AMO", "Данные CRM успешно сохранены");
            }).catch(err => {

            })


        }
    }
}
</script>
