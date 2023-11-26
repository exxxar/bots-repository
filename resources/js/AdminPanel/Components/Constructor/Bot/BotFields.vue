<template>
    <div class="mb-2">

        <div class="d-flex justify-content-between">
            <h6>Настройка полей пользователя бота</h6>

            <button
                type="button"
                @click="addField()"
                class="btn mb-2 rounded-sm btn-outline-info">
                <i class="fa-solid fa-plus mr-2"></i> Добавить параметр
            </button>
        </div>
        <p><em>Данные поля будут добавлены пользователю бота и будут автоматически учитываться в условиях страниц,
            скриптах (в формах заполнения данных), диалогах. Ключи должны быть уникальные для каждого бота.
            Если ключ дублируется, то будет использовано более позднее его значение. Если поле добавлено и сохранено, то
            удалить его уже нельзя, только скрыть. </em>.
            <br>
            <span class="text-danger">*</span> - обязательное поле
        </p>

        <form v-on:submit.prevent="submit">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ключ<span class="text-danger">*</span></th>
                    <th scope="col">Подпись поля<span class="text-danger">*</span></th>
                    <th scope="col">Тип поля<span class="text-danger">*</span></th>
                    <th scope="col">Паттерн</th>
                    <th scope="col">Обязательный</th>
                    <th scope="col">Вопрос для анкеты<span class="text-danger">*</span></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in fieldForm.fields">
                    <th scope="row">{{ index + 1 }}</th>
                    <th scope="col">
                        <input type="text" class="form-control mb-2 w-100"
                               placeholder="Ключ"
                               aria-label="Ключ"
                               maxlength="255"
                               v-model="fieldForm.fields[index].key"
                               required>
                    </th>
                    <th scope="col">
                        <input type="text" class="form-control mb-2 w-100"
                               placeholder="Подпись поля"
                               aria-label="Название поля"
                               maxlength="255"
                               v-model="fieldForm.fields[index].label"
                               required>
                    </th>
                    <th scope="col">
                        <select class="form-select" v-model="fieldForm.fields[index].type">
                            <option :value="type.value" v-for="type in fieldTypes">{{
                                    type.title || 'Не указан'
                                }}
                            </option>
                        </select>
                    </th>
                    <th scope="col">
                        <input type="text" class="form-control mb-2 w-100"
                               placeholder="Паттерн"
                               aria-label="Паттерн"
                               maxlength="255"
                               :disabled="fieldForm.fields[index].type===2"
                               v-model="fieldForm.fields[index].validate_pattern"
                        >
                    </th>
                    <th scope="col">
                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="fieldForm.fields[index].required"
                                   type="checkbox" value="" :id="'field-required'+index">
                            <label class="form-check-label" :for="'field-required'+index">
                                <span v-if="fieldForm.fields[index].required">Да</span>
                                <span v-if="!fieldForm.fields[index].required">Нет</span>
                            </label>
                        </div>
                    </th>
                    <th scope="col">
                        <input type="text" class="form-control mb-2 w-100"
                               placeholder="Описание вопроса"
                               aria-label="Описание вопроса"
                               maxlength="255"
                               v-model="fieldForm.fields[index].description"
                               required>
                    </th>
                    <th scope="col">
                        <button
                            v-if="!fieldForm.fields[index].id"
                            type="button"
                            @click="removeField(index)"
                            class="btn btn-link text-danger"><i class="fa-regular fa-trash-can"></i>
                        </button>
                        <div class="form-check mt-2" v-else>
                            <input class="form-check-input d-none"
                                   v-model="fieldForm.fields[index].is_active"
                                   type="checkbox" value="" :id="'field-is_active-'+index">
                            <label class="form-check-label" :for="'field-is_active-'+index">
                                <span v-if="fieldForm.fields[index].is_active"><i
                                    class="fa-solid fa-eye text-success"></i></span>
                                <span v-if="!fieldForm.fields[index].is_active"><i
                                    class="fa-solid fa-eye-slash text-danger"></i></span>
                            </label>
                        </div>
                    </th>
                </tr>

                </tbody>
            </table>

            <button
                type="submit" class="btn btn-success w-100 p-3">
                Сохранить поля
            </button>
        </form>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            load: false,
            bot: null,
            fieldTypes: [
                {
                    title: 'Текст',
                    value: 0,
                },
                {
                    title: 'Число',
                    value: 1,
                },
                {
                    title: 'Логический',
                    value: 2,
                },
                {
                    title: 'Дата и время',
                    value: 3,
                }
            ],
            fieldForm: {
                fields: []
            }
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot().then(() => {
            this.bot = this.getCurrentBot

            this.$nextTick(() => {
                this.fieldForm.fields = this.bot.field_settings || []
            })

            this.loadCurrentBotFields()
        })
    },
    methods: {
        loadCurrentBotFields() {
            return this.$store.dispatch("loadCurrentBotFields", {
                bot_id: this.bot.id
            }).then((response) => {
                this.fieldForm.fields = response.data || []
            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        removeField(index) {
            this.fieldForm.fields.splice(index, 1)
        },
        submit() {

            let data = new FormData();
            Object.keys(this.fieldForm)
                .forEach(key => {
                    const item = this.fieldForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append("bot_id", this.bot.id)
            this.$store.dispatch("storeBotFields", {
                dataObject: data
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Поля успешно обновлены",
                    type: 'success'
                });

                this.fieldForm.fields = []

                this.fieldForm.fields = response.data
            }).catch(err => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка обновления полей",
                    type: 'error'
                });
            })

            //
        },
        addField() {
            if (!this.fieldForm.fields)
                this.fieldForm.fields = []

            if (this.fieldForm.fields.length > 0) {
                let hasEmptyKey = this.fieldForm.fields.findIndex(item => item.key == null) !== -1


                if (hasEmptyKey) {
                    this.$notify({
                        title: "Настраиваемые поля",
                        text: "У вас есть пустые ключевые поля!",
                        type: 'error'
                    });
                    return
                }

            }

            this.fieldForm.fields.push({
                key: null,
                type: 0,
                label: null,
                description: null,
                required: false,
                validate_pattern: null,
            })
        }
    }
}
</script>
