<template>

    <div class="d-inline-flex">


<!--        <button type="button"
                @click="openReLinkModal"
                data-bs-toggle="tooltip"
                data-bs-title="Добавить кнопку с пересылкой ссылки друзьям"
                class="btn btn-link"><i class="fa-solid fa-sack-dollar"></i></button>-->
        <button type="button"
                @click="openReLinkModal"
                data-bs-toggle="tooltip"
                data-bs-title="Добавить кнопку с пересылкой ссылки друзьям"
                class="btn btn-link">      <i class="fa-solid fa-arrow-up-right-from-square"></i></button>
        <button type="button"
                @click="openFontModal"
                data-bs-toggle="tooltip"
                data-bs-title="Настройка шрифта"
                class="btn btn-link">   <i class="fa-solid fa-font"></i></button>
        <button type="button"
                @click="boldTag"
                data-bs-toggle="tooltip"
                data-bs-title="Жирный текст"
                class="btn btn-link"><i class="fa-solid fa-bold"></i></button>
        <button type="button"
                @click="italic"
                data-bs-toggle="tooltip"
                data-bs-title="Курсив"
                class="btn btn-link"><i class="fa-solid fa-italic"></i></button>
        <button type="button"
                @click="underline"
                data-bs-toggle="tooltip"
                data-bs-title="Подчеркнутый текст"
                class="btn btn-link"><i class="fa-solid fa-underline"></i></button>
        <button type="button"
                @click="spoiler"
                data-bs-toggle="tooltip"
                data-bs-title="Скрытый текст"
                class="btn btn-link"><i class="fa-solid fa-hand-dots"></i></button>
        <button type="button"
                @click="strike"
                data-bs-toggle="tooltip"
                data-bs-title="Зачеркнутый текст"
                class="btn btn-link"><i class="fa-solid fa-strikethrough"></i></button>
        <button type="button"
                @click="code"
                data-bs-toggle="tooltip"
                data-bs-title="Фрагмент кода"
                class="btn btn-link"><i class="fa-solid fa-code"></i></button>
        <button type="button"
                @click="blockquote"
                data-bs-toggle="tooltip"
                data-bs-title="Цитата"
                class="btn btn-link"><i class="fa-solid fa-quote-left"></i></button>
        <button type="button"
                @click="openLinkModal"
                data-bs-toggle="tooltip"
                data-bs-title="Ссылка"
                class="btn btn-link"><i class="fa-solid fa-link"></i></button>
        <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                <i class="fa-brands fa-hire-a-helper"></i>
            </button>

            <div
                class="dropdown-menu item-with-text cursor-pointer text-muted p-2">

                <li @click="expressionsSelect(item)"
                    v-for="item in expressions"><a class="dropdown-item" href="javascript:void(0)" v-html="item.description"></a>


                </li>

            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="font-choose-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                   <h6 class="fw-bold"><strong class="text-primary">Внимание!</strong> Откройте ссылку с генератором шрифтов, выберите шрифт, введите текст, а затем скопируйте полученный результат в поле ниже - текст с этим шрифтом будет отображен пользователю!</h6>
                    <a href="https://textgenerator.ru/" class="btn btn-link w-100 text-center" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Открыть генератор шрифтов</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="link-enter-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" v-on:submit.prevent="linkTag">

                <div class="modal-body">
                    <div class="alert alert-light mb-2">
                        <strong class="fw-bold">Внимание!</strong> Добавьте ссылку, на которую должен перейти пользователь!
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text"
                               v-model="link"
                               class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Ссылка</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="p-2 w-100">
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Отмена
                            </button>
                        </div>
                        <div class="p-2 w-100">
                            <button type="submit"
                                    class="btn btn-primary w-100">Сохранить
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="relink-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" v-on:submit.prevent="reLinkSubmit">

                <div class="modal-body">
                    <div class="alert alert-light mb-2">
                        <strong class="fw-bold">Внимание!</strong> Вы создаете кнопку к тексту, по которой пользователь сможет поделиться ссылкой и текстом к ней!
                    </div>
                    <div class="form-floating mb-2">
                        <input type="url"
                               v-model="reLinkForm.url"
                               class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput">Ссылка,url</label>
                    </div>

                    <div class="form-floating">
                        <textarea class="form-control"
                                  v-model="reLinkForm.text"
                                  placeholder="Leave a comment here" id="floatingTextarea2" style="min-height: 100px" required></textarea>
                        <label for="floatingTextarea2">Текст к ссылке</label>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="p-2 w-100">
                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Отмена
                            </button>
                        </div>
                        <div class="p-2 w-100">
                            <button type="submit"
                                    class="btn btn-primary w-100">Сохранить
                            </button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>

