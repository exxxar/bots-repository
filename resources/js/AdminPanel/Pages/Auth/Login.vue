<script setup>
import Checkbox from '@/AdminPanel/Components/Constructor/Deprecated/Start/Checkbox.vue';
import GuestLayout from '@/AdminPanel/Layouts/GuestLayout.vue';
import InputError from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputError.vue';
import InputLabel from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputLabel.vue';
import PrimaryButton from '@/AdminPanel/Components/Constructor/Deprecated/Start/PrimaryButton.vue';
import TextInput from '@/AdminPanel/Components/Constructor/Deprecated/Start/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const errors = page.props.errors; // Получаем ошибки из Laravel

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <section class="h-100 gradient-form" style="background-color: #eee;">
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

                                        <form @submit.prevent="submit">
                                            <p>Вход в аккаунт</p>

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

                                            <p v-if="errors.email" class="alert alert-danger my-3">{{ errors.email }}</p>

                                            <!-- Поле пароля -->
                                            <div class="form-floating mb-3">
                                                <input
                                                    id="password"
                                                    type="password"
                                                    v-model="form.password"
                                                    required
                                                    class="form-control"
                                                    placeholder="Пароль"
                                                />
                                                <label for="password">Пароль</label>
                                            </div>
                                            <InputError class="mt-2" :message="form.errors.password" />

                                            <!-- Чекбокс "Запомнить меня" -->
                                            <div class="block my-3">
                                                <label class="flex items-center">
                                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Запомнить меня</span>
                                                </label>
                                            </div>



                                            <p v-if="errors.system" class="alert alert-danger my-3">{{ errors.system }}</p>


                                            <!-- Кнопка входа -->
                                            <div class="text-center pt-1 mb-5 pb-1">
                                                <PrimaryButton class="ml-4 gradient-custom-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Войти в аккаунт
                                                </PrimaryButton>
                                                <Link
                                                    v-if="canResetPassword"
                                                    :href="route('password.request')"
                                                    class="text-muted ml-2">
                                                    Забыли пароль?
                                                </Link>
                                            </div>

                                            <!-- Ссылка на регистрацию -->
                                            <div class="d-flex align-items-center justify-content-center pb-4">
                                                <p class="mb-0 me-2">Нет аккаунта?</p>
                                                <Link :href="route('register')" class="btn btn-outline-danger ml-2">Создать новый</Link>
                                            </div>


                                        </form>

                                        <div class="d-flex justify-content-center">
                                            <Link :href="route('admin.login')" target="_blank" class="btn btn-link my-3"><i class="fa-brands fa-telegram"></i>
                                                 Войти через телеграм</Link>
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
