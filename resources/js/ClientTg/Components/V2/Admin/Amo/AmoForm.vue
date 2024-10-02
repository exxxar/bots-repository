<template>

    <h3>Настройка AMO</h3>

    <form
        v-on:submit.prevent="submitAmo">

        <p class="mb-2 alert alert-light"><a target="_blank" href="https://www.amocrm.ru/developers/content/oauth/step-by-step">Документация
            для
            разработчика </a> по шагам</p>

        <div class="form-floating mb-2">
            <input type="text" class="form-control"
                   placeholder="client_id"
                   aria-label="client_id"
                   v-model="amoForm.client_id"
                   aria-describedby="client_id" required>
            <label id="client_id">
                ID интеграции
            </label>
        </div>
        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   placeholder="client_secret"
                   aria-label="client_secret"
                   v-model="amoForm.client_secret"
                   aria-describedby="client_secret" required>
            <label id="client_secret">
                Секретный ключ клиента
            </label>
        </div>
        <div class="form-floating mb-2">

                    <textarea class="form-control font-12"
                              style="min-height:250px;"
                              placeholder="auth_code"
                              aria-label="auth_code"
                              v-model="amoForm.auth_code"
                              aria-describedby="auth_code" required>
                                    </textarea>

            <label id="auth_code">
                Полученный код авторизации
            </label>
        </div>
        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   placeholder="subdomain"
                   aria-label="subdomain"
                   v-model="amoForm.subdomain"
                   aria-describedby="subdomain" required>
            <label id="subdomain2">
                Ваш поддомен в системе AMO CRM
            </label>
        </div>

        <button
            type="button"
            :disabled="!amoForm.client_id||!amoForm.auth_code||!amoForm.client_secret||!amoForm.subdomain"
            @click="loadAmoFields"
            class="btn w-100 btn-outline-light  text-primary p-3 mb-2">
            Загрузить справочную информацию
        </button>

        <Vue3JsonEditor
            v-if="!load&&custom_fields"
            :mode="'tree'"
            v-model="custom_fields"
            :show-btns="false"
            :expandedOnStart="false"
        />

        <template  v-if="amoForm.fields"
                   v-for="(field, index) in amoForm.fields">
            <div class="card mb-2">

                <div class="card-body">
                    <div class="form-floating mb-2">
                        <input type="number" class="form-control"
                               placeholder="Ключ"
                               aria-label="Ключ"
                               v-model="amoForm.fields[index].key"
                               aria-describedby="Ключ" required>
                        <label :id="'field-key-'+index">
                            Ключевое значение из справочника
                        </label>
                    </div>

                    <div class="form-floating mb-2">


                        <select class="form-control font-12"
                                v-model="amoForm.fields[index].field">
                            <option
                                v-for="item in fields"
                                :value="item.field">{{ item.title || 'Ошибка' }}
                            </option>
                        </select>

                        <label :id="'field-field-'+index">
                            Поле из списка для связи
                        </label>
                    </div>

                    <div class="form-floating mb-2">

                        <input type="text" class="form-control"
                               placeholder="Перечисление"
                               aria-label="Перечисление"
                               v-model="amoForm.fields[index].enum"
                               aria-describedby="Перечисление">
                        <label class="form-label d-flex justify-content-between mt-0 mb-0" :id="'field-enum-'+index">
                            Перечисление (enum) из справочника

                        </label>
                    </div>
                    <button
                        type="button"
                        @click="removeField(index)"
                        class="btn btn-link p-3 w-100">
                        <i class="fa-solid fa-trash-can mr-2"></i> Удалить связь полей (#{{index+1}})
                    </button>
                </div>

            </div>
        </template>

        <button
            type="button"
            @click="addField"
            class="btn w-100 btn-outline-info p-3">
            Добавить связь полей
        </button>


        <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
             style="border-radius:10px 10px 0px 0px;">

            <button type="submit"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-cloud-arrow-down mr-2"></i> Сохранить настройку AMO
            </button>
        </nav>



    </form>

    <hr class="my-3 p-0">

    <a href="javascript:void(0)"
       @click="syncAmo"
       class="btn btn-outline-primary w-100 p-3">
        <i class="fa-solid fa-rotate mr-2"></i> Синхронизировать
    </a>

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
                this.$notify({
                    title:"Работа с AMO",
                    text:"Справочная информация успешно загружена",
                    type:"success",
                });
            }).catch(err => {

                this.$notify({
                    title:"Работа с AMO",
                    text:"Ошибка работы со справочной информацией",
                    type:"error",
                });

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

                this.$notify({
                    title:"Работа с AMO",
                    text:"Данные CRM успешно сохранены",
                    type:"success",
                });
            }).catch(err => {
                this.$notify({
                    title:"Работа с AMO",
                    text:"Ошибка работы",
                    type:"error",
                });
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
                this.$notify({
                    title:"Работа с AMO",
                    text:"Данные CRM успешно сохранены",
                    type:"success",
                });
            }).catch(err => {
                this.$notify({
                    title:"Работа с AMO",
                    text:"Ошибка работы",
                    type:"error",
                });
            })


        }
    }
}
</script>
