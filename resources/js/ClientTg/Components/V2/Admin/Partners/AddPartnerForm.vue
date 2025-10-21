<template>


    <!-- Футер с кнопкой -->
    <nav

        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
        style="border-radius:10px 10px 0px 0px;z-index:999!important;">
        <div class="container text-center">
            <button class="btn text-center btn-primary w-100 p-3 rounded-3 shadow-lg" @click="openModal">
                Добавить партнера
            </button>
        </div>
    </nav>

    <!-- Модальное окно -->
    <div class="modal fade" id="add-partner-modal" tabindex="-1" aria-labelledby="add-partner-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-partner-modal-label">Добавление партнера</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p class="alert alert-light border-secondary mb-2">
                        Настройка партнера происходит после его добавления
                    </p>
                    <form @submit.prevent="handleSubmit">
                        <div class="mb-3 form-floating">
                            <input
                                type="text"
                                class="form-control"
                                id="telegram"
                                v-model="telegramInput"
                                placeholder="Введите ссылку на Telegram-бота"
                            />
                            <label for="telegram" class="form-label">Ссылка на Telegram-бота</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 p-3">Добавить</button>
                    </form>

                </div>

            </div>
        </div>
    </div>


</template>

<script>
export default {
    data() {
        return {
            telegramInput: "",
            modal:null,
        };
    },
    mounted() {
        this.modal =  new bootstrap.Modal(document.getElementById('add-partner-modal'));
    },
    methods: {
        openModal() {

            this.modal.show();
        },
        handleSubmit() {
            const processedTelegram = this.processTelegramLink(this.telegramInput);

            let data = new FormData();
            data.append("telegram_domain", processedTelegram)

            this.$store.dispatch("storePartner", {
                form: data
            }).then((response) => {
                this.load = true

                this.telegramInput = ''
                this.$notify({
                    title: "Добавление партнера",
                    text: "Партнер успешно добавлен",
                    type: 'success'
                })

                this.modal.hide()

                this.$emit("callback")

            }).catch(err => {
                this.$notify({
                    title: "Добавление партнера",
                    text: err.response.data.message || "Ошибка добавления партнера",
                    type: 'error'
                })
            })
        },
        processTelegramLink(link) {
            // Убираем https://t.me/ если это ссылка, или @ если это домен
            let processedLink = link.trim();
            if (processedLink.startsWith("https://t.me/")) {
                processedLink = processedLink.replace("https://t.me/", "");
            } else if (processedLink.startsWith("@")) {
                processedLink = processedLink.replace("@", "");
            }
            return processedLink;
        }
    }
};
</script>

<style scoped>
/* Добавьте стили, если необходимо */
</style>
