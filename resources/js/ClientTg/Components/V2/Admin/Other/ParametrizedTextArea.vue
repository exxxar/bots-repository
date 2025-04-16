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
                            :name="'script-settings-text'+uuid"
                          :maxlength="maxlength||'4000'"
                          style="min-height:150px;"
                          :required="required||false"
                          placeholder="Leave a comment here"

                          :id="'script-settings-text'+uuid"></textarea>
            <label :for="'script-settings-text'+uuid">
                <slot name="title"></slot>
                <span v-if="(message||'').length>0">{{ (message || '').length }}/{{ maxlength || 4000 }}</span>
            </label>
        </div>
    </div>

</template>
<script>
import {v4 as uuidv4} from "uuid";
export default {
    props: ["modelValue", "maxlength", "params","required"],
    computed:{
        uuid() {
            return uuidv4();
        }
    },
    data() {
        return {
            message: null,
            selection:{
              start: 0,
            },
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
            this.message = this.modelValue || '-'

            if (this.params)
                this.settings = this.params
        })
    },
    methods: {
        insert( text){
            let cursorPosition = document.querySelector('#script-settings-text'+this.uuid).selectionStart || 0;
            let tmp = this.message;
            this.message = tmp.slice(0, cursorPosition)
                + '<b>' + text+'</b>' + tmp.slice(cursorPosition);
        },
        addCommand(item) {
            if (!this.message)
                this.message = ""

            this.insert(item.key)
        }
    }
}
</script>
