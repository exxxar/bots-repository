<template>

    <form @submit.prevent="handleSubmit">


        <!-- Заголовок -->
        <div class="mb-2 form-floating">
            <input
                type="text"
                class="form-control"
                id="title"
                v-model="form.title"
                placeholder="Введите заголовок"
            />
            <label for="title">Заголовок</label>
        </div>

        <!-- Описание -->
        <div class="mb-2 form-floating">
        <textarea
            class="form-control"
            id="description"
            style="min-height:200px;"
            v-model="form.description"
            placeholder="Введите описание"
        ></textarea>
            <label for="description">Описание</label>
        </div>

        <div class="form-floating mb-2 file-floating">
            <input
                type="file"
                class="form-control"
                id="imageInput"
                accept="image/*"
                @change="onFileChange"
            >
            <label for="imageInput">Загрузить изображение</label>
        </div>

        <div v-if="preview" class="mb-2">
            <div class="card">
                <div class="card-body">
                    <img :src="preview" class="img-fluid rounded border img-preview">
                </div>
            </div>

        </div>

        <div v-if="form.image" class="mb-2">
            <div class="card">
                <div class="card-body">
                    <img
                        v-lazy="'/images-by-bot-id/'+bot.id+'/'+form.image"
                        class="img-fluid rounded border img-preview">
                </div>
            </div>

        </div>


        <button type="submit" class="btn btn-primary w-100 p-3">Отправить</button>

    </form>

</template>

<script>
export default {
    props: ["modelValue"],
    data() {
        return {
            file: null,
            preview: null,

            form: {
                title: "",
                description: "",
                image: "",
                config: {
                    excludes: [],
                    bg_color: 'transparent',
                },

            }
        };
    },

    computed: {
        bot() {
            return window.currentBot || null
        }
    },
    mounted() {
        if (this.bot.settings?.partners) {
            this.form = {...this.bot.settings?.partners}
        }
    },
    methods: {
        addContract() {
            this.form.legal_info.contracts.value.push({id: Date.now(), partner_id: 1})
        },
        addContact() {
            this.form.legal_info.contacts.value.push({id: Date.now(), partner_id: 1})
        }
        ,
        addDocument() {
            this.form.legal_info.documents.value.push({id: Date.now(), contract_id: 10})
        },
        onFileChange(e) {
            const file = e.target.files[0]
            this.file = file

            if (file) {
                this.preview = URL.createObjectURL(file)
            }

            this.$emit("select", file)
        },
        handleSubmit() {
            let data = new FormData();
            Object.keys(this.form)
                .forEach(key => {
                    const item = this.form[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.file) {
                data.append('file', this.file);
            }

            this.$store.dispatch("updateSelfPartner", {
                form: data
            }).then((response) => {
                this.$notify({
                    title: "Отлично!",
                    text: "Параметры успешно сохранены",
                    type: "success"
                });

                this.$emit("success")
            }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка сохранения параметров",
                    type: "error"
                });
            })
        }
    }
};
</script>

<style scoped>
.img-preview {
    height: 200px;
    width: 100%;
    object-fit: cover;
}
</style>
