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

