import {useStore} from 'vuex'

let sharedState = {
    spent_time_counter: 0,
}

export function cashbackLimit() {
    const store = useStore()

    const self = store.getters.getSelf || 0
    const summaryPrice = store.getters.cartTotalPrice || 0
    const bot = window.currentBot


    const maxUserCashback = self.cashBack ? self.cashBack.amount : 0
    let botCashbackPercent = bot.max_cashback_use_percent || 0

    let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

    return Math.min(cashBackAmount, maxUserCashback) || 0
}

export function canBy() {
    const bot = window.currentBot
    const settings = bot.settings
    if (!window.isCorrectSchedule(bot.company.schedule))
        return true

    return settings?.can_buy_after_closing || true
}

export function getSpentTimeCounter() {
    return parseInt(sharedState.spent_time_counter || '0')
}

export function checkTimer(){
    const counter = parseInt(localStorage.getItem("cashman_self_product_delivery_counter") || '0')

    if (counter > 0) {
        startTimer(counter)
        return true
    }
    return false
}

export function startTimer(time) {
    sharedState.spent_time_counter = parseInt(time) != null ? Math.min(parseInt(time), 10) : 10;

    let counterId = setInterval(() => {
            if (sharedState.spent_time_counter > 0)
                sharedState.spent_time_counter--
            else {
                clearInterval(counterId)
                sharedState.spent_time_counter = null
            }
            localStorage.setItem("cashman_self_product_delivery_counter", sharedState.spent_time_counter)

            window.dispatchEvent(new CustomEvent('trigger-spent-timer', {'detail': sharedState.spent_time_counter}));
        }, 1000
    )

}
