<template>

    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <h4>Правила</h4>
                <p
                    class="alert-light alert"
                    v-if="rules">
                    {{ rules }}
                </p>

                <p
                    class="alert-light alert"
                    v-else>
                    Вам необходима загрузить скриншот и добавить к нему комментарий
                </p>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-center">
                            <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                                +
                                <input type="file" id="photos"
                                       multiple
                                       accept="image/*" @change="onChangePhotos"
                                       style="display:none;"/>

                            </label>

                            <div class="mb-2 mr-2 img-preview"
                                 v-if="photos.length>0"
                                 v-for="(img, index) in photos">
                                <img v-lazy="getPhoto(img).imageUrl"/>

                                <div class="remove">
                                    <a href="javascript:void(0)" @click="removePhoto(index)">Удалить</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-2">
                            <textarea class="form-control"
                                      v-model="form.comment"
                                      placeholder="Введите текст" id="floatingTextarea"
                                      style="height: 150px"></textarea>
                            <label for="floatingTextarea">Ваше сообщение</label>
                        </div>
                        <button
                            v-if="photos.length>0"
                            @click="submit"
                            type="button"
                            class="btn btn-primary p-3 w-100">
                            Отправить
                        </button>


                    </div>
                </div>


            </div>

        </div>
    </div>


</template>
<script>

export default {
    name: "App",
    data() {
        return {
            rules: null,
            sending: false,
            photos: [],
            form: {
                comment: null,
                name: null,
                phone: null,
            },
        };
    },
    computed: {},
    mounted() {
        this.loadServiceData()
    }
    ,
    methods: {


        loadServiceData() {
            return this.$store.dispatch("requestPhotoLoadData").then((response) => {
                this.rules = response.rules
            })
        },

        removePhoto() {
            this.photo = null
        },
        getPhoto() {
            return {imageUrl: URL.createObjectURL(this.photo)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },
        submit() {
            let data = new FormData();

            Object.keys(this.form)
                .forEach(key => {
                    const item = this.form[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.photos.length > 0)
                for (let i = 0; i < this.photos.length; i++) {
                    data.append('photos[]', this.photos[i]);
                }

            this.$store.dispatch("requestPhotoResult", {
                form: data,
            }).then((response) => {

                this.sending = false
                this.form = {
                    comment: null,
                    name: null,
                    phone: null,
                }

                this.photos = []

                this.$notify({
                    title: 'Отлично!',
                    text: "Наш менеджер свяжется с вами для дальнейших инструкций.",
                    type: "success"
                })

            }).catch(err => {

            })

        },
    },
}
;
</script>
<style lang="scss" scoped>

.img-preview {
    position: relative;

    .photo-loader {
        width: 100px;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        background: transparent;
        border-radius: 10px;
        border: 1px lightgray solid;
        position: relative;
    }

    .remove {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #0000009e;
    }
}
</style>
