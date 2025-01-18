<script setup>

import Checkbox from '@/AdminPanel/Components/Constructor/Deprecated/Start/Checkbox.vue';
import GuestLayout from '@/AdminPanel/Layouts/GuestLayout.vue';
import InputError from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputError.vue';
import InputLabel from '@/AdminPanel/Components/Constructor/Deprecated/Start/InputLabel.vue';
import PrimaryButton from '@/AdminPanel/Components/Constructor/Deprecated/Start/PrimaryButton.vue';
import TextInput from '@/AdminPanel/Components/Constructor/Deprecated/Start/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
                                        <h4 class="mb-4">We are more than just a company</h4>
                                        <p class="small mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                            exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>


                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card-body p-md-5 mx-md-4">

                                        <div class="text-center">
                                            <div class="logo">NextIT</div>
<!--                                            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp"
                                                 style="width: 185px;" alt="logo">-->
                                            <h4 class="mt-1 mb-5 pb-1">Современные решения для бизнеса</h4>
                                        </div>

                                        <form @submit.prevent="submit">
                                            <p>Вход в аккаунт</p>

                                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                                {{ status }}
                                            </div>

                                            <div class="form-outline mb-4">
                                                <input id="email"
                                                       type="email"
                                                       v-model="form.email"
                                                       required
                                                       autofocus
                                                       autocomplete="username"
                                                       class="form-control"
                                                       placeholder="Номер телефона или почта" />
                                                <label class="form-label" for="form2Example11">Логин</label>
                                            </div>

                                            <InputError class="mt-2" :message="form.errors.email" />

                                            <div class="form-outline mb-4">
                                                <input
                                                       id="password"
                                                       type="password"
                                                       v-model="form.password"
                                                       required
                                                       autocomplete="current-password" class="form-control" />
                                                <label class="form-label" for="form2Example22">Пароль</label>
                                            </div>

                                            <InputError class="mt-2" :message="form.errors.password" />

                                            <div class="block my-3">
                                                <label class="flex items-center">
                                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Запомнить меня</span>
                                                </label>
                                            </div>

                                            <div class="text-center pt-1 mb-5 pb-1">

                                                <PrimaryButton class="ml-4 gradient-custom-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                                    Войти в аккаунт
                                                </PrimaryButton>
                                                <Link
                                                    v-if="canResetPassword"
                                                    :href="route('password.request')"
                                                    class="text-muted ml-2">
                                                    Забыл пароль?
                                                </Link>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-center pb-4">
                                                <p class="mb-0 me-2">Нет аккаунта?</p>
                                                <button  type="button" class="btn btn-outline-danger ml-2">Создать новый</button>
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
<!--

        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>


        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>-->
    </GuestLayout>
</template>
<style>
.gradient-custom-2 {
    /* fallback for old browsers */
    background: #fccb90;

    /* Chrome 10-25, Safari 5.1-6 */

    background: -webkit-linear-gradient(to right, #2489ee, #364cd8, #1a97c5, #1a1c9a);

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
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
