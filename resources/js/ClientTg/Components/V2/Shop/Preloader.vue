<template>
    <div
        v-if="is_active"
        v-bind:class="{'preloader--light':colorScheme!=='dark'}"
        class="d-flex justify-content-center align-items-center flex-column preloader   "
        role="alert">

        <div class="stack">
            <div class="stack__card"></div>
            <div class="stack__card"></div>
            <div class="stack__card"></div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";


export default {
    data() {
        return {
            is_active: false,
            timer: null,
        }
    },
    /* watch: {
         'is_active': {
             handler: (newValue) => {
                 this.timer = setTimeout(()=>{
                     this.is_active = false
                 }, 1000)
             },
             deep: true
         },
     },*/
    computed: {
        colorScheme() {
            return window.Telegram.WebApp.colorScheme || 'dark'
        }
    },
    mounted() {

        /*    window.addEventListener("preloader-toggle", (event) =>{
                this.is_active = !this.is_active
            });

            window.addEventListener("preloader-hide", (event) =>{
                this.is_active = false
            });
    */
        window.addEventListener("preloader-show", (event) => {
            this.is_active = true
            this.timer = setTimeout(() => {
                this.is_active = false
            }, 1100)
        });

    },
    methods: {}
}
</script>
<style lang="scss">

.preloader {
    min-height: 100vh;
    z-index: 10000;
    position: fixed;
    width: 100%;
    height: 100vh;
    background: #17212b;
}

.stack {
    --stack-dur: 2s;
    --stack-delay: 0.05;
    --stack-spacing: 15%;

    background: #17212b;

    overflow: hidden;
    position: relative;
    width: 14em;
    height: 32em;

    &__card {
        aspect-ratio: 1;
        position: absolute;
        inset: 0;
        top: var(--stack-spacing);
        margin: auto;
        width: 70%;
        transform: rotateX(45deg) rotateZ(-45deg);
        transform-style: preserve-3d;

        &::before {
            animation: card var(--stack-dur) infinite;
            background-color: #17212b;
            border: 2px white solid;
            border-radius: 7.5%;
            box-shadow: -0.5em 0.5em 1.5em hsl(var(--hue) 90% 15% / 0.1);
            content: "";
            display: block;
            position: absolute;
            inset: 0;
        }

        &:nth-child(2) {
            top: 0;

            &::before {
                animation-delay: calc(var(--stack-dur) * (-1 + var(--stack-delay)));
                background-color: #17212b;
                border: 2px white solid;
            }
        }

        &:nth-child(3) {
            top: calc(var(--stack-spacing) * -1);

            &::before {
                animation-delay: calc(var(--stack-dur) * (-1 + var(--stack-delay) * 2));

                background: {
                    color: #00BCD4;
                    image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='SVGRepo_bgCarrier' stroke-width='0'%3E%3C/g%3E%3Cg id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round'%3E%3C/g%3E%3Cg id='SVGRepo_iconCarrier'%3E%3Cpath d='M20.5 7.27783L12 12.0001M12 12.0001L3.49997 7.27783M12 12.0001L12 21.5001M14 20.889L12.777 21.5684C12.4934 21.726 12.3516 21.8047 12.2015 21.8356C12.0685 21.863 11.9315 21.863 11.7986 21.8356C11.6484 21.8047 11.5066 21.726 11.223 21.5684L3.82297 17.4573C3.52346 17.2909 3.37368 17.2077 3.26463 17.0893C3.16816 16.9847 3.09515 16.8606 3.05048 16.7254C3 16.5726 3 16.4013 3 16.0586V7.94153C3 7.59889 3 7.42757 3.05048 7.27477C3.09515 7.13959 3.16816 7.01551 3.26463 6.91082C3.37368 6.79248 3.52345 6.70928 3.82297 6.54288L11.223 2.43177C11.5066 2.27421 11.6484 2.19543 11.7986 2.16454C11.9315 2.13721 12.0685 2.13721 12.2015 2.16454C12.3516 2.19543 12.4934 2.27421 12.777 2.43177L20.177 6.54288C20.4766 6.70928 20.6263 6.79248 20.7354 6.91082C20.8318 7.01551 20.9049 7.13959 20.9495 7.27477C21 7.42757 21 7.59889 21 7.94153L21 12.5001M7.5 4.50008L16.5 9.50008M16 18.0001L18 20.0001L22 16.0001' stroke='%23000000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3C/path%3E%3C/g%3E%3C/svg%3E");
                    position: center;
                    repeat: no-repeat;
                    size: 45% 45%;
                }

                border: 2px #17212b solid;
                filter: invert(1);
            }
        }
    }
}

