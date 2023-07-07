<script setup>
defineProps({
    bot: Object,
    rules: String,
});
</script>
<template>
    <div class="container">
        <div class="row" v-if="action">
            <div class="col-12 mb-2 mt-2" v-if="rules">
                <div class="card">
                    <div class="card-body">
                        <p>{{ rules }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-2 mt-2">
                <p
                    v-if="canPlay"
                    style="text-align: center;font-size: larger;">Ваши попытки: <strong>{{
                        action.current_attempts || 0
                    }}</strong> из <strong>{{
                        action.max_attempts || 1
                    }}</strong></p>

                <p style="text-align: center;font-size: larger;" v-else>Вы израсходовали все ваши попытки</p>
            </div>
            <div class="col-12 d-flex justify-content-center align-items-center "
                 v-if="canPlay&&!success">

                <div class="card mb-3">
                    <div class="card-header">
                        <h6>Выберите фотографию согласно задания квеста</h6>
                    </div>
                    <div class="card-body d-flex justify-content-start">

                        <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            +
                            <input type="file" id="photos"
                                   accept="image/*" @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>
                        <div class="mb-2 img-preview"
                             style="margin-right: 10px;"
                             v-if="photo">
                            <img v-lazy="getPhoto().imageUrl">
                            <div class="remove">
                                <a @click="photo=null"><i class="fa-regular fa-trash-can"></i></a>
                            </div>
                        </div>




                    </div>
                </div>
            </div>

            <div class="col-12 p-2" v-if="canPlay&&success">

                <div class="alert alert-info mb-2" role="alert">
                    <p>Вы успешно прошли квест</p>
                </div>
                <form v-on:submit="submit">
                    <h6 class="text-center">Укажите своё имя, как к Вам может обращаться менеджер?</h6>
                    <div class="input-group mb-2">

                        <input type="text" class="form-control text-center p-3"
                               placeholder="Петров Петр Семенович"
                               aria-label="winForm-name"
                               v-model="instaForm.name"
                               aria-describedby="winForm-name" required>
                    </div>


                    <h6 class="text-center">Введите свой номер телефона чтобы наш менеджер мог связаться с
                        Вами!</h6>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control p-3 text-center"
                               v-mask="'+7(###)###-##-##'"
                               v-model="instaForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="winForm-phone" aria-describedby="vipForm-phone" required>

                    </div>

                    <h6 class="text-center">Оставьте свой комментарий</h6>
                    <div class="input-group mb-2">
                        <textarea type="text" class="form-control p-3 text-center"
                               v-model="instaForm.comment"
                               aria-label="winForm-phone" aria-describedby="vipForm-phone" required>
                        </textarea>

                    </div>


                    <button class="btn btn-outline-primary p-3 w-100">
                        Получить приз
                    </button>


                </form>

            </div>

            <div class="col-12 p-2">
                <button
                    @click="closeQuest"
                    type="button" class="btn btn-outline-success p-3 w-100">
                    Вернуться в бота
                </button>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-12">
                <img v-lazy="'/images/load.gif'" class="w-100" style="object-fit:cover;" alt="">
            </div>
        </div>

    </div>

</template>
<script>
import {Roulette} from "vue3-roulette";

export default {
    name: "App",
    components: {
        Roulette,
    },
    data() {
        return {
            rouletteKey: 0,
            action: null,
            success:false,
            photo: null,
            instaForm: {
                comment: null,
                name: null,
                phone: null,
            },
            items: [],
        };
    },
    computed: {
        canPlay() {
            return this.action.current_attempts < this.action.max_attempts
        },
        tg() {
            return window.Telegram.WebApp;
        },
        tgUserId() {
            return JSON.parse(new URLSearchParams(window.Telegram.WebApp.initData).get("user")).id || null
        }
    },
    mounted() {
        this.prepare()
    },
    methods: {
        getPhoto() {
            return {imageUrl: URL.createObjectURL(this.photo)}
        },
        onChangePhotos(e) {
            const files = e.target.files
            this.photo = files[0]
            this.success = true
        },
        prepare() {
            return this.$store.dispatch("instagramQuestPrepare", {
                prepareForm: {
                    telegram_chat_id: this.tgUserId
                },
                botDomain: this.bot.bot_domain
            }).then((response) => {
                this.action = response
            })
        },
        submit() {
            let data = new FormData();

            Object.keys(this.instaForm)
                .forEach(key => {
                    const item = this.instaForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append("telegram_chat_id", this.tgUserId)

            data.append('photo', this.photo);

            this.$store.dispatch("instagramQuestResult", {
                instaForm: data,
                botDomain: this.bot.bot_domain
            }).then((response) => {

                this.winForm = {
                    comment: null,
                    name: null,
                    phone: null,
                }

                this.photo = null

                this.$notify({
                    title: "Instagram Quest",
                    text: "Вы успешно приняли участие в квесте! Наш менеджер свяжется с вами для дальнейших инструкций.",
                    type: 'success'
                });

                this.prepare()

            }).catch(err => {

            })

        },
        closeQuest() {
            this.tg.close()
        },

    },
};
</script>
<style>

.img-preview,
.photo-loader {
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 42px;
    background: white;
    border-radius: 10px;
    border: 1px lightgray solid;

    position: relative;

}

.img-preview img,
.photo-loader img {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.lime {
    color: lime;
}

.img-preview .remove {
    display: none;
}

.img-preview:hover .remove {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10;
    left: 0;
    top: 0;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 10px;
    cursor: pointer;
}

.img-preview:hover .remove a {
    font-size: 12px;
    color: white;
}
</style>
