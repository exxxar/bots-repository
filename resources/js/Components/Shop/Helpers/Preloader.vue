<template>

    <div id="preloader" v-if="load">
        <div class="spinner-border color-highlight" role="status"></div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            load: false
        }
    },
    mounted() {
        window.addEventListener("preloader-toggle", (e) => {
            this.load = !this.load
        });

        window.addEventListener("preloader-hide", (e) => {
            this.load = false

        });

        window.addEventListener("preloader-show", (e) => {
            this.load = true

            setTimeout(()=>{
                this.load = false
            }, 500)
        });
    }
}
</script>