.preloader--light {
    background: #ffffff;
    .stack {
        background: #ffffff;

        &__card {
            &::before {
                background-color: #ffffff;
                border: 2px #17212b solid;
            }

            &:nth-child(2) {
                &::before {
                    background-color: #ffffff;
                    border: 2px #17212b solid;
                }
            }

            &:nth-child(3) {
                &::before {
                    background: {
                        color: #00BCD4;
                        image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='SVGRepo_bgCarrier' stroke-width='0'%3E%3C/g%3E%3Cg id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round'%3E%3C/g%3E%3Cg id='SVGRepo_iconCarrier'%3E%3Cpath d='M20.5 7.27783L12 12.0001M12 12.0001L3.49997 7.27783M12 12.0001L12 21.5001M14 20.889L12.777 21.5684C12.4934 21.726 12.3516 21.8047 12.2015 21.8356C12.0685 21.863 11.9315 21.863 11.7986 21.8356C11.6484 21.8047 11.5066 21.726 11.223 21.5684L3.82297 17.4573C3.52346 17.2909 3.37368 17.2077 3.26463 17.0893C3.16816 16.9847 3.09515 16.8606 3.05048 16.7254C3 16.5726 3 16.4013 3 16.0586V7.94153C3 7.59889 3 7.42757 3.05048 7.27477C3.09515 7.13959 3.16816 7.01551 3.26463 6.91082C3.37368 6.79248 3.52345 6.70928 3.82297 6.54288L11.223 2.43177C11.5066 2.27421 11.6484 2.19543 11.7986 2.16454C11.9315 2.13721 12.0685 2.13721 12.2015 2.16454C12.3516 2.19543 12.4934 2.27421 12.777 2.43177L20.177 6.54288C20.4766 6.70928 20.6263 6.79248 20.7354 6.91082C20.8318 7.01551 20.9049 7.13959 20.9495 7.27477C21 7.42757 21 7.59889 21 7.94153L21 12.5001M7.5 4.50008L16.5 9.50008M16 18.0001L18 20.0001L22 16.0001' stroke='%23000000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3C/path%3E%3C/g%3E%3C/svg%3E");
                        position: center;
                        repeat: no-repeat;
                        size: 45% 45%;
                    }
                    border: 2px #ffffff solid;
                    filter: invert(1);
                }
            }
        }
    }
}

/* Animations */
$ease-in: cubic-bezier(0.32, 0, 0.67, 0);
$ease-out: cubic-bezier(0.33, 1, 0.68, 1);
$ease-in-out: cubic-bezier(0.65, 0, 0.35, 1);

@keyframes card {
    0%,
    100% {
        animation-timing-function: $ease-in-out;
        transform: translateZ(0);
    }
    11% {
        animation-timing-function: $ease-in;
        opacity: 1;
        transform: translateZ(0.125em);
    }
    34% {
        animation-timing-function: steps(1);
        opacity: 0;
        transform: translateZ(-12em);
    }
    48% {
        animation-timing-function: linear;
        opacity: 0;
        transform: translateZ(12em);
    }
    57% {
        animation-timing-function: $ease-out;
        opacity: 1;
        transform: translateZ(0);
    }
    61% {
        animation-timing-function: $ease-in-out;
        transform: translateZ(-1.8em);
    }
    74% {
        animation-timing-function: $ease-in-out;
        transform: translateZ(1.8em / 3);
    }
    87% {
        animation-timing-function: $ease-in-out;
        transform: translateZ(-1.8em / 9);
    }
}
</style>
