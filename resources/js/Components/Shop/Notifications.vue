<template>
    <div id="notification-1"
         data-dismiss="notification-1"
         data-delay="3000"
         data-autohide="true"
         class="notification notification-ios bg-dark1-dark">
        <span class="notification-icon">
            <i class="fa fa-bell"></i>
            <em>Enabled</em>
            <i data-dismiss="notification-1" class="fa fa-times-circle"></i>
        </span>
        <h1 class="font-15 color-white mb-n3"> {{title}} </h1>
        <p class="pt-2">
            {{text}}
        </p>
    </div>

    <div id="menu-success-2" class="menu menu-box-bottom menu-box-detached bg-green1-dark rounded-m"
         data-menu-height="305"
         data-menu-effect="menu-over">
        <h1 class="text-center mt-4"><i class="fa fa-3x fa-check-circle color-white shadow-xl rounded-circle"></i></h1>
        <h1 class="text-center mt-3 text-uppercase font-700 color-white">All's Good</h1>
        <p class="boxed-text-l color-white opacity-70">
            You can continue with your previous actions.<br> Easy to attach these to success calls.
        </p>
        <a href="#" class="close-menu btn btn-m btn-center-m button-s shadow-l rounded-s text-uppercase font-900 bg-white">Great</a>
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

        window.addEventListener("success_box", (e) => {
            this.title = e.detail.title || null
            this.text = e.detail.text || null
            $('#menu-success-2').showMenu();
        } );


    },
}
</script>