</template>
<script>
export default {
    props: ["modelValue", "fieldId"],
    data() {
        return {
            link: null,
            linkModal: null,
            fontModal: null,
            relinkModal: null,
            reLinkForm:{
              url:null,
              text:null,
            },
            expressions: [
                {
                    expression: "{{userName}}",
                    description: '<i class="fa-solid fa-signature mr-2"></i>Имя пользователя'
                },

                {
                    expression: "{{level_1_percent}}",
                    description: '<i class="fa-solid fa-1 mr-2"></i>Процент кэшбэка 1 уровня'
                },

                {
                    expression: "{{level_2_percent}}",
                    description: '<i class="fa-solid fa-2 mr-2"></i>Процент кэшбэка 2 уровня'
                },

                {
                    expression: "{{level_3_percent}}",
                    description: '<i class="fa-solid fa-3 mr-2"></i>Процент кэшбэка 3 уровня'
                },

                {
                    expression: "{{cashback_fire_percent}}",
                    description: '<i class="fa-solid fa-fire mr-2"></i>Процент сжигания кэшбэка'
                },

                {
                    expression: "{{is_admin}}",
                    description: '<i class="fa-solid fa-user-tie mr-2"></i>Статус администратора'
                },

                {
                    expression: "{{is_work}}",
                    description: '<i class="fa-solid fa-building mr-2"></i>Статус работника'
                },

                {
                    expression: "{{is_vip}}",
                    description: '<i class="fa-solid fa-crown mr-2"></i>Статус VIP'
                },

                {
                    expression: "{{is_manager}}",
                    description: '<i class="fa-solid fa-people-roof mr-2"></i>Статус менеджера'
                },

                {
                    expression: "{{is_deliveryman}}",
                    description: '<i class="fa-solid fa-truck-ramp-box mr-2"></i>Статус доставщика'
                },

                {
                    expression: "{{sex}}",
                    description: '<i class="fa-solid fa-venus-mars mr-2"></i>Пол клиента'
                },
                {
                    expression: "{{friendsCount}}",
                    description: '<i class="fa-solid fa-user-group mr-2"></i>Число друзей пользователя'
                },
                {
                    expression: "{{cashback}}",
                    description: '<i class="fa-solid fa-coins mr-2"></i>Сумма КэшБэк пользователя'
                },
                {
                    expression: '{{telegramChatId}}',
                    description: '<i class="fa-brands fa-telegram mr-2"></i>Идентификатор чата'
                },
                {
                    expression: '{{referralLink}}',
                    description: '<i class="fa-solid fa-link mr-2"></i>Реферальная ссылка'
                },
                {
                    expression: '{{referralQr}}',
                    description: '<i class="fa-solid fa-qrcode mr-2"></i>Реферальный QR-код'
                },

            ]
        }
    },
    mounted() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        this.linkModal = new bootstrap.Modal(document.getElementById('link-enter-modal'), {})
        this.fontModal = new bootstrap.Modal(document.getElementById('font-choose-modal'), {})
        this.relinkModal = new bootstrap.Modal(document.getElementById('relink-modal'), {})
    },
    methods: {
        openReLinkModal(){
            this.relinkModal.show();
        },
        openFontModal(){
          this.fontModal.show();
        },
        reLinkSubmit(){
            this.$emit("submit-relink",this.reLinkForm)
            this.relinkModal.hide()
        },
        getFieldSelection() {
            let start = document.querySelector(this.fieldId).selectionStart || 0;
            let end = document.querySelector(this.fieldId).selectionEnd || 0;

            return {
                start: start,
                end: end,
            }
        },
        useTemplate(startTemplate, endTemplate) {

            let range = this.getFieldSelection()
            let tmp = this.modelValue || '';
            let firstPart = tmp.slice(0, range.start)

            if (range.end - range.start > 0 && range.end > 0) {
                let textInCenter = tmp.slice(range.start, range.end)
                let secondPart = tmp.slice(range.end)
                tmp = firstPart
                    + startTemplate + textInCenter + endTemplate + secondPart;
                this.$emit("update:modelValue", tmp)
            }


            /* tmp = firstPart
                 + startTemplate+endTemplate +tmp.slice(range.start);*/


        },
        boldTag() {
            this.useTemplate("<b>", "</b>")
        },
        italic() {
            this.useTemplate("<i>", "</i>")
        },
        underline() {
            this.useTemplate("<u>", "</u>")
        },
        strike() {
            this.useTemplate("<s>", "</s>")
        },
        code() {
            this.useTemplate("<code>", "</code>")
        },
        spoiler() {
            this.useTemplate("<span class='tg-spoiler'>", "</span>")
        },
        pre() {
            this.useTemplate("<pre>", "</pre>")
        },
        blockquote() {
            this.useTemplate("<blockquote>", "</blockquote>")
        },
        openLinkModal() {

            this.linkModal.show();
        },
        linkTag() {
            this.linkModal.hide();
            const link = this.link
            this.useTemplate("<a href='" + link + "'>", "</a>")
            this.link = null
        },
        expressionsSelect(item) {
            let range = this.getFieldSelection()
            let tmp = this.modelValue || '';
            let firstPart = tmp.slice(0, range.start)
            tmp = firstPart
                + item.expression + tmp.slice(range.start);

            this.$emit("update:modelValue", tmp)
        },
    }
}
</script>
<style lang="scss">
.item-with-text {
    max-height: 300px;
    max-width: 500px;
    min-width: 500px !important;
    overflow-y: auto;
}
</style>
