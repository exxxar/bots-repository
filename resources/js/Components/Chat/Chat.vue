<template>

    <div class="chat-window d-flex justify-content-between align-items-center flex-column">
        <perfect-scrollbar
            v-if="canEnter"
            class="ps-container w-100">
            <div class="message-card"
                 v-for="message in messages"
                 v-bind:class="{'incoming':message.direction === 'incoming', 'outgoing':message.direction === 'outgoing' }"
            >
                <div class="message-card-body">
                    <p v-html="message.text" v-if="message.text"></p>
                    <p v-html="message.caption" v-if="message.caption"></p>
                    <p v-if="message.created_at">{{ message.created_at }}</p>
                    <img v-lazy="message.photo" v-if="message.photo" class="w-100 mb-1 mt-1" alt="">
                    <div class="inline-keyboard w-100 " v-if="message.keyboard">

                        <div v-for="row in message.keyboard" class="d-flex justify-content-between flex-wrap">

                            <div class="d-flex justify-content-between w-100">
                                <div class="chat-inline-btn w-100" v-for="col in row">
                                    <button
                                        v-if="col.callback_data"
                                        @click="send(col.callback_data || col.text, 1)"
                                        class="btn btn-outline-light w-100">{{ col.text || col || '-' }}
                                    </button>

                                    <a
                                        class="btn btn-outline-light w-100"
                                        v-if="col.url"
                                        :href="col.url" target="_blank">{{ col.text || col || '-' }}</a>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </perfect-scrollbar>
        <div
            v-if="canEnter"
            class="footer">
            <div class="menu">
                <div class="dropdown dropup w-100 ">
                    <button class="chat-menu-btn w-100"
                            type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <div class="dropdown-menu w-100 chat-custom-dropdown-menu">
                        <button
                            @click="send('/start')"
                            v-if="buttons.length===0"
                            class="btn btn-outline-light w-100 text-center">Начать
                        </button>

                        <perfect-scrollbar
                            v-if="buttons.length>0"
                            class="ps-keyboard-container buttons ">
                            <div v-for="row in buttons" class="d-flex justify-content-between flex-wrap">

                                <div class="d-flex justify-content-between w-100">
                                    <div class="chat-reply-btn w-100" v-for="col in row">
                                        <button
                                            @click="send( col.text || col)"
                                            class="btn btn-outline-light w-100">{{ col.text || col || '-' }}
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </perfect-scrollbar>
                    </div>
                </div>
            </div>
            <div class="message">
                <input type="text"
                       :disabled="!loaded"
                       class="form-control w-100"
                       placeholder="text"
                       @keydown.enter="send(null)"
                       v-model="dataForm.message">
            </div>

        </div>
        <div class="p-1" v-if="!canEnter">
            <form v-on:submit.prevent="login" class="pt-2 pb-2 ">
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
.chat-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #2196F3;
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    border: 2px #2196F3 solid;
    box-shadow: 1px 1px 3px 1px #dbdbdb;
}

.chat-window {
    position: relative;
    height: 100vh;
    padding: 18px 0;
    margin: 0;
    box-sizing: border-box;

    .ps-container {
        position: relative;
        height: 100%;
        max-height: inherit;
        padding: 0px 3px;

        .message-card {
            width: 100%;
            background-color: #f8f8f8;
            margin-bottom: 5px;
            border-radius: 10px;

            &.incoming {
                background: linear-gradient(45deg, #2937f0, #9f1ae2) !important;
                color: white;


            }

            &.outgoing {
                background-color: #d0d0d0;
            }

            .message-card-body {
                padding: 5px;
                font-size: 10px;

                p {
                    margin: 0;
                }
            }
        }


    }

    & > .footer {
        width: 100%;
        height: 60px;
        background: white;
        padding: 5px;
        box-sizing: border-box;
        border-top: 2px #3F51B5 solid;

        input {
            border-radius: 25px;
            font-size: 10px;
            padding: 13px;
        }

        .chat-menu-btn {

            background: none;
            border: none;
            margin: 0;
            padding: 0;

        }

        .ps-keyboard-container {
            position: relative;
            height: auto;
            max-height: 140px;
        }

        .message-form {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-right: 10px;

            input[type="text"] {
                width: 100%;
                border-radius: 25px;
            }

            .mini-btn {
                padding: 5px;
                box-sizing: border-box;
                display: flex;
            }
        }
    }

}

.chat-reply-btn,
.chat-inline-btn {

    padding: 1px;

    &:nth-last-child {
        padding-right: 0;
    }

    &:nth-of-type(1) {
        padding-left: 0;
    }

    button {
        font-size: 8px;
    }
}

.chat-reply-btn {
    button {
        font-size: 10px;
    }
}

.chat-custom-dropdown-menu {
    padding: 2px;
    background: linear-gradient(45deg, #2937f0, #9f1ae2) !important;
}

</style>
