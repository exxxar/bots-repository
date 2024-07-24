<script setup>
import OneArmedBandit from "@/ClientTg/Components/V1/Other/OneArmedBandit.vue";
</script>
<template>


    <div class="card card-style">
        <div class="content">
            <p class="mb-2 text-center font-18">
                <strong>Внимание!</strong> Правила игры:
            </p>

                <ul style="padding: 0px 20px;">
                    <li>цена ставки меняется в зависимости от популярности игры (чем популярнее тем выше)</li>
                    <li>ставка берется из вашего CashBack</li>
                    <li>за каждый выигрыш вы получаете игровые деньги, которые суммируются к вашему CashBack и используются для игровой ставки, но не начисляются вам как CashBack</li>
                    <li>каждый проигрыш списывает ваш реальный CashBack</li>
                    <li>одна из игровых комбинация может принести вам супер-приз - <strong>Пицца 50 см</strong></li>
                </ul>

        </div>
    </div>
    <div class="card card-style">
        <div class="content">
            <OneArmedBandit></OneArmedBandit>
        </div>
    </div>



</template>
