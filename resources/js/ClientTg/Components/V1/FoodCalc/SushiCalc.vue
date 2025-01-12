<template>
    <div class="card card-style">
        <div class="content">
            <h1 class="text-center mt-4 mb-4">Собираем вкусный ролл</h1>
            <h2 class="text-center">
                <mark>Выбираем покрытие ролла</mark>
            </h2>
            <div class="my-4">

                    <div class="container-wrapper" v-for="fill in getFilling(11)">
                        <label class="container">{{fill.title}}<span
                            class="badge badge-weight">{{fill.weight}} гр.</span><span
                            class="badge">{{fill.price | currency}}</span>


                            <input v-if="fill.checked" checked="checked" type="checkbox"
                                   :disabled="fill.disabled||hasManyItems(fill.id)>1" v-model="fillings" :value="fill.id">
                            <input v-else type="checkbox"
                                   :disabled="fill.disabled||hasManyItems(fill.id)>1"
                                   v-model="pizza_base" :value="fill.id">
                            <span class="checkmark"></span>


                        </label>


                </div>
            </div>

            <div class="my-4">
                <div v-for="fill in getFilling(9)">
                    <div class="container-wrapper">
                        <label class="w-100 font-14">{{fill.title}}<span
                            class="badge badge-weight">{{fill.weight}} гр.</span><span
                            class="badge">{{fill.price | currency}}</span>


                            <input v-if="fill.checked" checked="checked" type="radio" name="roll_coating"
                                   :disabled="fill.disabled||hasManyItems(fill.id)>1" v-model="roll_coating"
                                   :value="fill.id">
                            <input v-else type="radio" name="roll_coating"
                                   :disabled="fill.disabled||hasManyItems(fill.id)>1"
                                   v-model="roll_coating" :value="fill.id">
                            <span class="checkmark"></span>


                        </label>

                    </div>

                </div>
            </div>
            <h2 class="text-center">
                <mark>А теперь выбираем начинку ролла...</mark>
            </h2>

            <div class="row mt-4">
                <div class="col-12 col-sm-12 col-md-6" v-for="fill in getFilling(10)">

                    <div class="container-wrapper">
                        <label class="container">{{fill.title}}<span
                            class="badge badge-weight">{{fill.weight}} гр.</span><span
                            class="badge">{{fill.price | currency}}</span>
                            <input v-if="fill.checked"
                                   checked="checked"
                                   type="checkbox" name="filling"
                                   :disabled="fill.disabled||hasManyItems(fill.id)>1||(fillings.length >= 6&&fillings.indexOf(fill.id) === -1)" v-model="fillings" :value="fill.id">
                            <input v-else type="checkbox" name="filling" :value="fill.id"
                                   :disabled="fill.disabled||hasManyItems(fill.id)>1||(fillings.length >= 6&&fillings.indexOf(fill.id) === -1)"
                                   v-model="fillings">
                            <span class="checkmark"></span>
                        </label>

                         <div v-if="!fill.disabled">
                              <div class="counter-wrapper" v-if="hasManyItems(fill.id)>0">
                                  <div class="counter">
                                      <button class="btn btn-counter" @click="removeItem(fill.id)">-</button>
                                      <p>{{hasManyItems(fill.id)}}</p>
                                      <button class="btn btn-counter" @click="addItem(fill.id)">+</button>
                                  </div>
                              </div>
                          </div>
                    </div>


                </div>

            </div>


            <hr>
            <p class="text-center mb-4"><em> <strong>Цена указана за 1 порцию роллов (вы заказали
                <mark> {{summary_count}}</mark>
                порций). Порция включает в себя 8 штук роллов общей массой <mark>{{weight}}</mark>
                грамм.</strong></em></p>
            <div class="row d-flex justify-content-center result">
                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                    <h3 class="text-center">Сколько таких роллов сделать?</h3>
                    <div class="summary">
                        <button class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark"
                                @click="decrementSummary">
                            <i class="fa-solid fa-minus font-16 p-3"></i>
                        </button>
                        <p>{{summary_count}}</p>
                        <button class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark"
                             @click="incrementSummary">
                            <i class="fa-solid fa-plus  font-16 p-3"></i>
                        </button>
                    </div>
                    <hr>

                    <div class="d-flex justify-content-center">
                        <ul>
                            <li>Вес: <strong>{{summary_weight}} грамм</strong></li>
                            <li>Цена: <strong>{{summary_price}} руб</strong></li>
                        </ul>
                    </div>

                    <p class="text-center  mt-4" v-if="message.length>0">
                        <mark class="text-white">{{message}}</mark>
                    </p>
                    <div class="d-flex justify-content-center flex-wrap mt-4">

                        <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-center">
                            <button class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100" :disabled="summary_count===0" @click="addToCart">
                                В корзину
                            </button>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 d-flex justify-content-center">
                            <button class="btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100" @click="clearCalc">
                                Очистить
                            </button>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            timer: null,
            message: '',
            roll_coating: 77,
            summary_count: 0,
            summary_weight: 0,
            summary_price: 0,
            price: 0,
            weight: 0,
            fillings: []
        }
    },
    watch: {
        roll_coating: function (newVal, oldVal) {
            if (oldVal)
                this.removeItem(oldVal)
            if (newVal)
                this.addItem(newVal)
        },
        summary_count: function (val) {
            this.summary_price = this.price * this.summary_count
            this.summary_weight = this.weight * this.summary_count
        },
        fillings: function (val) {

        }
        ,
        products: function (newVal, oldVal) {
            return newVal
        },
    },
    computed: {
        ...mapGetters(['getIngredientsFilling','getIngredientsCategory','getAllFillings']),
        products() {
            return this.$store.getters.cartProducts;
        }
    },
    mounted() {

    },
    methods: {
        addToCart() {

        },
        clearCalc() {

        },

        decrementSummary() {

        },
        incrementSummary() {

        },
        hasManyItems(id) {
            return  this.getAllFillings.filter(item => item === id).length
        },
        removeItem(id) {

        },
        addItem(id) {

        },
        getCategory(id) {
            return  this.getIngredientsCategory(id)
        },
        getFilling(type) {
            return this.getIngredientsFilling(type)

        },
        sendMessage(message) {

        },



    }
}
</script>
<style lang="scss" >

