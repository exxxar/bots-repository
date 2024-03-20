<script setup>

import InlineQueryItemForm from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryItemForm.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Название оказываемой услуги">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Название команды

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Команда"
                       aria-label="Команда"
                       v-model="inlineQueryForm.command"
                       maxlength="255"
                       aria-describedby="service-title" required>
            </div>


            <div class="col-12 mb-3">

                <label class="form-label " id="service-description">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация о команде
                            </div>
                        </template>
                    </Popper>
                    Описание команду
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="inlineQueryForm.description">
                        Длина текста {{ inlineQueryForm.description.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание команды"
                          aria-label="Описание команды"
                          maxlength="255"
                          v-model="inlineQueryForm.description"
                          aria-describedby="service-description" required>
                    </textarea>

            </div>

            <div class="col-12 mb-2">


                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-primary" @click="openModal">
                    Добавление пункта меню
                </button>
            </div>

            <div v-if="inlineQueryForm.items.length>0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Название</th>
                            <th scope="col">Описание</th>
                            <th scope="col">Текст</th>
                            <th scope="col">Есть клавиатура</th>
                            <th scope="col">Есть доп настройки</th>
                            <th scope="col">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in inlineQueryForm.items">
                            <td @click="selectInlineQueryItem(item)">{{ item.title || '-' }}</td>
                            <td @click="selectInlineQueryItem(item)">{{ item.description || '-' }}</td>
                            <td @click="selectInlineQueryItem(item)">{{ item.input_message_content || '-' }}</td>
                            <td>
                                <i class="fa-solid fa-check text-success"
                                   v-if="item.inline_keyboard"></i>
                                <i class="fa-solid fa-xmark text-danger" v-else></i>
                            </td>
                            <td>
                                <i class="fa-solid fa-check text-success"
                                   v-if="(item.custom_settings||[]).length > 0"></i>
                                <i class="fa-solid fa-xmark text-danger" v-else></i>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-link" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="fa-solid fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                               @click="removeInlineQueryItem(index)"
                                               href="#">Удалить</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="inlineQueryForm.id===null">Создать встроенное меню</span>
                    <span v-else>Обновить встроенное меню</span>
                </button>
            </div>
        </div>
    </form>


    <!-- Modal -->
    <div class="modal fade" id="inline-query-item-editor" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <InlineQueryItemForm
                        v-if="!load"
                        :item="selectedInlineQueryItem"
                        v-on:callback="addInlineQueryItem"></InlineQueryItemForm>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
export default {
    props: ["bot", "inlineQuery"],
    data() {
        return {
            load: false,
            selectedInlineQueryItem: null,
            inlineQueryModal: null,
            inlineQueryForm: {
                id: null,
                command: null,
                description: null,
                items: []
            }
        }
    },

    watch: {
        inlineQueryForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {

        this.inlineQueryModal = new bootstrap.Modal(document.getElementById('inline-query-item-editor'), {})

        if (this.inlineQuery)
            this.$nextTick(() => {
                this.inlineQueryForm = {
                    id: this.inlineQuery.id || null,
                    command: this.inlineQuery.command || null,
                    description: this.inlineQuery.description || null,
                    items: this.inlineQuery.items || [],
                }
            })

    },
    methods: {
        removeInlineQueryItem(index) {
            const item = this.inlineQueryForm.items[index]

            this.inlineQueryForm.items.splice(index, 1)

            this.$store.dispatch("removeInlineQueryItem",
                {
                    queryItemId: item.id
                }).then((response) => {

                this.$notify("Команда успешно удалена");
            }).catch(err => {
                this.$notify("Ошибка удаления команды");
            })
        },
        openModal() {
            this.load = true

            this.$nextTick(() => {
                this.selectedInlineQueryItem = null
                this.load = false
                this.inlineQueryModal.show();
            })
        },
        addInlineQueryItem(item) {

            let index = this.inlineQueryForm.items.findIndex(queryItem => queryItem.id === item.id)

            if (index === -1)
                this.inlineQueryForm.items.push(item)
            else
                this.inlineQueryForm.items[index] = item
        },
        selectInlineQueryItem(item) {
            this.load = true

            this.$nextTick(() => {

                this.selectedInlineQueryItem = item

                this.load = false
                this.inlineQueryModal.show();
            })
        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.inlineQueryForm)
                .forEach(key => {
                    const item = this.inlineQueryForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);

            this.$store.dispatch("storeInlineQuery",
                {
                    inlineQueryForm: data
                }).then((response) => {

                this.inlineQueryForm = {
                    id: null,
                    command: null,
                    description: null,
                    items: []
                }

                this.$emit("callback", response.data)

                this.$notify("Команда успешно создана");
            }).catch(err => {
                this.$notify("Ошибка создания команды");

                this.$emit("callback", response.data)
            })

        },

    }
}
</script>


