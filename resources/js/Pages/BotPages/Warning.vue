<script setup>
defineProps({
    message: String,
});
</script>
<template>
    <div class="alert alert-info" role="alert">
        {{message||'Вы уже использовали данный сервис. У вас не осталось попыток'}}
    </div>
</template>
<script>


export default {

};
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 20px;
    font-weight: 900;
}
</style>
