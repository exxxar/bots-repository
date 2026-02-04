<template>
    <template v-if="loaded_params">
        <!-- Активность -->
        <div class="form-check form-switch mb-2">
            <input
                class="form-check-input"
                type="checkbox"
                v-model="form.subscriptions.is_active"
            >
            <label class="form-check-label">
                Активировать подписки
            </label>
        </div>

        <!-- Текст -->
        <div class="form-floating mb-2">
      <textarea
          class="form-control"
          placeholder=" "
          style="min-height: 200px"
          v-model="form.subscriptions.text"
      ></textarea>
            <label>Текст подписки</label>
        </div>


        <!-- Каналы -->
        <div
            v-for="(channel, index) in form.subscriptions.channels"
            :key="index"
            class="border rounded p-3 mb-2 position-relative"
        >
            <h6 class="mb-2">Канал {{ index + 1 }}</h6>

            <button
                v-if="form.subscriptions.channels.length>1"
                type="button"
                class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2"
                @click="removeChannel(index)"
            >
                ✕
            </button>

            <div class="form-floating mb-2">
                <input
                    class="form-control"
                    placeholder=" "
                    v-model="channel.title"
                >
                <label>Название на кнопке</label>
            </div>

            <div class="form-floating">
                <input
                    class="form-control"
                    placeholder=" "
                    v-model="channel.link"
                >
                <label>Ссылка</label>
            </div>

            <p
                v-if="channel.error"
                class="alert alert-danger mt-2 mb-0">
                {{ channel.error }}
            </p>
        </div>

        <!-- Добавить канал -->
        <button
            type="button"
            class="btn btn-outline-primary w-100 mb-5"
            @click="addChannel"
        >
            ➕ Добавить канал
        </button>

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
            if (!this.modelValue.subscriptions)
                this.form.subscriptions = {
                    text: "Подпишись на каналы ниже и получи доступ к проекту",
                    is_active: false,
                    channels: [
                        {
                            title: 'Канал 1',
                            link: '',
                            id: '',
                        },
                        {
                            title: 'Канал 2',
                            link: '',
                            id: '',
                        }
                    ]
                }
            this.loaded_params = true
        })
    },
    methods: {
        addChannel() {
            this.form.subscriptions.channels.push({
                title: '',
                link: '',
                id: ''
            })
        },

        removeChannel(index) {
            this.form.subscriptions.channels.splice(index, 1)
        }
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
            color: black !important;

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
