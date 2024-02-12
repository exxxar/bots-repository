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

    <div id="menu-success-1" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="305" data-menu-effect="menu-over" style="display: block; height: 305px;overflow:auto;">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-check-circle color-green1-dark"></i></h1>
        <h1 class="text-center mt-3 text-uppercase font-700">{{title || 'Системное'}} </h1>
        <p class="boxed-text-l" v-html="text">
        </p>
        <a data-dismiss="menu-success-1" class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-green1-light">Хорошо</a>
    </div>

    <div id="menu-warning-1" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="305" data-menu-effect="menu-over"
         style="display: block; height: 305px;overflow:auto;">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-times color-red2-dark"></i></h1>
        <h1 class="text-center mt-3 text-uppercase font-700">{{title || 'Системное'}} </h1>
        <p class="boxed-text-l" v-html="text">

        </p>
        <a data-dismiss="menu-warning-1" class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-red1-light">Хорошо</a>
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


        window.addEventListener("success", (e) => {
            this.title = e.detail.title || null
            this.text = e.detail.text || null
            $('#menu-success-1').showMenu();
        } );

        window.addEventListener("warning", (e) => {
            this.title = e.detail.title || null
            this.text = e.detail.text || null
            $('#menu-warning-1').showMenu();
        } );
    },
}
</script>
<style>
.snackbar-toast {
    z-index:1000;
}
</style>
