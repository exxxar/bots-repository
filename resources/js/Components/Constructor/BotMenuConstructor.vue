<script setup>


import MenuFunctionSwitcher from "@/Components/Constructor/MenuFunctionSwitcher.vue";
</script>
<template>
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
                    class="btn btn-outline-danger mb-2 ml-2"
                    v-if="selectedRow!=null"
                    @click="selectedRow=null"><i class="fa-solid fa-xmark"></i>
                </button>
            </div>


            <div class="d-flex flex-column">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           v-model="showCode"
                           id="showCode">
                    <label class="form-check-label" for="showCode">
                        Отобразить код
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           v-model="showAssign"
                           id="showAssign">
                    <label class="form-check-label" for="showAssign">
                        Меню связывания
                    </label>
                </div>
            </div>


        </div>
        <div class="col-12">
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
                </div>
                <div class="col-10 d-flex justify-content-center p-1">

                    <div class="btn-group dropdown-center w-100 m-1 " v-for="(col, colIndex) in row">
                        <input
                            type="text"
                            @click="selectIndex(rowIndex, colIndex)"
                            class="btn btn-outline-primary w-100"
                            v-bind:class="{'has-script':hasScript(rowIndex, colIndex)}"
                            v-model="keyboard[rowIndex][colIndex].text"
                        />
                        <button type="button"
                                class="btn btn-outline-primary dropdown-toggle
                                 dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"
                                data-bs-reference="parent">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu w-100" style="min-width:350px;">
                            <form class="px-4 py-3">
                                <div class="alert alert-danger" role="alert">
                                    Возможно выбрать только 1 тип действия
                                </div>


                                <div class="mb-3">
                                    <label :for="'command-row-'+rowIndex+'-col-'+colIndex"
                                           class="form-label">Команда (для меню в сообщении)</label>
                                    <input type="text"
                                           @change="needRemoveField( 'callback_data', rowIndex, colIndex)"
                                           v-model="keyboard[rowIndex][colIndex].callback_data"
                                           class="form-control"
                                           :id="'command-row-'+rowIndex+'-col-'+colIndex"
                                           placeholder="/start">
                                </div>
                                <div class="mb-3">
                                    <label :for="'url-row-'+rowIndex+'-col-'+colIndex"
                                           class="form-label">Ссылка (для меню в сообщении)</label>
                                    <input type="text" class="form-control"
                                           @change="needRemoveField( 'url',rowIndex, colIndex)"
                                           v-model="keyboard[rowIndex][colIndex].url"
                                           :id="'url-row-'+rowIndex+'-col-'+colIndex"
                                           placeholder="https://t.me/example">
                                </div>
                                <div class="form-check">
                                    <input type="radio"
                                           @change="needRemoveField( null,rowIndex, colIndex)"
                                           name="request-radio"
                                           class="form-check-input" :id="'no-action-row-'+rowIndex+'-col-'+colIndex">
                                    <label class="form-check-label" :for="'no-action-row-'+rowIndex+'-col-'+colIndex">
                                        Без действий
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio"
                                           @change="needRemoveField( 'request_contact',rowIndex, colIndex)"
                                           @click="keyboard[rowIndex][colIndex].request_contact = true"
                                           name="request-radio"
                                           class="form-check-input" :id="'phone-row-'+rowIndex+'-col-'+colIndex">
                                    <label class="form-check-label" :for="'phone-row-'+rowIndex+'-col-'+colIndex">
                                        Запросить телефон (для нижнего меню)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio"
                                           name="request-radio"
                                           @change="needRemoveField( 'request_location',rowIndex, colIndex)"
                                           @click="keyboard[rowIndex][colIndex].request_location = true"
                                           class="form-check-input" :id="'location-row-'+rowIndex+'-col-'+colIndex">
                                    <label class="form-check-label" :for="'location-row-'+rowIndex+'-col-'+colIndex">
                                        Запросить локацию (для нижнего меню)
                                    </label>
                                </div>

                            </form>
                        </ul>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-12" v-if="showAssign">
            <MenuFunctionSwitcher
                v-on:change-associate="assignAssociateForm"
                :selected-data="select"
                v-if="selectedRow!=null&!load"/>
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
</template>
<script>
import {Vue3JsonEditor} from 'vue3-json-editor'

export default {
    props: ["editedKeyboard", "type"],
    components: {
        Vue3JsonEditor
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
        if (this.editedKeyboard) {
            this.$nextTick(() => {
                this.keyboard = this.editedKeyboard.menu

                console.log(this.keyboard)
            })
        }
    },
    methods: {
        assignAssociateForm(form) {
            this.keyboard[form.row][form.col].page_id = form.page_id || null
            this.keyboard[form.row][form.col].slug_id = form.slug_id || null
            this.keyboard[form.row][form.col].dialog_id = form.dialog_id || null
            this.keyboard[form.row][form.col].type = form.type || 'reply'
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

            this.load = true
            this.$nextTick(() => {
                this.load = false
            })

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
        hasScript(row, col) {
            return this.keyboard[row][col].page_id ||
                this.keyboard[row][col].slug_id ||
                this.keyboard[row][col].dialog_id
        },
        addRow(above = false) {

            if (this.selectedRow == null) {
                if (this.keyboard)
                    this.keyboard.push([{
                        text: "No Text"
                    }])
                else
                    this.keyboard = [{
                        text: "No Text"
                    }]

                this.selectedRow = null
            } else {
                let index = !above ? this.selectedRow + 1 : this.selectedRow
                this.keyboard.splice(index, 0, [{
                    text: "No Text"
                }])
            }

            this.save();
        },

        addColToRow(index) {
            this.keyboard[index].push({
                text: "No Text"
            })


            this.save();
        },
        selectIndex(rowIndex, colIndex) {

            this.selectedRow = rowIndex

            this.select.row = rowIndex
            this.select.col = colIndex
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

.has-script{

    background-color: rgba(173, 216, 230, 0.30);

}

</style>
