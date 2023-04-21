<template>


                <h3>Заголовок чата</h3>
                <a @click="close()"><i class="fa fa-times" aria-hidden="true"></i></a>

                <perfect-scrollbar class="scroll-area">
                    <ul>
                        <li v-for="message in messages">
                            <div v-if="message.type===0">
                                <div class="incoming" v-if="message.result">
                                    <p v-html="message.text"></p>
                                </div>
                                <div class="outcoming" v-else>
                                    <p v-html="message.text"></p>
                                </div>
                            </div>

                            <div v-if="message.type===1">
                                <div class="incoming">
                                    <img :src="message.photo" alt="">
                                    <p v-html="message.text"></p>
                                </div>
                            </div>

                            <div v-if="message.type===2">
                                <div class="incoming">
                                    <ul>
                                        <li v-for="row in message.keyboard" class="row">
                                            <div class="btn-wrapper" v-for="button in row">
                                                <button v-if="button.callback_data" @click="buttonSend(button.callback_data)">{{button.text}}</button>
                                                <a v-if="button.url" :href="button.url" target="_blank">{{button.text}}</a>
                                            </div>

                                        </li>
                                    </ul>
                                </div>
                            </div>


                        </li>
                    </ul>
                </perfect-scrollbar>

                <form v-on:submit.prevent="send" class="message">
                    <input type="text" placeholder="text" v-model="dataForm.message">
                    <div class="mini-btn">
                        <button type="submit">
                          Отправть
                        </button>
                    </div>
                </form>
                <div class="buttons" v-if="buttons.length>0&&is_keyboard">


                        <ul>
                            <li v-for="row in buttons" class="row">
                                <div class="col-12 d-flex justify-content-center">
                                    <button
                                        class="btn btn-outline-primary w-100 m-1"
                                        v-for="button in row"
                                        @click="buttonSend(button)">{{button.text}}</button>
                                </div>
                            </li>
                        </ul>



                </div>


</template>

<script>


export default {
        props:["domain"],
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
            is_active: true,
            is_keyboard: true,
            text: '/start',
            messages: [],
            buttons: [],
            dataForm:{
                message: null,
                query: null,
                user:{
                    id:484698703,
                    first_name:"Test",
                    last_name:"test",
                    username:"eessdsdsd",
                }
            }
        }
    },
    methods: {
        buttonSend(button) {
            this.dataForm.message = null;
            this.dataForm.query = button.text || button || null
            this.send();
        },
        send() {

           // this.messages.push({text: message, result: false, type: 0});

            axios.post('/web/'+this.domain, this.dataForm).then(resp => {

                console.log("data",resp)
                let data = resp.data;
                data.forEach(item => {
                    if (item.text)
                        this.messages.push({text: item.text, result: true, type: 0, keyboard: null, photo: null});
                    if (item.photo)
                        this.messages.push({text: item.caption, result: true, type: 1, photo: item.photo})
                    let tmp = JSON.parse(item.reply_markup);
                    if (item.reply_markup) {
                        if (tmp.keyboard) {
                            this.buttons = tmp.keyboard;
                            this.is_keyboard = true;
                        }
                        if (tmp.inline_keyboard) {
                            console.log(tmp.inline_keyboard)
                            this.messages.push({result: true, type: 2, keyboard: tmp.inline_keyboard})
                        }
                    }
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
.ps-container {
    position: relative;
    height: 100%;
    max-height: inherit;
}
.chat-container {
    width: auto;
    height: auto;
    border-radius: 5px;
    display: inline-block;
    position: fixed;
    bottom: 50px;
    right: 50px;
    .chat {
        height: 100%;
        max-width: 280px;
        min-width: 280px;
        box-shadow: 2px 2px 3px 0px #f6f6f6;
        border-radius: 5px;
        //border: 3px #38a7ff solid;
        background: #ecf7fc;;
        .header {
            background: #38a7ff;
            border-radius: 5px 5px 0px 0px;
            padding: 3px;
            display: flex;
            justify-content: space-between;
            min-height: 5px;
            align-items: center;
            h3 {
                color: white;
                font-size: 10px;
                font-weight: 800;
            }
            a {
                color: white;
                text-decoration: none;
                font-size: 10px;
                padding: 0px 5px;
            }
        }
        .footer {
            height: auto;
            .message {
                display: flex;
                justify-content: center;
                position: relative;
                input {
                    width: 100%;
                    padding: 10px;
                    border: 1px #f6f6f6 solid;
                }
                .mini-btn {
                    position: absolute;
                    right: 5px;
                    top: 5px;
                    display: flex;
                    justify-content: space-around;
                    opacity: 0.1;
                    transition: 0.3s;
                    &:hover {
                        opacity: 1;
                    }
                    button {
                        width: 30px;
                        height: 30px;
                        border: none;
                        background: #38a7ff;
                        border-radius: 5px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-left: 5px;
                        color: white;
                    }
                }
            }
        }
    }
}
.body {
    width: 100%;
    height: 300px;
    overflow: hidden;
    transition: .3s;
    ul {
        list-style: none;
        margin: 0;
        padding: 10px;
        box-sizing: border-box;
        li {
            .outcoming {
                display: flex;
                justify-content: flex-end;
                & > div {
                    background: #ddf5ff;
                    margin-bottom: 5px;
                    padding: 5px 10px;
                    box-sizing: border-box;
                    border-radius: 5px;
                    p {
                        padding: 5px;
                        margin: 0px;
                        font-size: 12px;
                    }
                }
            }
            .incoming {
                display: flex;
                justify-content: flex-start;
                ul {
                    width: 100%;
                    li {
                        display: flex;
                        .btn-wrapper {
                            width: 100%;
                            a,
                            button {
                                padding: 5px;
                                width: 100%;
                                margin: 1px;
                                background: aliceblue;
                                border: 1px lightblue solid;
                                border-radius: 5px;
                                font-size: 10px;
                                text-decoration:none;
                                color:black;
                                display: block;
                                text-align: center;
                            }
                        }
                    }
                }
                & > div {
                    background: #e9f5ff;
                    margin-bottom: 5px;
                    padding: 5px 10px;
                    box-sizing: border-box;
                    border-radius: 5px;
                    p {
                        padding: 5px;
                        margin: 0px;
                        font-size: 12px;
                    }
                }
            }
        }
    }
}
.buttons {
    overflow: hidden;
    max-height: 140px;
    ul {
        width: 100%;
        list-style: none;
        margin: 0;
        padding: 0;
        li.row {
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            .btn-wrapper {
                width: 100%;
                box-sizing: border-box;
                display: flex;
                height: 35px;
                button {
                    width: 100%;
                    margin: 1px;
                    background: aliceblue;
                    border: 1px lightblue solid;
                    border-radius: 5px;
                    font-size: 10px;
                }
            }
        }
    }
}
</style>
