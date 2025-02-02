<script setup>
/*import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import InlineQueryList from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryList.vue";*/
</script>
<template>

    <div v-if="!editor&&mode===0">
        <Popper>
            <p class="mb-2"><i class="fa-regular fa-circle-question mr-1"></i>Инструкция</p>
            <template #content>
                <div class="text-left w-100 ">
                    <p class="mb-0 text-white"><i class="fa-solid fa-arrow-down"></i> - добавление в нижнюю часть новой
                        строки (кнопки)</p>
                    <p class="mb-0 text-white"><i class="fa-solid fa-arrow-turn-up"></i> - добавление строки над
                        выбранной
                        строкой</p>
                    <p class="mb-0 text-white"><i class="fa-solid fa-arrow-turn-down"></i> - добавление строки под
                        выбранной
                        строкой</p>
                    <p class="mb-0 text-white"><i class="fa-solid fa-plus"></i> - добавление кнопки на строку</p>
                    <p class="mb-0 text-white"><i class="fa-solid fa-minus"></i> - удаление крайней левой кнопки из
                        строки
                        либо самой строки</p>
                    <p class="mb-0 text-white"><i class="fa-solid fa-xmark"></i> - отмена выделения</p>
                </div>

            </template>
        </Popper>


        <div class="mb-2 d-flex">

            <button
                title="добавление в нижнюю часть новой строки (кнопки)"
                type="button"
                class="btn btn-link mb-2 w-100"
                style="font-size:12px;"
                v-if="selectedRow==null"
                @click="addRow"><i class="fa-solid fa-arrow-down"></i> Добавить строку
            </button>
            <button
                type="button"
                title="добавление строки над выбранной строкой"
                class="btn btn-primary mb-2 mr-2"
                v-if="selectedRow!=null"
                @click="addRowAbove"><i class="fa-solid fa-arrow-turn-up"></i>
            </button>
            <button
                type="button"
                title="добавление строки под выбранной строкой"
                class="btn btn-primary mb-2 mr-2"
                v-if="selectedRow!=null"
                @click="addRowBelow"><i class="fa-solid fa-arrow-turn-down"></i>
            </button>


            <button
                type="button"
                class="btn btn-primary mb-2 mr-2"
                v-if="selectedRow!=null"
                @click="moveRow(0)"><i class="fa-solid fa-arrows-up-to-line"></i>
            </button>

            <button
                type="button"
                class="btn btn-primary mb-2 mr-2"
                v-if="selectedRow!=null"
                @click="moveRow(1)"><i class="fa-solid fa-arrows-down-to-line"></i>
            </button>

            <button
                type="button"
                class="btn btn-outline-danger mb-2 mr-2"
                v-if="selectedRow!=null"
                @click="selectedRow=null"><i class="fa-solid fa-xmark"></i>
            </button>


        </div>


        <p v-if="(keyboard||[]).length===0" class="text-danger font-weight-bold p-0 m-0">Элементы клавиатуры еще не
            добавлены</p>

        <div v-if="(keyboard||[]).length>0">
            <div class="row mb-0"

                 v-for="(row, rowIndex) in keyboard">
                <!--            <div class="col-2 d-flex justify-content-around p-2">
                                <button
                                    type="button"
                                    title="добавление кнопки на строку"
                                    class="btn btn-link w-100"
                                    @click="addColToRow(rowIndex)"><i class="fa-solid fa-plus"></i>
                                </button>
                                <button
                                    type="button"
                                    title="удаление крайней левой кнопки из строки либо самой строки"
                                    class="btn btn-link w-100"
                                    @click="removeColFromRow(rowIndex)"><i class="fa-solid fa-minus"></i>
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-link w-100"
                                    @click="moveCol(rowIndex,0)"><i class="fa-solid fa-caret-left"></i>
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-link w-100"
                                    @click="moveCol(rowIndex, 1)"><i class="fa-solid fa-caret-right"></i>
                                </button>

                            </div>-->
                <div class="col-12 d-flex justify-content-center">

                    <div
                        style="margin-right:5px;"
                        class="btn-group dropdown-center w-100 mb-1"
                        @click="selectIndex(rowIndex, colIndex)"
                        v-for="(col, colIndex) in row">
                        <input
                            style="font-size:10px;"
                            type="text"
                            class="btn btn-outline-light text-primary w-100"
                            v-model="keyboard[rowIndex][colIndex].text"
                        />
                        <!--                    <button type="button"
                                                    @click="openKeyboardEditorMenu(rowIndex,colIndex)"
                                                    class="btn btn-outline-primary" aria-expanded="false"
                                                    data-bs-reference="parent">
                                                <i class="fa-solid fa-bars"></i>
                                            </button>-->
                        <div class="dropdown">
                            <button

                                style="border-radius:0 5px 5px 0px;"
                                class="btn btn-outline-light text-primary" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                       @click="openKeyboardEditorMenu(rowIndex,colIndex)"
                                       href="javascript:void(0)">Редактор</a></li>

                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        title="добавление кнопки на строку"
                                        class="dropdown-item"
                                        v-if="selectedRow!=null"
                                        @click="addColToRow()"><i class="fa-solid fa-plus"> </i> Добавить
                                    </a>
                                </li>


                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        title="удаление крайней левой кнопки из строки либо самой строки"
                                        class="dropdown-item"
                                        v-if="selectedRow!=null"
                                        @click="removeColFromRow(colIndex)"><i class="fa-solid fa-minus"> </i> Удалить
                                    </a>
                                </li>

                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        class="dropdown-item"
                                        v-if="selectedRow!=null"
                                        @click="moveCol(rowIndex,0)"><i class="fa-solid fa-caret-left"> </i> Переместить
                                    </a>
                                </li>


                                <li>
                                    <a
                                        href="javascript:void(0)"
                                        class="dropdown-item"
                                        v-if="selectedRow!=null"
                                        @click="moveCol(rowIndex,1)"><i class="fa-solid fa-caret-right"> </i> Переместить
                                    </a>
                                </li>

                                <li @click="moveColVertical(rowIndex,0)"><a class="dropdown-item"
                                                                            href="javascript:void(0)">
                                    <i class="fa-solid fa-caret-up mr-2 "></i> переместить вверх
                                </a></li>
                                <li @click="moveColVertical(rowIndex,1)"><a class="dropdown-item"
                                                                            href="javascript:void(0)">
                                    <i class="fa-solid fa-caret-down mr-2"></i> переместить вниз
                                </a></li>
                            </ul>
                        </div>

                    </div>


                </div>
            </div>


        </div>

        <div class="form-check">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="showCode"
                   :id="'showCode'+uuid">
            <label class="form-check-label" :for="'showCode'+uuid">
                Отобразить код
            </label>
        </div>

        <div class="mb-0" v-if="showCode">
            <label class="form-label" id="bot-domain">JSON-код клавиатуры</label>
            <Vue3JsonEditor
                v-if="!load"
                :mode="'code'"
                v-model="keyboard"
                :show-btns="false"
                :expandedOnStart="true"
                @json-change="onJsonChange"
            />
        </div>
    </div>

    <div v-if="mode===1">
        <div class="row">
            <div class="col-12 mb-2">
                <button type="button"
                        @click="mode=0"
                        class="btn btn-outline-light text-primary">Назад
                </button>
            </div>
            <form class="col-12">
                <div class="alert alert-danger" role="alert">
                    Возможно выбрать только 1 тип действия
                </div>

                <div class="mb-3">

                    <div class="input-group ">

                        <div class="form-floating ">
                            <input
                                type="text"
                                :id="'command-title-'+select.row+'-col-'+select.col"
                                class="form-control border-light"
                                v-model="keyboard[select.row][select.col].text"
                            />
                            <label for="floatingInput">Название кнопки</label>
                        </div>


                        <button type="button"
                                @click="openPageModal"
                                class="btn btn-outline-light" aria-expanded="false">
                            <i class="fa-solid fa-bars"></i>
                        </button>

                    </div>


                </div>


                <div class="form-floating mb-2" v-if="type==='inline'">

                    <input type="text"
                           @change="needRemoveField( 'callback_data', select.row, select.col)"
                           v-model="keyboard[select.row][select.col].callback_data"
                           class="form-control"
                           :id="'command-row-'+select.row+'-col-'+select.col"
                           placeholder="/start">
                    <label :for="'command-row-'+select.row+'-col-'+select.col"
                           class="form-label">Команда (для меню в сообщении)</label>
                </div>
                <div class="form-floating mb-2" v-if="type==='inline'">
                    <input type="text" class="form-control"
                           @change="needRemoveField( 'switch_inline_query',select.row, select.col)"
                           v-model="keyboard[select.row][select.col].switch_inline_query"
                           :id="'switch-inline-query-row-'+select.row+'-col-'+select.col"
                           placeholder="@YourAccountLink">
                    <label :for="'switch-inline-query-row-'+select.row+'-col-'+select.col"
                           class="form-label">Ссылка на аккаунт в ТЕЛЕГРАММ</label>
                </div>
                <div class="form-floating mb-2" v-if="type==='inline'">
                    <input type="text" class="form-control"
                           @change="needRemoveField( 'url',select.row, select.col)"
                           v-model="keyboard[select.row][select.col].url"
                           :id="'url-row-'+select.row+'-col-'+colIndex"
                           placeholder="https://t.me/example">
                    <label :for="'url-row-'+select.row+'-col-'+select.col"
                           class="form-label">Внешняя URL-ссылка</label>
                </div>

                <div class="mb-3" v-if="type==='inline'">

                    <label :for="'switch-inline-query-current-chat-row-'+select.row+'-col-'+select.col"
                           class="form-label">Команда всплывающего меню бота</label>

                    <div class="alert alert-warning" role="alert">
                        Внимание! Режим всплывающего меню должен быть настроен в BotFather-е в разделе редактирования
                        бота InlineMode. Сперва нужно включить данный режим.
                    </div>

                    <div class="input-group mb-3">

                        <input type="text" class="form-control"
                               @change="needRemoveField( 'switch_inline_query_current_chat',select.row, select.col)"
                               v-model="keyboard[select.row][select.col].switch_inline_query_current_chat"
                               :id="'switch-inline-query-current-chat-row-'+select.row+'-col-'+select.col"
                               placeholder="команда">

                        <button type="button"
                                @click="openInlineQueryModal"
                                class="btn btn-outline-primary" aria-expanded="false"><i class="fa-solid fa-bars"></i>
                        </button>

                    </div>

                </div>
                <div class="mb-3" v-if="type==='inline'">

                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_login_url"
                               type="checkbox"
                               id="need-login-url">
                        <label class="form-check-label" for="need-login-url">
                            Добавить ссылку авторизации
                        </label>
                    </div>

                    <div v-if="need_login_url" class="mb-3">
                        <label :for="'login-link-row-'+select.row+'-col-'+select.col"
                               class="form-label">Ссылка авторизации
                        </label>

                        <input type="text" class="form-control"
                               v-model="keyboard[select.row][select.col].login_url.url"
                               :id="'login-link-row-'+select.row+'-col-'+select.col"
                               placeholder="Ссылка авторизации" required>
                    </div>


                    <div v-if="need_login_url" class="mb-3">
                        <label :for="'login-link-forward-text-row-'+select.row+'-col-'+select.col"
                               class="form-label">Новый текст кнопки в пересылаемых сообщениях
                        </label>

                        <input type="text" class="form-control"
                               v-model="keyboard[select.row][select.col].login_url.forward_text"
                               :id="'login-link-forward-text-row-'+select.row+'-col-'+select.col"
                               placeholder="Текст кнопки">
                    </div>

                    <div v-if="need_login_url" class="mb-3">
                        <label :for="'login-link-bot-username-row-'+select.row+'-col-'+select.col"
                               class="form-label">Имя бота, которое будет использоваться для авторизации
                        </label>

                        <input type="text" class="form-control"
                               v-model="keyboard[select.row][select.col].login_url.bot_username"
                               :id="'login-link-bot-username-row-'+select.row+'-col-'+select.col"
                               placeholder="Имя бота">
                    </div>


                    <div class="form-check mb-3" v-if="need_login_url">
                        <input class="form-check-input"
                               v-model="keyboard[select.row][select.col].login_url.request_write_access"
                               type="checkbox"
                               id="need-request_write_access">
                        <label class="form-check-label" for="need-request_write_access">
                            Запросить отправку сообщений ботом
                        </label>
                    </div>
                </div>


                <div class="form-check" v-if="type==='reply'">
                    <input type="radio"
                           @change="needRemoveField( null,select.row, select.col)"
                           name="request-radio"
                           class="form-check-input"
                           :id="'no-action-row-'+select.row+'-col-'+select.col">
                    <label class="form-check-label"
                           :for="'no-action-row-'+select.row+'-col-'+select.col">
                        Без действий
                    </label>
                </div>
                <!--                                                <div class="form-check" v-if="rowIndex===0">
                                                                    <input type="radio"
                                                                           @change="needRemoveField( 'pay',rowIndex, colIndex)"
                                                                           @click="keyboard[rowIndex][colIndex].pay = true"
                                                                           name="request-radio"
                                                                           class="form-check-input"
                                                                           :id="'pay-action-row-'+rowIndex+'-col-'+colIndex">
                                                                    <label class="form-check-label"
                                                                           :for="'pay-action-row-'+rowIndex+'-col-'+colIndex">
                                                                        Кнопка оплаты
                                                                    </label>
                                                                </div>-->
                <div class="form-check" v-if="type==='reply'">
                    <input type="radio"
                           @change="needRemoveField( 'request_contact',select.row, select.col)"
                           @click="keyboard[select.row][select.col].request_contact = true"
                           name="request-radio"
                           :checked="keyboard[select.row][select.col].request_contact"
                           class="form-check-input" :id="'phone-row-'+select.row+'-col-'+select.col">
                    <label class="form-check-label" :for="'phone-row-'+select.row+'-col-'+select.col">
                        Запросить телефон
                    </label>
                </div>
                <div class="form-check" v-if="type==='reply'">
                    <input type="radio"
                           name="request-radio"
                           :checked="keyboard[select.row][select.col].request_location"
                           @change="needRemoveField( 'request_location',select.row, select.col)"
                           @click="keyboard[select.row][select.col].request_location = true"
                           class="form-check-input" :id="'location-row-'+select.row+'-col-'+select.col">
                    <label class="form-check-label"
                           :for="'location-row-'+select.row+'-col-'+select.col">
                        Запросить локацию
                    </label>
                </div>

            </form>
        </div>
        <hr>
    </div>

    <div class="modal fade" :id="'page-list-in-keyboard-'+uuid" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <PagesList
                        v-on:callback="attachPage"
                        :editor="false"/>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" :id="'inline-query-list-in-keyboard-'+uuid" tabindex="-1"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <InlineQueryList v-on:select="selectInlineQuery"></InlineQueryList>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import {Vue3JsonEditor} from 'vue3-json-editor'
