<template>
    <div class="card" @click="selectSlug">
        <div class="card-header d-flex justify-content-between">
            <div>
                <button
                    data-bs-toggle="modal" :data-bs-target="'#edit-slug-'+item.id"
                    type="button"
                    class="btn btn-outline-success mr-2"
                >
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>

                <button
                    @click="duplicateSlug"
                    type="button"
                    class="btn btn-outline-success mr-2"
                >
                    <i class="fa-solid fa-clone"></i>
                </button>
                <button
                    @click="removeSlug"
                    type="button"
                    class="btn btn-outline-danger"
                >
                    <i class="fa-regular fa-trash-can"></i>
                </button>

                <button
                    type="button"
                    data-bs-toggle="modal" :data-bs-target="'#attach-command-'+item.id"
                    class="btn btn-outline-primary ml-2">
                    <i class="fa-solid fa-link"></i>
                </button>
            </div>


            <button
                type="button"
                @click="simple=!simple"
                class="btn btn-outline-primary ml-2"

            >
                <i v-if="simple" class="fa-solid fa-arrow-up-short-wide"></i>
                <i v-else class="fa-solid fa-arrow-down-short-wide"></i>
            </button>


        </div>
        <div class="card-body">
            <div class="row">

                <div

                    class="col-12">
                    <p v-if="simple"> {{ item.command || 'Нет команды' }}</p>
                    <ol v-else class="list-group list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Команда</div>
                                {{ item.command || 'Нет команды' }}
                            </div>
                        </li>


                        <li
                            v-if="item.bot_dialog_command_id"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Данная команда начинает диалог</div>
                            </div>
                            <span class="badge bg-primary rounded-pill"> #{{ item.bot_dialog_command_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Мнемоническое имя</div>
                                {{ item.slug || 'Slug' }}
                            </div>

                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Пояснение</div>
                                {{ item.comment || 'Пояснение не указано' }}
                            </div>

                        </li>
                        <li
                            v-if="item.config"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Набор параметров скрипта</div>
                                {{ item.config }}
                            </div>
                        </li>
                        <li
                            v-if="item.page"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Привязана страница</div>
                            </div>

                        </li>
                        <li
                            v-if="item.is_global"
                            class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Глобальный скрипт</div>
                            </div>

                        </li>
                    </ol>


                </div>

            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" :id="'attach-command-'+item.id" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Привязываем старт диалога к команде</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogGroupListSimple
                        v-if="bot"
                        v-on:select-dialog="selectDialog"
                        :bot-id="bot.id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" :id="'edit-slug-'+item.id" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Редактирование команды #<span>{{ item.id }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form v-on:submit.prevent="submit">
                        <div class="form-floating mb-3">
                            <input type="text"
                                   v-model="slugForm.command"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Команда</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   v-model="slugForm.slug"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Мнемоническое имя</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   v-model="slugForm.comment"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Описание скрипта</label>
                        </div>


                        <div class="form-check" v-if="item.is_global">
                            <input class="form-check-input"
                                   v-model="slugForm.is_global"
                                   type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                Является глобальным
                            </label>
                        </div>

                        <div class="card" v-if="!item.is_global">
                            <div class="card-header">
                                Параметры скрипта
                            </div>
                            <div class="card-body">

                                <div class="alert alert-info" role="alert">
                                    Данные параметры используются для настройки скриптов на стороне сервера
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-outline-info w-100 dropdown-toggle mb-2" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Добавить
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li v-for="(item, index) in configTypes">
                                            <a class="dropdown-item"
                                               @click="addConfig(item.type)">{{ item.title || 'Не установлен' }}</a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="row"
                                     v-if="slugForm.config.length>0"
                                     v-for="(item, index) in slugForm.config">
                                    <div class="col-md-5">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control"
                                                   v-model="slugForm.config[index].key"
                                                   id="floatingInput"
                                                   placeholder="name@example.com" required>
                                            <label for="floatingInput">Ключ</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5" v-if="slugForm.config[index].type==='text'">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput"
                                                   v-model="slugForm.config[index].value"
                                                   placeholder="name@example.com" required>
                                            <label for="floatingInput">Значение</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5" v-if="slugForm.config[index].type==='coords'">
                                        <p>Координаты</p>
                                    </div>
                                    <div class="col-md-5" v-if="slugForm.config[index].type==='image'">
                                        <label :for="'param-photo-'+index+'-item-'+item.id" style="margin-right: 10px;"
                                               class="photo-loader ml-2">
                                            <span>+ </span>
                                            <span
                                                v-if="slugForm.config[index].value">{{
                                                    slugForm.config[index].value
                                                }}</span>
                                            <input type="file" :id="'param-photo-'+index+'-item-'+item.id"
                                                   accept="image/*"
                                                   @change="onChangePhotos($event, index)"
                                                   style="display:none;"/>

                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <button
                                            @click="removeConfigItem(index)"
                                            class="btn btn-outline-info w-100 p-3" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row" v-else>
                                    <div class="col-12">
                                        <p>Параметры скрипта еще не добавлены</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-outline-primary w-100 mt-2 p-3">Сохранить команду</button>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: ["item", "bot"],
    data() {
        return {
            simple: true,
            configTypes: [
                {
                    title: 'Текстовый или числовой параметр',
                    type: 'text',
                },
                /*  {
                      title: 'Изображение',
                      type: 'image',
                  },
                  {
                      title: 'Координаты',
                      type: 'coords',
                  }*/
            ],
            slugForm: {
                bot_id: null,
                id: null,
                command: null,
                comment: null,
                slug: null,
                config: [],
                is_global: false,
                bot_dialog_command_id: null,
            }
        }
    },

    mounted() {
        this.slugForm.bot_id = this.bot.id
        this.slugForm.id = this.item.id ?? null
        this.slugForm.command = this.item.command
        this.slugForm.comment = this.item.comment
        this.slugForm.slug = this.item.slug
        this.slugForm.config = this.item.config ?? []
        this.slugForm.is_global = this.item.is_global ?? false
        this.slugForm.bot_dialog_command_id = this.item.bot_dialog_command_id
    },

    methods: {
        onChangePhotos(e, index) {
            const files = e.target.files
            this.slugForm.config[index].value = files[0]
        },
        submit() {
            let data = new FormData();
            Object.keys(this.slugForm)
                .forEach(key => {
                    const item = this.slugForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("updateSlug", {
                slugForm: data
            }).then((response) => {

                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно обновлена",
                    type: 'success'
                });

            }).catch(err => {

            })

        },
        addConfig(type) {
            this.slugForm.config.push({
                key: null,
                value: null,
                type: type || 'text'
            })
        },
        selectDialog(command) {
            this.$store.dispatch("attachDialogCommandToSlug", {
                dataObject: {
                    dialogCommandId: command.id,
                    slugId: this.slugForm.id
                }
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно связана!",
                    type: 'success'
                });

                this.slugs.forEach(item => {
                    if (item.id === this.slugForm.id)
                        item.bot_dialog_command_id = command.id
                })

                this.slugForm = {
                    id: null,
                    command: null,
                    comment: null,
                    slug: null,
                    bot_dialog_command_id: null
                }

            }).catch(err => {

            })

        },
        editSlug() {

        },
        selectSlug() {
            this.$emit("select", this.item)
        },
        duplicateSlug() {
            // this.$emit("duplicate", index)
            this.$store.dispatch("duplicateSlug", {
                dataObject: {
                    slugId: this.slugForm.id
                }
            }).then((response) => {

                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно продублирована",
                    type: 'success'
                });
                this.$emit("callback")
            }).catch(err => {

            })

        },
        removeSlug() {
            //

            this.$store.dispatch("removeSlug", {
                dataObject: {
                    slugId: this.slugForm.id
                }
            }).then((response) => {

                this.$notify({
                    title: "Конструктор команд",
                    text: "Команда успешно удалена",
                    type: 'success'
                });
                this.$emit("callback")
            }).catch(err => {

            })
        },
        removeConfigItem(index) {
            try {
                this.slugForm.config.splice(index, 1)
            } catch (e) {
                this.slugForm.config = []
            }

        },

    }
}
</script>
