<script setup>
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import InlineQueryList from "@/AdminPanel/Components/Constructor/InlineQuery/InlineQueryList.vue";
</script>
<template>

    <div v-if="!editor&&mode===0">
        <div class="row">
            <div class="col-12 d-flex justify-content-between">
                <div>
                    <button
                        type="button"
                        class="btn btn-primary mb-2"
                        v-if="selectedRow==null"
                        @click="addRow">Добавить строку
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary mb-2"
                        v-if="selectedRow!=null"
                        @click="addRowAbove">Добавить строку выше
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary mb-2 ml-2"
                        v-if="selectedRow!=null"
                        @click="addRowBelow">Добавить строку ниже
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary mb-2 ml-2"
                        v-if="selectedRow!=null"
                        @click="moveRow(0)"><i class="fa-solid fa-arrows-up-to-line"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary mb-2 ml-2"
                        v-if="selectedRow!=null"
                        @click="moveRow(1)"><i class="fa-solid fa-arrows-down-to-line"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-outline-danger mb-2 ml-2"
                        v-if="selectedRow!=null"
                        @click="reset"><i class="fa-solid fa-xmark"></i>
                    </button>
                </div>


                <div class="d-flex flex-column">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               v-model="showCode"
                               :id="'showCode'+uuid">
                        <label class="form-check-label" :for="'showCode'+uuid">
                            Отобразить код
                        </label>
                    </div>


                </div>


            </div>
            <div class="col-12" v-if="(keyboard||[]).length>0">
                <div class="row" v-for="(row, rowIndex) in keyboard">

                    <div class="col-12 d-flex justify-content-center p-1">

                        <div class="btn-group dropdown-center w-100 m-1 "
                             @click="selectIndex(rowIndex, colIndex)"
                             v-for="(col, colIndex) in row">
                            <input
                                type="text"
                                v-bind:class="{'btn-outline-primary':select.row!=rowIndex||select.col!=colIndex,'btn-primary':select.row==rowIndex&&select.col==colIndex}"
                                class="btn  w-100"
                                v-model="keyboard[rowIndex][colIndex].text"
                            />
                            <div class="dropdown">
                                <a class="btn btn-outline-primary rounded-0" href="javascript:void(0)" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li @click="addColToRow(rowIndex)">
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fa-solid fa-plus mr-2"></i> добавить кнопку
                                        </a>
                                    </li>
                                    <li @click="removeSelectedButton">
                                        <a class="dropdown-item" href="javascript:void(0)">
                                            <i class="fa-solid fa-minus mr-2"></i> удалить кнопку
                                        </a>
                                    </li>
                                    <li @click="moveCol(rowIndex,0)"><a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa-solid fa-caret-left mr-2"></i> переместить влево
                                    </a></li>
                                    <li @click="moveCol(rowIndex,1)"><a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa-solid fa-caret-right mr-2"></i> переместить вправо
                                    </a></li>
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
                            <button type="button"
                                    @click="mode=1"
                                    class="btn btn-outline-primary" aria-expanded="false">
                                <i class="fa-solid fa-bars"></i>
                            </button>


                        </div>


                    </div>
                </div>
            </div>

        </div>

        <div class="mb-3" v-if="type==='reply'">


            <div class="form-check">
                <input class="form-check-input"
                       v-model="settings.is_persistent"
                       type="checkbox"
                       id="need-is_persistent">
                <label class="form-check-label" for="need-is_persistent">
                    Скрывать клавиатуру автоматически
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       v-model="settings.one_time_keyboard"
                       type="checkbox"
                       id="need-one_time_keyboard">
                <label class="form-check-label" for="need-one_time_keyboard">
                    Клавиатура показывается только 1 раз
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       v-model="settings.resize_keyboard"
                       type="checkbox"
                       id="need-resize_keyboard">
                <label class="form-check-label" for="need-resize_keyboard">
                    Клавиши масштабируются
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_input_field_placeholder"
                       type="checkbox"
                       id="need_input_field_placeholder">
                <label class="form-check-label" for="need_input_field_placeholder">
                    Нужна подсказка к клавиатуре
                </label>
            </div>

            <div class="form-floating mb-3" v-if="need_input_field_placeholder">
                <input type="text"
                       v-model="settings.input_field_placeholder"
                       class="form-control" id="input_field_placeholder" placeholder="Клавиатура">
                <label for="input_field_placeholder">Подсказка к клавиатуре</label>
            </div>

        </div>

        <div class="row" v-if="showCode">
            <div class="col-12">

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
    </div>

    <div v-if="mode===1">
        <div class="row">
            <div class="col-12 mb-2">
                <button type="button"
                        @click="mode=0"
                        class="btn btn-outline-primary">Назад
                </button>
            </div>
            <form class="col-12">
                <div class="alert alert-danger" role="alert">
                    Возможно выбрать только 1 тип действия
                </div>

                <div class="mb-3">

                    <label :for="'command-title-'+select.row+'-col-'+select.сol"
                           class="form-label">Название кнопки</label>
                    <div class="input-group">


                        <input
                            type="text"
                            :id="'command-title-'+select.row+'-col-'+select.col"
                            class="form-control"
                            v-model="keyboard[select.row][select.col].text"
                        />

                        <button type="button"
                                @click="openPageModal"
                                class="btn btn-outline-primary" aria-expanded="false">
                            <i class="fa-solid fa-bars"></i>
                        </button>

                    </div>


                </div>
                <hr>

                <div class="mb-3" v-if="type==='inline'">
                    <label :for="'command-row-'+select.row+'-col-'+select.col"
                           class="form-label">Команда (для меню в сообщении)</label>
                    <input type="text"
                           @change="needRemoveField( 'callback_data', select.row, select.col)"
                           v-model="keyboard[select.row][select.col].callback_data"
                           class="form-control"
                           :id="'command-row-'+select.row+'-col-'+select.col"
                           placeholder="/start">
                </div>
                <div class="mb-3" v-if="type==='inline'">
                    <label :for="'switch-inline-query-row-'+select.row+'-col-'+select.col"
                           class="form-label">Ссылка на аккаунт в ТЕЛЕГРАММ</label>
                    <input type="text" class="form-control"
                           @change="needRemoveField( 'switch_inline_query',select.row, select.col)"
                           v-model="keyboard[select.row][select.col].switch_inline_query"
                           :id="'switch-inline-query-row-'+select.row+'-col-'+select.col"
                           placeholder="@YourAccountLink">
                </div>
                <div class="mb-3" v-if="type==='inline'">
                    <label :for="'url-row-'+select.row+'-col-'+select.col"
                           class="form-label">Внешняя URL-ссылка</label>
                    <input type="text" class="form-control"
                           @change="needRemoveField( 'url',select.row, select.col)"
                           v-model="keyboard[select.row][select.col].url"
                           :id="'url-row-'+select.row+'-col-'+colIndex"
                           placeholder="https://t.me/example">
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
    <!-- Modal -->
