<template>
    <div
         style="min-height:100vh;"
         class="d-flex justify-content-center align-items-center flex-column"
         role="alert">

        <div class="loader-new">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
            <div class="bar4"></div>
            <div class="bar5"></div>
            <div class="bar6"></div>
        </div>
        <p class="fw-bold text-primary" style="margin-top:50px;">Загружаем...</p>
    </div>
</template>
<script>
export default {

}
</script>
<style lang="scss">
.loader-new {
    margin: 0 auto;
    width: 60px;
    height: 50px;
    text-align: center;
    font-size: 10px;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translateY(-50%) translateX(-50%);

    > div {
        height: 100%;
        width: 8px;
        display: inline-block;
        float: left;
        margin-left: 2px;
        -webkit-animation: delay 0.8s infinite ease-in-out;
        animation: delay 0.8s infinite ease-in-out;
    }

    .bar1 {
        background-color: #754fa0;
    }

    .bar2 {
        background-color: #09b7bf;
        -webkit-animation-delay: -0.7s;
        animation-delay: -0.7s;
    }

    .bar3 {
        background-color: #90d36b;
        -webkit-animation-delay: -0.6s;
        animation-delay: -0.6s;
    }

    .bar4 {
        background-color: #f2d40d;
        -webkit-animation-delay: -0.5s;
        animation-delay: -0.5s;
    }

    .bar5 {
        background-color: #fcb12b;
        -webkit-animation-delay: -0.4s;
        animation-delay: -0.4s;
    }

    .bar6 {
        background-color: #ed1b72;
        -webkit-animation-delay: -0.3s;
        animation-delay: -0.3s;
    }
}


@-webkit-keyframes delay {
    0%, 40%, 100% {
        -webkit-transform: scaleY(0.05)
    }
    20% {
        -webkit-transform: scaleY(1.0)
    }
}

@keyframes delay {
    0%, 40%, 100% {
        transform: scaleY(0.05);
        -webkit-transform: scaleY(0.05);
    }
    20% {
        transform: scaleY(1.0);
        -webkit-transform: scaleY(1.0);
    }
}
</style>
