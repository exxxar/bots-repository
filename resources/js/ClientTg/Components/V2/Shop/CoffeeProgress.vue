<template>
    <div v-if="settings.coffee.enabled" class="coffee-wrapper">

        <template v-if="!coffee">
            <p class="alert alert-light mb-2">
                Вы еще не начали собирать чашечки кофе:) Начнем?
            </p>
            <button
                type="button"
                @click="InitCoffee"
                class="btn btn-primary p-3 mb-2 w-100">Приступить
            </button>
        </template>

        <template v-if="coffee">
            <!-- Заголовок -->
            <h5 class="fw-bold mb-3">Ваш прогресс кофе</h5>

            <div class="row row-cols-3">
                <div class="col mb-2"
                     :key="'coffee-cup-'+index"
                     @click="onCupClick(cup)"
                     v-for="(cup, index) in cups">
                    <a href="javascript:void(0)"
                       :class="{ filled: cup.filled }"
                       class="btn w-100 p-3 coffee-cup-btn">
                        <i
                            style="font-size:26px;"
                            class="fa-solid fa-mug-hot"></i>
                    </a>
                </div>
            </div>

            <!-- Прогресс -->
            <div class="progress coffee-progress mb-3">
                <div
                    class="progress-bar progress-bar-striped bg-warning"
                    role="progressbar"
                    :style="{ width: progressPercent + '%' }"
                ></div>
            </div>

            <p class="text-muted small mb-3">
                Осталось {{ maxCups - usedCups }} чашек до бесплатной.
            </p>

            <!-- Кнопка обмена -->
            <button
                v-if="usedCups >= maxCups&&maxCups>0"
                class="btn btn-success w-100 mb-3"
                @click="toggleExchangeQR"
            >
                Обменять на бесплатный кофе
            </button>

            <!-- QR обмена -->
            <div v-if="showExchangeQR" class="qr-box text-center mb-3">
                <img v-lazy="markQR" class="qr-img" alt="QR для обмена">
                <p class="small text-muted">Покажите бариста</p>
            </div>

            <!-- QR отметки кружки -->
            <div v-if="showMarkQR" class="qr-box text-center mb-3">
                <img v-lazy="markQR" class="qr-img" alt="QR для отметки">
                <p class="small text-muted">Покажите администратору</p>
            </div>


        </template>

        <!-- Правила -->
        <button class="btn btn-outline-secondary w-100 mb-2 p-3" @click="toggleRules">
            Правила акции
        </button>

        <div v-if="showRules" class="alert alert-light border">
            <p class="mb-0" style="white-space: pre-line;">
                {{ settings.coffee.rules }}
            </p>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "CoffeeProgress",

    /*   props: {
           coffee: { type: Object, required: true },
           settings: { type: Object, required: true }
       },*/

    data() {
        return {
            intervalId: null,
            showRules: false,
            showExchangeQR: false,
            showMarkQR: false,
            selectedCupIndex: null,
            coffee: null,
            settings: {
                coffee: {
                    enabled: true,       // модуль включён
                    max: 7,              // всего нужно 7 кружек
                    rules: `
1. За каждую покупку кофе — 1 отметка.
2. После 7 кружек — 1 кофе бесплатно.
3. Отметки действуют 30 дней.
4. Бесплатный кофе нельзя обменять на деньги.
        `
                }
            },


        };
    },
    watch: {
        'self': {
            handler: function (newValue) {
                this.coffee = this.self.config?.coffee || null
            },
            deep: true
        },
    },
    computed: {
        ...mapGetters(['getSelf']),
        bot() {
            return window.currentBot
        },
        self() {
            return this.getSelf
        },
        maxCups() {
            return this.bot.settings.coffee?.max || 0;
        },

        usedCups() {
            return this.coffee?.count || 0;
        },

        cups() {
            return Array.from({length: this.maxCups}, (_, i) => ({
                index: i,
                filled: i < this.usedCups
            }));
        },

        progressPercent() {
            return Math.min((this.usedCups / this.maxCups) * 100, 100);
        },
        link() {
            return "https://t.me/" + this.bot.bot_domain + "?start=" + btoa("077" + this.self.telegram_chat_id + "request");
        },

        markQR() {
            if (this.selectedCupIndex === null) return null;
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        }
    },

    mounted() {

    },
    methods: {
        InitCoffee() {
            return this.$store.dispatch('initCoffee').then((resp) => {
                this.coffee = resp
            })
        },
        clearWaitInterval() {
            clearInterval(this.intervalId)
        },
        waitForAccept() {
            let waitIterationCount = 5
            const currentCount = this.coffee.count
            this.intervalId = setInterval(() => {
                this.InitCoffee().then(resp => {
                    waitIterationCount--;
                    if (currentCount !== this.coffee.count || waitIterationCount <= 0)
                        this.clearWaitInterval()
                });
            }, 10000)
        },
        onCupClick(cup) {
            if (cup.filled) return;

            this.selectedCupIndex = cup.index;
            this.showMarkQR = true;

            this.waitForAccept()
        },

        toggleRules() {
            this.showRules = !this.showRules;
        },

        toggleExchangeQR() {
            this.showExchangeQR = !this.showExchangeQR;
        }
    }
};
</script>

<style scoped>
.coffee-cup-btn {

    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 22px;
    cursor: pointer;
    transition: 0.2s;
    background: #f8f9fa;
}

.coffee-cup-btn.filled {
    background: #d63384;
    color: white;
    border-color: #b02a6b;
}

.coffee-cup-btn:hover {
    transform: scale(1.05);
}

.coffee-progress {
    height: 20px;
    border-radius: 10px;
    overflow: hidden;
}

.qr-img {
    width: 280px;
    height: 280px;
}
</style>
