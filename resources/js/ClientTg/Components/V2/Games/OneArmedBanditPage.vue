<script setup>
import OneArmedBandit from "@/ClientTg/Components/V1/Other/OneArmedBandit.vue";
import CardGame from "@/ClientTg/Components/V2/Games/CardGame.vue";
</script>
<template>


    <div class="container g-2 py-3">

        <div class="card mb-2">
            <div class="card-body">
                <p class="mb-2 text-center fw-bold">
                    <strong class="text-primary">Внимание!</strong> Правила игры:
                </p>

                <ul style="padding: 0px 20px;" class="fw-bold">
                    <li>цена ставки меняется в зависимости от популярности игры (чем популярнее тем выше)</li>
                    <li>ставка берется из ваших баллов</li>
                    <li>за каждый выигрыш вы получаете игровые деньги, которые суммируются к вашему CashBack и используются для игровой ставки, но не начисляются вам как CashBack</li>
                    <li>каждый проигрыш списывает ваш реальный CashBack</li>
                    <li>одна из игровых комбинация может принести вам супер-приз - <strong>Пицца 50 см</strong></li>
                </ul>

            </div>
        </div>



<!--        <OneArmedBandit></OneArmedBandit>-->
        <CardGame></CardGame>
    </div>






</template>
