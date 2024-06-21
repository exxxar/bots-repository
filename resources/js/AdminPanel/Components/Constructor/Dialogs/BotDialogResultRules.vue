<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
</script>
<template>
    <button
        type="button"
        @click="addRule"
        class="btn btn-outline-info">Добавить правило
    </button>

    <template
        v-if="rules.length>0"
        v-for="(item, index) in rules">
        <div class="row mt-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h6>Шаг {{ index + 1 }}</h6>
                <button
                    @click="removeRule(index)"
                    type="button" class="btn btn-link">удалить правило
                </button>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-3">
                <div class="form-floating">
                    <input type="text" class="form-control"
                           v-model="rules[index].param1"
                           id="floatingInput"
                           placeholder="name@example.com">
                    <label for="floatingInput">Значение параметра 1</label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-floating">
                    <select class="form-select"
                            v-model="rules[index].operation"
                            id="floatingSelect"
                            aria-label="Floating label select example">
                        <option :value="null" selected>без действия</option>
                        <option :value="op.id" v-for="op in operations">{{ op.title || '-' }}</option>
                    </select>
                    <label for="floatingSelect">Доступные операции</label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-floating">
                    <input type="text"
                           v-model="rules[index].param2"
                           class="form-control" id="floatingInput"
                           placeholder="name@example.com">
                    <label for="floatingInput">Значение параметра 2</label>
                </div>
            </div>
            <div class="col-3">
                <div class="form-floating">
                    <input type="text"
                           :disabled="!testRule(index)"
                           v-model="rules[index].use_result_as"
                           class="form-control" id="floatingInput"
                           placeholder="name@example.com">
                    <label for="floatingInput">Переменная результата</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h6>Если условие выполнено</h6>
            </div>
            <div class="col-6">
                <h6>Если условие <span class="text-danger">не</span> выполнено</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="rules[index].text_if_true"
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Текст для пользователя</label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-floating mb-3">
                    <input type="text"
                           v-model="rules[index].text_if_false"
                           class="form-control" id="floatingInput" placeholder="name@example.com">
                    <label for="floatingInput">Текст для пользователя</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox"
                           v-model="rules[index].need_keyboard_if_true"
                           :id="'need-keyboard-if-true-'+index">
                    <label class="form-check-label" :for="'need-keyboard-if-true-'+index">
                        Нужна клавиатура
                    </label>
                </div>
                <BotMenuConstructor
                    :type="'inline'"
                    v-if="rules[index].need_keyboard_if_true"
                    v-on:save="saveInlineKeyboard($event, index, true)"
                    :edited-keyboard="rules[index].keyboard_if_true"/>
            </div>
            <div class="col-6">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox"
                           v-model="rules[index].need_keyboard_if_false"
                           :id="'need-keyboard-if-false-'+index">
                    <label class="form-check-label" :for="'need-keyboard-if-false-'+index">
                        Нужна клавиатура
                    </label>
                </div>
                <BotMenuConstructor
                    :type="'inline'"
                    v-if="rules[index].need_keyboard_if_false"
                    v-on:save="saveInlineKeyboard($event, index, false)"
                    :edited-keyboard="rules[index].keyboard_if_false"/>
            </div>
        </div>
    </template>
    <div
        v-else
        class="alert alert-primary d-flex align-items-center mt-2" role="alert">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
        <div>
            Правила не добавлены
        </div>
    </div>

</template>
<script>
export default {
    props: ['modelValue'],
    data() {
        return {
            rules: [],
            operations: [
                {
                    id: 1,
                    title: 'Больше'
                },
                {
                    id: 2,
                    title: 'Меньше'
                },
                {
                    id: 3,
                    title: 'Равно'
                },
                {
                    id: 4,
                    title: 'Не равно'
                },
                {
                    id: 5,
                    title: 'Сложить'
                },
                {
                    id: 6,
                    title: 'Вычесть'
                },
                {
                    id: 7,
                    title: 'Логическое И'
                },
                {
                    id: 8,
                    title: 'Логическое ИЛИ'
                },
            ]
        }
    },
    mounted() {
      if (this.modelValue)
          this.rules = this.modelValue
    },
    methods: {
        addRule() {
            this.rules.push({
                param1: null,
                operation: null,
                param2: null,
                use_result_as: null,
                text_if_true: null,
                text_if_false: null,
                keyboard_if_true: null,
                keyboard_if_false:null,
                need_keyboard_if_true:false,
                need_keyboard_if_false:false,
            })

            this.$notify({
                title: "Конструктор ботов",
                text: "Успешная создано новое правило",
                type: 'success'
            });

            this.$emit('update:modelValue', this.rules)
        },
        testRule(index) {
            let rule = this.rules[index]

            return rule.operation === 5 || rule.operation === 6
        },
        removeRule(index) {
            this.rules.splice(index, 1)

            this.$notify({
                title: "Конструктор ботов",
                text: "Успешная удалено выбранное правило",
                type: 'success'
            });
        },
        saveInlineKeyboard(keyboard, index, direction) {
            this.rules[index]["keyboard_if_"+(direction?"true":"false")] = keyboard

        },
    }
}
</script>
