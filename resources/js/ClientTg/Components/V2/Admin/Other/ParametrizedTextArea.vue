<template>

    <div>
        <div class="alert alert-light mb-2">
            Данное сообщение может содержать параметры для отображения:
            <div class="dropdown d-inline">
                <button class="text-primary btn btn-link p-0" style="font-size:14px;" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <ul class="dropdown-menu">
                    <li v-for="item in settings"><a
                        @click="addCommand(item)"
                        class="dropdown-item" href="javascript:void(0)">{{ item.title || 'Не указано' }}</a></li>
                </ul>
            </div>
        </div>

        <div class="form-floating">
                <textarea class="form-control"
                          v-model="message"
                          :maxlength="maxlength||'4000'"
                          style="min-height:150px;"
                          :required="required||false"
                          placeholder="Leave a comment here"
                          id="script-settings-wheel-of-fortune-can_play"></textarea>
            <label for="script-settings-disabled_text">
                <slot name="title"></slot>
                <span v-if="(message||'').length>0">{{ (message || '').length }}/{{ maxlength || 4000 }}</span>
            </label>
        </div>
    </div>

</template>
<script>
export default {
    props: ["modelValue", "maxlength", "params","required"],
    data() {
        return {
            message: null,
            settings: [
                {
                    title: 'Имя пользователя',
                    key: '{{name}}',
                },
                {
                    title: 'Домен аккаунта',
                    key: '{{username}}',
                },
                {
                    title: 'Номер телефона',
                    key: '{{phone}}',
                },

                {
                    title: 'Название приза',
                    key: '{{prize}}',
                }
            ]
        }
    },
    watch: {
        'message': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.message)
            },
            deep: true
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.message = this.modelValue

            if (this.params)
                this.settings = this.params
        })
    },
    methods: {
        addCommand(item) {
            if (!this.message)
                this.message = ""

            this.message += item.key
        }
    }
}
</script>