hr {
    width: 100%;
    height: 1px;
    /* background: darkred; */
    border-top: 1px darkred solid;
    margin-top: 10px;
    margin-bottom: 10px;
    padding: 10px;
}


h2,
h3 {
    mark {
        background: transparent;
        border-bottom:2px #d50c0d solid;
        //color: white;
    }
}

.container-wrapper {
    display: flex;
    justify-content: space-between;
}

.result {
    ul {
        width: 150px;

        li {
            width: 100%;
            display: flex;
            justify-content: space-between;

        }
    }

    .summary {
        width: 100%;
        display: flex;
        padding: 10px;
        justify-content: space-around;
        align-items: center;

        p {
            padding: 10px;
        }

        .btn-counter {
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: darkorange;
            font-size: 20px;
            font-weight: bolder;
            color: #4e3d03;
        }
    }
}

.counter-wrapper {
    display: inline-block;

    .counter {
        display: flex;
        justify-content: space-between;
        width: 100px;
        padding: 5px;

        .btn-counter {
            width: 25px;
            height: 25px;
            background: darkorange;
            color: white;
            padding: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
}

.tabs-component {
    margin: 0em 0;
}

.tabs-component-tabs {
    border: solid 1px #ddd;
    border-radius: 6px;
    margin-bottom: 5px;
}

@media (min-width: 700px) {
    .tabs-component-tabs {
        border: 0;
        align-items: stretch;
        display: flex;
        justify-content: flex-start;
        margin-bottom: -1px;
    }
}

.tabs-component-tab {
    color: #999;
    font-size: 14px;
    font-weight: 600;
    margin-right: 0;
    list-style: none;
}

.tabs-component-tab:not(:last-child) {
    border-bottom: dotted 1px #ddd;
}

.tabs-component-tab:hover {
    color: #666;
}

.tabs-component-tab.is-active {
    color: #000;
}

.tabs-component-tab.is-disabled * {
    color: #cdcdcd;
    cursor: not-allowed !important;
}

@media (min-width: 700px) {
    .tabs-component-tab {
        background-color: #fff;
        border: solid 1px #ddd;
        border-radius: 3px 3px 0 0;
        margin-right: .5em;
        transform: translateY(2px);
        transition: transform .3s ease;
    }

    .tabs-component-tab.is-active {
        border-bottom: solid 1px #fff;
        z-index: 2;
        transform: translateY(0);
    }
}

.tabs-component-tab-a {
    align-items: center;
    color: inherit;
    display: flex;
    padding: .75em 1em;
    text-decoration: none;
}

.tabs-component-panels {
    padding: 4em 0;
}

@media (min-width: 700px) {
    .tabs-component-panels {
        border-top-left-radius: 0;
        background-color: #fff;
        border: solid 1px #ddd;
        border-radius: 0 6px 6px 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, .05);
        padding: 4em 2em;
    }
}


</style>