</template>
<script>
import {Vue3JsonEditor} from 'vue3-json-editor'
import {v4 as uuidv4} from "uuid";

export default {
    props: ["editedKeyboard", "type"],

    components: {
        Vue3JsonEditor
    },
    computed: {
        uuid() {
            const data = uuidv4();
            return data
        }
    },
    watch: {
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


        if (this.editedKeyboard) {
            this.$nextTick(() => {
                this.keyboard = this.editedKeyboard.menu
                this.settings = {
                    resize_keyboard: this.editedKeyboard.settings.resize_keyboard || true,
                    one_time_keyboard: this.editedKeyboard.settings.one_time_keyboard || false,
                    input_field_placeholder: this.editedKeyboard.settings.input_field_placeholder || null,
                    is_persistent: this.editedKeyboard.settings.is_persistent || false,
                }

                if (this.settings.input_field_placeholder != null)
                    this.need_input_field_placeholder = true
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
        addRowBelow() {
            this.addRow(false)
        },

        addRow(above = false) {

            if (this.selectedRow == null) {
                if (this.keyboard)
                    this.keyboard.push([{
                        text: "Нет команды"
                    }])
                else
                    this.keyboard = [[{
                        text: "Нет команды"
                    }]]

                this.selectedRow = null
            } else {
                let index = !above ? this.selectedRow + 1 : this.selectedRow
                this.keyboard.splice(index, 0, [{
                    text: "Нет команды"
                }])
            }

            this.save();
        },

        addColToRow(index) {
            this.keyboard[index].push({
                text: "Нет команды"
            })


            this.save();
        },
        selectIndex(rowIndex, colIndex) {

            this.selectedRow = rowIndex

            this.select.row = rowIndex
            this.select.col = colIndex

            if (this.keyboard[rowIndex])
                if (this.keyboard[rowIndex][colIndex])
                    this.select.text = this.keyboard[rowIndex][colIndex].text || ''

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
