<script setup>
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
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
                    <div class="col-2 d-flex justify-content-around p-2">
                        <button
                            type="button"
                            class="btn btn-link w-100"
                            @click="addColToRow(rowIndex)"><i class="fa-solid fa-plus"></i>
                        </button>
                        <button
                            type="button"
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
                            @click="moveCol(rowIndex,1)"><i class="fa-solid fa-caret-right"></i>
                        </button>
                    </div>
                    <div class="col-10 d-flex justify-content-center p-1">

                        <div class="btn-group dropdown-center w-100 m-1 "
                             @click="selectIndex(rowIndex, colIndex)"
                             v-for="(col, colIndex) in row">
                            <input
                                type="text"
                                v-bind:class="{'btn-outline-primary':select.row!=rowIndex||select.col!=colIndex,'btn-primary':select.row==rowIndex&&select.col==colIndex}"
                                class="btn  w-100"
                                v-model="keyboard[rowIndex][colIndex].text"
                            />
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
                    <input type="text" class="form-control"
                           @change="needRemoveField( 'switch_inline_query_current_chat',select.row, select.col)"
                           v-model="keyboard[select.row][select.col].switch_inline_query_current_chat"
                           :id="'witch-inline-query-current-chat-row-'+select.row+'-col-'+select.col"
                           placeholder="команда">
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
                           class="form-check-input" :id="'phone-row-'+select.row+'-col-'+select.col">
                    <label class="form-check-label" :for="'phone-row-'+select.row+'-col-'+select.col">
                        Запросить телефон
                    </label>
                </div>
                <div class="form-check" v-if="type==='reply'">
                    <input type="radio"
                           name="request-radio"
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


    <div class="modal fade" :id="'page-list-in-keyboard-'+uuid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        keyboard: {
            handler: function (newValue) {
                this.save()
            },
            deep: true
        }
    },
    data() {
        return {

            pageModal: null,
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
        this.pageModal = new bootstrap.Modal(document.getElementById('page-list-in-keyboard-'+  this.uuid), {})


        if (this.editedKeyboard) {
            this.$nextTick(() => {
                this.keyboard = this.editedKeyboard.menu
            })
        }


    },
    methods: {
        openPageModal(){
          this.pageModal.show()
        },
        attachPage(item) {

            let command = (item.slug.command || 'Нет команды').replace(".*","")

            this.keyboard[this.select.row][this.select.col].text = command


            this.$notify({
                title: "Конструктор страниц",
                text: "Вы успешно выбрали страницу",
                type: 'success'
            });

            if (this.type==='inline')
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
        needRemoveField(param, rowIndex, colIndex) {
            Object.keys(this.keyboard[rowIndex][colIndex])
                .forEach(item => {
                    if (item !== 'text' && item !== param)
                        delete this.keyboard[rowIndex][colIndex][item]
                })

        },
        save() {
            this.$emit("save", this.keyboard)

            /* this.load = true
             this.$nextTick(() => {
                 this.load = false
             })*/

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
        moveCol(row, direction = 0) {

            if (row !== this.select.row) {
                this.select.row = row
                this.select.col = 0
                this.select.text = this.keyboard[this.select.row][this.select.col].text
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
