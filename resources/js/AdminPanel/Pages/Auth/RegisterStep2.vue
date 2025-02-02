<script setup>
import Checkbox from '@/AdminPanel/Components/Constructor/Deprecated/Start/Checkbox.vue';
import GuestLayout from '@/AdminPanel/Layouts/GuestLayout.vue';
import InputError from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputError.vue';
import InputLabel from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputLabel.vue';
import PrimaryButton from '@/AdminPanel/Components/Constructor/Deprecated/Start/PrimaryButton.vue';
import TextInput from '@/AdminPanel/Components/Constructor/Deprecated/Start/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    link: {
        type: String,
    },
});

</script>

<template>
    <GuestLayout>
        <section class="gradient-form" style="background-color: #eee;height:100vh;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                            <div class="row g-0">
                                <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                        <h4 class="mb-4">Добро пожаловать в систему управления Telegram-ботами!</h4>
                                        <p class="small mb-0">
                                            Здесь вы можете легко управлять своими Telegram-ботами: создавать новых, настраивать команды, анализировать статистику и многое другое. Войдите в систему, чтобы получить доступ ко всем возможностям платформы. Если у вас ещё нет аккаунта, зарегистрируйтесь — это займёт всего несколько минут.

                                            Начните прямо сейчас и сделайте своих ботов ещё умнее и полезнее! 🚀
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card-body p-md-5 mx-md-4">
                                        <div class="text-center">
                                            <div class="logo">NextIT</div>
                                            <h4 class="mt-1 mb-5 pb-1">Современные решения для бизнеса</h4>
                                        </div>


                                        <div class="d-flex justify-content-center">
                                            <a :href="link" class="btn btn-primary p-3 ">Завершить регистрацию в боте</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </GuestLayout>
</template>

<style>
.gradient-custom-2 {
    background: #fccb90;
    background: -webkit-linear-gradient(to right, #2489ee, #364cd8, #1a97c5, #1a1c9a);
    background: linear-gradient(to right, #2489ee, #364cd8, #021f64, #1a1c9a);
}

.logo {
    font-size: 64px;
    font-weight: lighter;
    background: linear-gradient(90deg, #007BFF, #00C6FF);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-family: Arial, sans-serif;
}
</style>
