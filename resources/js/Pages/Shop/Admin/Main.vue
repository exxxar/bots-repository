<script setup>
defineProps({
    bot: {
        type: Object,
    },
});

</script>
<template>
    <div class="alert alert-info" role="alert">
        Данная страница Админ Панели магазина всё еще в разработке
    </div>
</template>

<script>
export default {
    created() {
        window.currentBot = this.bot.data
    }
}
</script>

