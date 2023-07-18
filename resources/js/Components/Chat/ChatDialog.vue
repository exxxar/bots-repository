<template>
    <div class="row d-flex justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-6">
            <div class="card" id="chat2">
                <div class="card-header d-flex justify-content-between align-items-center p-3">
                    <h5 class="mb-0">
                        <img v-lazy="'/images/cashman.jpg'" style="width:50px; object-fit:cover; margin-right:10px; border-radius:50%;" alt="">
                        Cash<strong style="color:#7701ce;">Man</strong></h5>
                    <button type="button" class="btn btn-primary btn-sm" data-mdb-ripple-color="dark">Перейти к созданию чат-бота
                    </button>
                </div>
                <perfect-scrollbar
                    v-if="canEnter"
                    id="scroll-area"
                    class="card-body ps-container py-5 px-3" data-mdb-perfect-scrollbar="true"
                    style="position: relative; height: 400px">

                    <div

                        v-for="message in messages"
                        v-bind:class="{'justify-content-start':message.direction === 'incoming', 'justify-content-end':message.direction === 'outgoing' }"
                        class="d-flex flex-row mb-3">
                        <img v-lazy="'/images/cashman.jpg'"
                             alt="avatar 1" style="width: 45px; height: 100%;">
                        <div v-if="message.direction === 'incoming'">
                            <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;"
                               v-html="message.text" v-if="message.text"></p>
                            <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;"
                               v-html="message.caption" v-if="message.caption"></p>
                            <p class="small ms-3 mb-3 rounded-3 text-muted" v-if="message.created_at">
                                {{ message.created_at }}</p>

                            <img v-lazy="message.photo" v-if="message.photo" class="w-100 mb-1 mt-1" alt="">
                            <div class="chat-custom-menu px-2 py-2 " style="border-radius:10px;"
                                 v-if="message.keyboard">

                                <div v-for="row in message.keyboard"
                                     class="col-12 d-flex justify-content-between flex-wrap mb-1">

                                    <div class="d-flex justify-content-between w-100">
                                        <div class="chat-inline-btn w-100" v-for="col in row">
                                            <button
                                                v-if="col.callback_data"
                                                @click="send(col.callback_data || col.text, 1)"
                                                class="btn btn-outline-light w-100">{{ col.text || col || '-' }}
                                            </button>

                                            <a
                                                class="btn btn-outline-light w-100"
                                                v-if="col.web_app"
                                                :href="'https://t.me/'+domain"
                                                target="_blank">Только в Telegram</a>


                                            <a
                                                class="btn btn-outline-light w-100"
                                                v-if="col.url"
                                                :href="col.url" target="_blank">{{ col.text || col || '-' }}</a>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>


                        <div v-if="message.direction === 'outgoing'">


                            <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary" v-html="message.text"
                               v-if="message.text"></p>
                            <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary" v-html="message.caption"
                               v-if="message.caption"></p>
                            <p class="small me-3 mb-3 rounded-3 text-muted d-flex justify-content-end"
                               v-if="message.created_at">{{ message.created_at }}</p>


                        </div>
                    </div>



                </perfect-scrollbar>

                <div class=" w-100" v-if="!canEnter">
                    <form v-on:submit.prevent="login" class="p-2">
                        <div class="form-floating mb-3">
                            <input type="text"
                                   v-model="dataForm.user.first_name"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Ваше имя</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   v-model="dataForm.user.last_name"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Ваша фамилия</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text"
                                   v-mask="'+7(###)###-##-##'"
                                   v-model="dataForm.user.username"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Ваш номер телефона</label>
                        </div>

                        <button class="btn btn-outline-primary p-3 w-100">Войти</button>
                    </form>

                </div>

                <div class="card-footer ">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="dropup-center dropup">
                                <a class="ms-3 text-muted"
                                   type="button"
                                   id="bottom-menu"
                                   data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <div class="dropdown-menu w-100 chat-custom-dropdown-menu"    style="min-width:300px;">
                                    <button
                                        @click="send('/start')"
                                        v-if="buttons.length===0"
                                        class="btn btn-outline-light w-100 text-center">Начать
                                    </button>

                                    <perfect-scrollbar
                                        v-if="buttons.length>0"

                                        class="ps-keyboard-container buttons p-2">
                                        <div v-for="row in buttons" class="d-flex justify-content-between flex-wrap">

                                            <div class="d-flex justify-content-between w-100">
                                                <div class="chat-reply-btn w-100" v-for="col in row">
                                                    <div class="p-1">
                                                        <button
                                                            @click="send( col.text || col)"
                                                            class="btn btn-outline-light w-100" >{{ col.text || col || '-' }}
                                                        </button>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </perfect-scrollbar>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-muted d-flex justify-content-start align-items-center py-1 px-3">
                            <img v-lazy="'/images/cashman.jpg'"
                                 alt="avatar 3" style="width: 40px; height: 100%;">
                            <input type="text"
                                   @keydown.enter="send(null)"
                                   v-model="dataForm.message"
                                   class="form-control form-control-lg" id="exampleFormControlInput1"
                                   placeholder="Ваше сообщение">
                            <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                            <a class="ms-3 text-muted" href="#!"><i class="fas fa-smile"></i></a>



                            <a class="ms-3" href="#!"><i class="fas fa-paper-plane"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {v4 as uuidv4} from "uuid";

