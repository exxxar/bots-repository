<template>
    <template v-if="form?.coffee">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.coffee.enabled"
                   role="switch" id="script-settings-is_edit_mode">
            <label class="form-check-label" for="script-settings-is_edit_mode">Режим бонусного кофе: <span
                v-bind:class="{'text-primary fw-bold':form.coffee.enabled}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!form.coffee.enabled}">выкл</span></label>
        </div>

        <div class="form-floating mb-2">
                <textarea class="form-control"
                          v-model="form.coffee.rules"
                          maxlength="4000"
                          style="min-height:150px;"
                          placeholder="Leave a comment here"
                          id="script-settings-disabled_text"></textarea>
            <label for="script-settings-disabled_text">Текст правил</label>
        </div>

        <div class="form-floating mb-2">
            <input type="number"
                   min="0"
                   v-model="form.coffee.max"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Необходимое кол-во</label>
        </div>
    </template>

</template>
<script>
export default {
    props: ["modelValue"],

    data() {
        return {
            loaded_params: false,
            form: null,
        }
    },
    watch: {
        'form': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.form)
            },
            deep: true
        },
    },
    mounted() {
        this.loaded_params = false
        this.$nextTick(() => {
            this.form = this.modelValue
            if (!this.modelValue.coffee)
                this.form.coffee = {
                    enabled: true,
                    max: 7,
                    rules: `
1. За каждую покупку кофе — 1 отметка.
2. После 7 кружек — 1 кофе бесплатно.
3. Отметки действуют 30 дней.
4. Бесплатный кофе нельзя обменять на деньги.
        `
                }
            this.loaded_params = true
        })
    }
}
</script>
