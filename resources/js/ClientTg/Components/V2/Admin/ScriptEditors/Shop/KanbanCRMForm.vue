<template>
    <template v-if="loaded_params">
        <!-- Активность -->
        <div class="form-check form-switch mb-2">
            <input
                class="form-check-input"
                type="checkbox"
                v-model="form.kanban.is_active"
            >
            <label class="form-check-label">
                Отправлять данные в crm:
                <span class="fw-bold text-primary" v-if="form.kanban.is_active">да</span>
                <span class="fw-bold text-primary" v-else>нет</span>
            </label>
        </div>


        <div class="form-floating mb-2">
            <input
                class="form-control"
                placeholder=" "
                v-model="form.kanban.board_uuid"
                @blur="validateBoardUuid"
                required
            >
            <label>Id доски</label>
        </div>

        <div class="form-floating mb-2">
            <input
                class="form-control"
                placeholder=" "
                v-model="form.kanban.token"
                required
            >
            <label>Токен (ключ)</label>
        </div>


    </template>
</template>

<script>
export default {
    name:'KanbanCRMForm',
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
            if (!this.modelValue.kanban)
                this.form.subscriptions = {
                    is_active: false,
                    board_uuid: null,
                    token: null,

                }
            this.loaded_params = true
        })
    },
    methods: {
        validateBoardUuid() {
            if (!this.form.kanban.board_uuid) return
            this.form.kanban.board_uuid = this.normalizeBoardUuid(this.form.kanban.board_uuid)
        },
        normalizeBoardUuid(link) {
            if (!link) return ''

            // убираем https://crm.your-cashman.com/board/
            link = link.replace(/^https?:\/\/crm\.your-cashman\.com\/board\//, '')

            // убираем возможные слэши в начале/конце
            link = link.replace(/^\/+|\/+$/g, '')

            return link
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
