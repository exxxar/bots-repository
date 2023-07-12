<template>
    <div id="notification-1"
         data-dismiss="notification-1"
         data-delay="3000"
         data-autohide="true"
         class="notification notification-ios bg-dark1-dark">
        <span class="notification-icon">
            <i class="fa fa-bell"></i>
            <em>Оповещение</em>
            <i data-dismiss="notification-1" class="fa fa-times-circle"></i>
        </span>
        <h1 class="font-15 color-white mb-n3"> {{title || 'Системное'}} </h1>
        <p class="pt-2">
            {{text || 'Ошибка отображения текста'}}
        </p>
    </div>


</template>
<script>
export default {
    data(){
      return {
          title:null,
          text:null,
      }
    },
    mounted() {
        window.addEventListener("notification", (e) => {
            this.title = e.detail.title || null
            this.text = e.detail.text || null
            $('#notification-1').toast('show');
        } );


    },
}
</script>
<style>
.snackbar-toast {
    z-index:1000;
}
</style>