import {v4 as uuidv4} from "uuid";

export default {
    props: ["modelValue", "type"],
    components: {
        Vue3JsonEditor
    },
    computed: {
        uuid() {
            return uuidv4();
        }
    },
    watch: {
        settings: {
            handler: function (newValue) {
                this.$emit("update:modelValue", {
                    menu: this.keyboard,
                    settings: this.settings,
                })
            },
            deep: true
        },
        need_login_url: {
            handler: function (newValue) {
                if (this.need_login_url)
                    this.keyboard[this.select.row][this.select.col].login_url = {
                        url: null,
                        forward_text: null,
                        bot_username: null,
                        request_write_access: true,
                    }
                else
                    delete this.keyboard[this.select.row][this.select.col].login_url
            },
            deep: true
        },
        keyboard: {
            handler: function (newValue) {
                this.save()
                this.$emit("update:modelValue", {
                    menu: this.keyboard,
                    settings: this.settings,
                })
            },
            deep: true
        }
    },
    data() {
        return {
            need_input_field_placeholder: false,
            settings: {
                resize_keyboard: true,
                one_time_keyboard: false,
                input_field_placeholder: null,
                is_persistent: false,
            },
            need_login_url: false,
            pageModal: null,
            inlineQueryModal: null,
            mode: 0,
            editor: false,
            showCode: false,
            showAssign: false,
            selectedRow: null,
            load: false,
            rowCount: 1,
            keyboard: [],
            select: {
                row: 0,
                col: 0,
                type: this.type || 'reply'
            }
        }
    },

    mounted() {
        this.pageModal = new bootstrap.Modal(document.getElementById('page-list-in-keyboard-' + this.uuid), {})
        this.inlineQueryModal = new bootstrap.Modal(document.getElementById('inline-query-list-in-keyboard-' + this.uuid), {})


        if (this.modelValue) {
            this.$nextTick(() => {
                this.keyboard = this.modelValue?.menu || []

                if (this.modelValue?.settings) {
                    this.settings = {
                        resize_keyboard: this.modelValue?.settings.resize_keyboard || true,
                        one_time_keyboard: this.modelValue?.settings.one_time_keyboard || false,
                        input_field_placeholder: this.modelValue?.settings.input_field_placeholder || null,
                        is_persistent: this.modelValue?.settings.is_persistent || false,
                    }

                    if (this.settings.input_field_placeholder != null)
                        this.need_input_field_placeholder = true
                }
            })
        }

    },
    methods: {
        selectInlineQuery(query) {
            this.keyboard[this.select.row][this.select.col].switch_inline_query_current_chat = query.command

            this.$notify({
                title: "Конструктор страниц",
                text: "Вы успешно выбрали страницу",
                type: 'success'
            });

            this.inlineQueryModal.hide()
        },
        openInlineQueryModal() {
            this.inlineQueryModal.show()
        },
        openPageModal() {
            this.pageModal.show()
        },

        attachPage(item) {

            let command = (item.slug.command || 'Нет команды').replace(".*", "")

            this.keyboard[this.select.row][this.select.col].text = command


            this.$notify({
                title: "Конструктор страниц",
                text: "Вы успешно выбрали страницу",
                type: 'success'
            });

            if (this.type === 'inline')
                this.keyboard[this.select.row][this.select.col].callback_data = command

        },
        reset() {
            this.selectedRow = null
            this.select = {
                row: -1,
                col: -1,
                type: this.type || 'reply'
            }
        },
        removeSelectedButton() {
            this.keyboard[this.select.row].splice(this.select.col, 1)

            let tmpRow = this.select.row;


            this.reset()

            if (this.keyboard[tmpRow].length === 0)
                this.keyboard.splice(tmpRow, 1)


        },
        needRemoveField(param, rowIndex, colIndex) {
            Object.keys(this.keyboard[rowIndex][colIndex])
                .forEach(item => {
                    if (item !== 'text' && item !== param)
                        delete this.keyboard[rowIndex][colIndex][item]
                })

        },
        moveColVertical(row, direction) {
            /*     if (row !== this.select.row) {
                     this.select.row = row
                     this.select.col = 0
                     this.select.text = this.keyboard[this.select.row][this.select.col].text
                 }
     */
            let maxRows = this.keyboard.length

            let index = direction === 0 ?
                this.select.row - 1 >= 0 ? this.select.row - 1 : maxRows - 1 :
                this.select.row < maxRows - 1 ? this.select.row + 1 : 0

            let tmpItem = this.keyboard[this.select.row][this.select.col]

            this.keyboard[this.select.row].splice(this.select.col, 1)

            console.log(this.keyboard[this.select.row].length, this.select.row)


            this.keyboard[index].push(tmpItem)

            if (this.keyboard[this.select.row].length === 0)
                this.keyboard.splice(this.select.row, 1)

            this.select.row = index
            this.select.col = this.keyboard[index].length - 1
            this.select.text = this.keyboard[index][0].text

            this.selectedRow = index
        },
        moveCol(row, direction = 0) {
            if (row !== this.select.row) {
                this.select.row = row
                this.select.col = 0
                this.select.text = this.keyboard[this.select.row][this.select.col]
            }

            let rowIndex = this.select.row
            let colIndex = this.select.col

            let maxCols = this.keyboard[rowIndex].length

            let index = direction === 0 ?
                colIndex - 1 >= 0 ? colIndex - 1 : maxCols - 1 :
                colIndex < maxCols - 1 ? colIndex + 1 : 0

            let tmpCol = this.keyboard[rowIndex][colIndex]
            this.keyboard[rowIndex][colIndex] = this.keyboard[rowIndex][index]
            this.keyboard[rowIndex][index] = tmpCol

            this.select.row = rowIndex
            this.select.col = index
            this.select.text = this.keyboard[rowIndex][index].text

        },
        moveRow(direction = 0) {
            if (this.selectedRow == null)
                return;

            let maxRows = this.keyboard.length

            let index = direction === 0 ?
                this.selectedRow - 1 >= 0 ? this.selectedRow - 1 : maxRows - 1 :
                this.selectedRow < maxRows - 1 ? this.selectedRow + 1 : 0

            let tmpRow = this.keyboard[this.selectedRow]

            this.keyboard[this.selectedRow] = this.keyboard[index]
            this.keyboard[index] = tmpRow

            this.select.row = index
            this.select.col = 0
            this.select.text = this.keyboard[index][0].text

            this.selectedRow = index

        },

        openKeyboardEditorMenu(rowIndex, colIndex) {
            this.mode = 1
        },
        save() {
            this.$emit("save", this.keyboard)
            if (this.type === 'reply')
                this.$emit("save-settings", this.settings)
        },
        onJsonChange(value) {
            this.keyboard = value
            this.save();

        },
        removeColFromRow(index) {

            if (this.keyboard[index].length > 1)
                this.keyboard[index].splice(this.keyboard[index].length - 1, 1)
            else
                this.keyboard.splice(index, 1)

            if (this.keyboard.length === 0)
                this.selectedRow = null;
            this.save();
        },
        addRowAbove() {
            this.addRow(true)
        },
        addRowBelow() {
            this.addRow(false)
        },
        addRow(above = false) {

            if (this.selectedRow == null) {
                if (this.keyboard)
                    this.keyboard.push([{
                        text: "No Text"
                    }])
                else
                    this.keyboard = [[{
                        text: "No Text"
                    }]]

                this.selectedRow = null
            } else {
                let index = !above ? this.selectedRow + 1 : this.selectedRow
                this.keyboard.splice(index, 0, [{
                    text: "No Text"
                }])
            }

            this.save();
        },
        addColToRow() {
            let index = this.selectedRow
            this.keyboard[index].push({
                text: "No Text"
            })


            this.save();
        },
        selectIndex(rowIndex, colIndex) {

            this.selectedRow = rowIndex

            this.select.row = rowIndex
            this.select.col = colIndex

            if (this.keyboard[rowIndex])
                this.select.text = this.keyboard[rowIndex][colIndex].text

            this.load = true
            this.$nextTick(() => {
                this.load = false
            })
        },
        removeCol(rowIndex, colIndex) {
            if (this.keyboard[rowIndex].length > 1)
                this.keyboard[rowIndex].splice(colIndex, 1)
            else
                this.keyboard.splice(1, 1)


            this.save();
        }
    }
}
</script>
<style lang="scss">

.has-script {

    background-color: rgba(173, 216, 230, 0.30);

}

</style>
