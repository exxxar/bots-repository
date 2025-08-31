<template>
    <div
        v-if="loaded_params"
        class="d-flex flex-column justify-content-center align-items-center pb-5">
        <!-- Фоновое изображение сертификата -->

        <!-- Форма поверх фона -->
        <form
            class="p-1 w-100"
        >
            <!-- Заголовок -->
            <div class="form-floating mb-2">
                <input
                    type="text"
                    class="form-control"
                    id="title"
                    v-model="form.init_certificate.title"
                    placeholder="Подарочный сертификат"
                    required
                />
                <label htmlFor="title">Название сертификата</label>
            </div>

            <div class="form-floating mb-2">
                <input
                    type="text"
                    class="form-control"
                    id="title"
                    v-model="form.init_certificate.description"
                    placeholder="Подарочный сертификат"
                    required
                />
                <label htmlFor="title">Описание приза</label>
            </div>


            <!-- Сумма -->
            <div
                v-if="form.init_certificate.type==='cashback'||form.init_certificate.type==='discount'"
                class="form-floating mb-2">
                <input
                    type="number"
                    class="form-control"
                    id="amount"
                    v-model="form.init_certificate.amount"
                    placeholder="500"
                />
                <label htmlFor="amount">
                    <span v-if="form.init_certificate.type==='cashback'">Сумма кэшбэка, руб</span>
                    <span v-if="form.init_certificate.type==='discount'">Процент скидки, %</span>
                    <span v-if="form.init_certificate.type==='gift'">Идентификатор подарка</span>
                </label>
            </div>

            <!-- Тип -->
            <div class="mb-2 form-floating">

                <select class="form-select" id="type" v-model="form.init_certificate.type" required>
                    <option value="cashback">CashBack</option>
                    <option value="discount">Скидка</option>
                    <option value="gift">Подарок</option>
                </select>
                <label htmlFor="type" class="form-label">Тип сертификата</label>
            </div>

            <!-- Активность -->
            <div class="form-check form-switch mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    id="isActive"
                    required
                    v-model="form.init_certificate.is_active"
                />
                <label class="form-check-label" htmlFor="isActive">Активен</label>
            </div>

        </form>


        <div class="image-with-labels">
            <img src="/images/certificate_1.png" alt="Certificate Background" class="bg-image"/>
            <div class="text-data">
                <p class="title mb-0">{{ form.init_certificate.title || 'Заголовок' }}</p>
                <p class="description mb-0">{{ form.init_certificate.description || 'Описание' }}</p>
                <p class="data">25.05.2025</p>
                <p class="qr">
                    Место под QR-код
                </p>
            </div>
        </div>

    </div>
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
            if (!this.modelValue.init_certificate)
                this.form.init_certificate = {
                    title: "Подарочный сертификат",
                    description: "500 рублей на CashBack",
                    amount: 500,
                    type: "cashback",
                    is_active: false,
                }
            this.loaded_params = true
        })
    }
}
</script>

<style lang="scss" scoped>
.bg-image {
    width: 100%;
    object-fit: contain;
}

.certificate-form-wrapper {
    height: 100vh;
    overflow: hidden;
}

.image-with-labels {
    height: 250px;
    position: relative;

    .text-data {
        position: absolute;
        top: 100px;
        width: 100%;

        p {
            text-align: center;
            font-size: 12px;

            &.title {
                font-weight: bold;

            }

            &.qr {
                width: 50px;
                height: 50px;
                background-color: white;
                position: absolute;
                right: 31px;
                bottom: -60px;

                font-size: 8px;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 5px;
            }
        }
    }

    img {
        object-fit: contain;
        width: 100%;
        height: 100%;
    }
}
</style>
