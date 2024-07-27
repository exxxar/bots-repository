<template>

    <Popper>
        <p class="mb-2"><i class="fa-regular fa-circle-question mr-1"></i>Инструкция</p>
        <template #content>
            <div class="text-left w-100 ">
                <p class="mb-0 text-white"><i class="fa-solid fa-arrow-down"></i> - добавление в нижнюю часть новой
                    строки (кнопки)</p>
                <p class="mb-0 text-white"><i class="fa-solid fa-arrow-turn-up"></i> - добавление строки над выбранной
                    строкой</p>
                <p class="mb-0 text-white"><i class="fa-solid fa-arrow-turn-down"></i> - добавление строки под выбранной
                    строкой</p>
                <p class="mb-0 text-white"><i class="fa-solid fa-plus"></i> - добавление кнопки на строку</p>
                <p class="mb-0 text-white"><i class="fa-solid fa-minus"></i> - удаление крайней левой кнопки из строки
                    либо самой строки</p>
                <p class="mb-0 text-white"><i class="fa-solid fa-xmark"></i> - отмена выделения</p>
            </div>

        </template>
    </Popper>


    <div class="mb-2 d-flex">

        <button
            title="добавление в нижнюю часть новой строки (кнопки)"
            type="button"
            class="btn btn-link mb-2 w-100 mr-2"
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


    <p v-if="keyboard.length===0" class="text-danger font-weight-bold p-0 m-0">Элементы клавиатуры еще не добавлены</p>

    <div v-if="keyboard.length>0">
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
                        type="text"
                        class="btn btn-outline-primary w-100"
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
                            class="btn btn btn-outline-primary" type="button" data-bs-toggle="dropdown"
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
                                    @click="removeColFromRow()"><i class="fa-solid fa-minus"> </i> Удалить
                                </a>
                            </li>

                            <li>
                                <a
                                    href="javascript:void(0)"
                                    class="dropdown-item"
                                    v-if="selectedRow!=null"
                                    @click="moveCol(0)"><i class="fa-solid fa-caret-left"> </i> Переместить
                                </a>
                            </li>


                            <li>
                                <a
                                    href="javascript:void(0)"
                                    class="dropdown-item"
                                    v-if="selectedRow!=null"
                                    @click="moveCol(1)"><i class="fa-solid fa-caret-right"> </i> Переместить
                                </a>
                            </li>

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
            return uuidv4();
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
            })
        }

    },
    methods: {
        moveCol(direction = 0) {

            let row = this.selectedRow

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

            this.selectedRow = index

        },
        openKeyboardEditorMenu(rowIndex, colIndex) {

            this.$botPages.keyboard({
                row: rowIndex,
                col: colIndex,
                type: this.type,
            }, this.keyboard)
        },

        save() {
            this.$emit("save", this.keyboard)
        },
        onJsonChange(value) {
            this.keyboard = value
            this.save();

        },
        removeColFromRow() {

            let index = this.selectedRow
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
