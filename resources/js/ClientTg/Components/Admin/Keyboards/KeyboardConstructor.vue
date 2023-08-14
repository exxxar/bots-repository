<template>

    <div class="mb-2 d-flex justify-content-between align-items-center">
        <div>
            <button
                type="button"
                class="btn btn-primary mb-2"
                v-if="selectedRow==null"
                @click="addRow"><i class="fa-solid fa-arrow-down"></i>
            </button>
            <button
                type="button"
                class="btn btn-primary mb-2"
                v-if="selectedRow!=null"
                @click="addRowAbove"><i class="fa-solid fa-arrow-turn-up"></i>
            </button>
            <button
                type="button"
                class="btn btn-primary mb-2 ml-2"
                v-if="selectedRow!=null"
                @click="addRowBelow"><i class="fa-solid fa-arrow-turn-down"></i>
            </button>

            <button
                type="button"
                class="btn btn-outline-danger mb-2 ml-2"
                v-if="selectedRow!=null"
                @click="selectedRow=null"><i class="fa-solid fa-xmark"></i>
            </button>
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


    </div>

    <div style="overflow-x: scroll;padding: 5px 20px;">
        <div style="min-width:600px;width:100%;">
            <div class="row mb-0"
                 v-if="keyboard.length>0"
                 v-for="(row, rowIndex) in keyboard">
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
                            v-model="keyboard[rowIndex][colIndex].text"
                        />
                        <button type="button"
                                @click="openKeyboardEditorMenu(rowIndex,colIndex)"
                                class="btn btn-outline-primary" aria-expanded="false"
                                data-bs-reference="parent">
                            <i class="fa-solid fa-bars"></i>
                        </button>


                    </div>


                </div>
            </div>
            <p v-else>Элементы клавиатуры еще не добавлены</p>
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
                uuid: null,
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
        openKeyboardEditorMenu(rowIndex, colIndex) {

            this.$botPages.keyboard({
                row:rowIndex,
                col:colIndex,
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

.has-script {

    background-color: rgba(173, 216, 230, 0.30);

}

</style>
