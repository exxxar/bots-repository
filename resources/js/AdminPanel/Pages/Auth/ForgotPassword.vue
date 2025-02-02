<script setup>
import GuestLayout from '@/AdminPanel/Layouts/GuestLayout.vue';
import InputError from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputError.vue';
import InputLabel from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputLabel.vue';
import PrimaryButton from '@/AdminPanel/Components/Constructor/Deprecated/Start/PrimaryButton.vue';
import TextInput from '@/AdminPanel/Components/Constructor/Deprecated/Start/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <section class="gradient-form" style="background-color: #eee;height:100vh;">
            <div class="container py-5">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-xl-10">
                        <div class="card rounded-3 text-black">
                            <div class="row g-0">
                                <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                        <h4 class="mb-4">Восстановление доступа к аккаунту</h4>
                                        <p class="small mb-0">
                                            Забыли пароль? Не беда! Укажите вашу почту, и мы отправим вам ссылку для сброса пароля. Следуйте инструкциям в письме, чтобы восстановить доступ к вашему аккаунту.
                                            <br><br>
                                            Если у вас возникли трудности, свяжитесь с нашей поддержкой — мы всегда готовы помочь!
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card-body p-md-5 mx-md-4">
                                        <div class="text-center">
                                            <div class="logo">NextIT</div>
                                            <h4 class="mt-1 mb-5 pb-1">Современные решения для бизнеса</h4>
                                        </div>

                                        <form @submit.prevent="submit">
                                            <p>Восстановление пароля</p>

                                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                                {{ status }}
                                            </div>

                                            <!-- Поле почты -->
                                            <div class="form-floating mb-3">
                                                <input
                                                    id="email"
                                                    type="email"
                                                    v-model="form.email"
                                                    required
                                                    autofocus
                                                    class="form-control"
                                                    placeholder="name@example.com"
                                                />
                                                <label for="email">Почта</label>
                                            </div>
                                            <InputError class="mt-2" :message="form.errors.email" />

                                            <!-- Кнопка отправки ссылки -->
                                            <div class="text-center pt-1 mb-5 pb-1">
                                                <PrimaryButton class="ml-4 gradient-custom-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Отправить ссылку для сброса
                                                </PrimaryButton>
                                            </div>

                                            <!-- Ссылка на вход -->
                                            <div class="d-flex align-items-center justify-content-center pb-4">
                                                <p class="mb-0 me-2">Вспомнили пароль?</p>
                                                <a :href="route('login')" class="btn btn-outline-danger ml-2">Войти</a>
                                            </div>
                                        </form>
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