export default {
    props: ["domain"],
    watch: {
        messages: function (newVal) {
            this.$nextTick(function () {
                var container = this.$el.querySelector("#scroll-area");
                container.scrollTop = container.scrollHeight + 120;
            });
        },
    },

    data() {
        return {
            loaded: true,
            canEnter: false,
            messages: [],
            buttons: [],
            dataForm: {
                message: null,
                query: null,
                user: {
                    id: null,
                    first_name: null,
                    last_name: null,
                    username: null,
                }
            }
        }
    },
    methods: {
        login() {
            this.canEnter = true
            this.dataForm.user.id = uuidv4();
            this.send('/start')
        },
        send(action = null, type = 0) {

            this.loaded = false
            console.log(action)

            let text = action || this.dataForm.message

            if (action) {
                this.dataForm.message = null
                this.dataForm.query = action
            }


            if (type === 0)
                this.messages.push({
                    text: text, direction: 'outgoing', created_at: (() => {
                        let today = new Date();
                        let dd = String(today.getDate()).padStart(2, '0');
                        let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        let yyyy = today.getFullYear();
                        let min = today.getMinutes()
                        let hour = today.getHours()
                        let sec = today.getSeconds()
                        return yyyy + "-" + mm + "-" + dd + " " + hour + ":" + min + ":" + sec
                    })()
                });


            axios.post('/web/' + this.domain, this.dataForm)
                .then(resp => {
                    this.dataForm.message = null
                    this.dataForm.query = null

                    let data = resp.data;

                    data.forEach(item => {

                        let replyKeyboard = null;
                        if (item.reply_markup) {
                            let tmp = JSON.parse(item.reply_markup)

                            if (tmp.keyboard) {
                                this.buttons = tmp.keyboard;
                                this.is_keyboard = true;


                                let bottomMenu = document.querySelector("#bottom-menu")
                                bottomMenu.click();

                            }

                            replyKeyboard = tmp.inline_keyboard || null;
                        }


                        this.messages.push({
                            text: item.text || null,
                            direction: 'incoming',
                            keyboard: replyKeyboard,
                            photo: item.photo || null,
                            created_at: item.created_at || null
                        });

                        this.$nextTick(() => {
                            this.loaded = true
                        })

                    }).catch(() => {
                        this.$nextTick(() => {
                            this.loaded = true
                        })
                    })
                })
        },
    }
}
</script>

<style lang="scss">
#chat2 .form-control {
    border-color: transparent;
}

#chat2 .form-control:focus {
    border-color: transparent;
    box-shadow: inset 0px 0px 0px 1px transparent;
}

.divider:after,
.divider:before {
    content: "";
    flex: 1;
    height: 1px;
    background: #eee;
}

.ps-container {
    position: relative;
    height: 100%;
    max-height: inherit;
    padding: 0px 3px;
}

.chat-custom-menu,
.chat-custom-dropdown-menu {
    padding: 2px;
    background: linear-gradient(45deg, #2937f0, #9f1ae2) !important;
}
</style>
