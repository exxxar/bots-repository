<template>
    <div class="row">
        <div class="col-12">
            <button
                type="button"
                class="btn btn-primary mb-2"
                v-if="selectedRow==null"
                @click="addRow">Добавить строку</button>
            <button
                type="button"
                class="btn btn-primary mb-2"
                v-if="selectedRow!=null"
                @click="addRowAbove">Добавить строку выше</button>
            <button
                type="button"
                class="btn btn-primary mb-2 ml-2"
                v-if="selectedRow!=null"
                @click="addRowBelow">Добавить строку ниже</button>
        </div>
        <div class="col-12">
            <div class="row" v-for="(row, rowIndex) in keyboard">
                <div class="col-1 d-flex justify-content-around p-1">
                    <button
                        type="button"
                        class="btn btn-info"
                        @click="addColToRow(rowIndex)">+
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="removeColFromRow(rowIndex)">-
                    </button>
                </div>
                <div class="col-11 d-flex justify-content-center p-1">
                    <input
                        type="text"
                        @click="selectedRow = rowIndex"
                        class="btn btn-outline-primary w-100 m-1"
                        v-model="keyboard[rowIndex][colIndex].text"
                        v-for="(col, colIndex) in row"/>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
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
    props:["editedKeyboard"],
    components: {
        Vue3JsonEditor
    },
    watch: {
        keyboard: {
            handler: function(newValue) {
               this.save()
            },
            deep: true
        }
    },
    data() {
        return {
            selectedRow: null,
            load: false,
            rowCount: 1,
            keyboard: []
        }
    },
    mounted() {
      if (this.editedKeyboard){
          this.$nextTick(()=>{
              this.keyboard = this.editedKeyboard.menu

              console.log(this.keyboard)
          })
      }
    },
    methods: {
        save(){
          this.$emit("save", this.keyboard)

            this.load = true
            this.$nextTick(()=>{
                this.load = false
            })

        },
        onJsonChange(value) {
            this.keyboard  = value
            this.save();

        },
        removeColFromRow(index) {
            if (this.keyboard[index].length > 1)
            this.keyboard[index].splice(this.keyboard[index].length - 1, 1)
            else
                this.keyboard.splice(index, 1)

            this.save();
        },
        addRowAbove(){
            this.addRow(true)
        },
        addRowBelow(){
            this.addRow(false)
        },
        addRow(above = false){

            if (this.selectedRow==null) {
                this.keyboard.push([{
                    text: "No Text"
                }])

                this.selectedRow = null
            }

            else {
                let index = !above ? this.selectedRow+1: this.selectedRow
                this.keyboard.splice(index, 0 ,[{
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
