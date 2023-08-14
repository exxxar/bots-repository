<template>


    <div :id="'keyboard-editor'" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="405" data-menu-effect="menu-over"
         style="height: 405px;display: block;">

        <form class="px-3 py-3"
              v-on:submit.prevent="submitKeyboard"
              v-if="keyboard">


            <div class="mb-3">
                <label :for="'title-row-'+select.row+'-col-'+select.col"
                       class="form-label">Название поля</label>
                <input type="text"
                       v-model="keyboard[select.row][select.col].text"
                       class="form-control"
                       :id="'title-row-'+select.row+'-col-'+select.col"
                       placeholder="Название">
            </div>

            <div class="divider divider-small my-3 bg-highlight "></div>

            <div class="alert alert-danger" role="alert">
                Возможно выбрать только 1 тип действия
            </div>

            <div class="mb-3">
                <label :for="'command-row-'+select.row+'-col-'+select.col"
                       class="form-label">Команда (для меню в сообщении)</label>
                <input type="text"
                       @change="needRemoveField( 'callback_data', select.row, select.col)"
                       v-model="keyboard[select.row][select.col].callback_data"
                       class="form-control"
                       :id="'command-row-'+select.row+'-col-'+select.col"
                       placeholder="/start">
            </div>
            <div class="mb-3">
                <label :for="'switch-inline-query-row-'+select.row+'-col-'+select.col"
                       class="form-label">Ссылка на аккаунт в ТЕЛЕГРАММ</label>
                <input type="text" class="form-control"
                       @change="needRemoveField( 'switch_inline_query',select.row, select.col)"
                       v-model="keyboard[select.row][select.col].switch_inline_query"
                       :id="'switch-inline-query-row-'+select.row+'-col-'+select.col"
                       placeholder="@YourAccountLink">
            </div>
            <div class="mb-3">
                <label :for="'url-row-'+select.row+'-col-'+select.col"
                       class="form-label">Внешняя URL-ссылка</label>
                <input type="text" class="form-control"
                       @change="needRemoveField( 'url',select.row, select.col)"
                       v-model="keyboard[select.row][select.col].url"
                       :id="'url-row-'+select.row+'-col-'+select.col"
                       placeholder="https://t.me/example">
            </div>

            <div class="mb-3">
                <label :for="'switch-inline-query-current-chat-row-'+select.row+'-col-'+select.col"
                       class="form-label">Команда всплывающего меню бота</label>
                <input type="text" class="form-control"
                       @change="needRemoveField( 'switch_inline_query_current_chat',select.row, select.col)"
                       v-model="keyboard[select.row][select.col].switch_inline_query_current_chat"
                       :id="'witch-inline-query-current-chat-row-'+select.row+'-col-'+select.col"
                       placeholder="команда">
            </div>


            <div class="form-check">
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
            <div class="form-check" v-if="select.row===0">
                <input type="radio"
                       @change="needRemoveField( 'pay',select.row, select.col)"
                       @click="keyboard[select.row][select.col].pay = true"
                       name="request-radio"
                       class="form-check-input"
                       :id="'pay-action-row-'+select.row+'-col-'+select.col">
                <label class="form-check-label"
                       :for="'pay-action-row-'+select.row+'-col-'+select.col">
                    Кнопка оплаты
                </label>
            </div>
            <div class="form-check">
                <input type="radio"
                       @change="needRemoveField( 'request_contact',select.row, select.col)"
                       @click="keyboard[select.row][select.col].request_contact = true"
                       name="request-radio"
                       class="form-check-input" :id="'phone-row-'+select.row+'-col-'+select.col">
                <label class="form-check-label" :for="'phone-row-'+select.row+'-col-'+select.col">
                    Запросить телефон (для нижнего меню)
                </label>
            </div>
            <div class="form-check">
                <input type="radio"
                       name="request-radio"
                       @change="needRemoveField( 'request_location',select.row, select.col)"
                       @click="keyboard[select.row][select.col].request_location = true"
                       class="form-check-input" :id="'location-row-'+select.row+'-col-'+select.col">
                <label class="form-check-label"
                       :for="'location-row-'+select.row+'-col-'+select.col">
                    Запросить локацию (для нижнего меню)
                </label>
            </div>

        </form>


    </div>


</template>
<script>
export default {
    data() {
        return {
            load: false,
            keyboard: null,
            select: {
                uuid: null,
                row: 0,
                col: 0,
                type: null
            }
        }
    },
    mounted() {

        window.addEventListener("open-keyboard-editor", (e) => {

            const select = e.detail.select
            const keyboard = e.detail.keyboard || null
            this.select = select
            this.keyboard = keyboard

            $('#keyboard-editor').showMenu();


        });
    },
    methods: {

        needRemoveField(param, rowIndex, colIndex) {
            Object.keys(this.keyboard[rowIndex][colIndex])
                .forEach(item => {
                    if (item !== 'text' && item !== param)
                        delete this.keyboard[rowIndex][colIndex][item]
                })

        },
    }
}
</script>

