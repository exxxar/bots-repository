<template>

    <div  class="container py-3">
        <div class="d-flex justify-content-center align-items-center" style="min-height:350px;">
            <div style="width:200px;height:200px;border-radius:50%;overflow:hidden;">
                <img v-lazy="''" class="w-100 object-fit-cover" alt="...">
            </div>
        </div>
        <h6 class="opacity-75 mb-3">Информация о профиле</h6>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Имя</span>
                <span class="text-primary fw-bold">Testoviy</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>ID</span>
                <span class="text-primary fw-bold">1234567890</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span class="text-primary fw-bold">+7(949)000-00-00</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Приглашено друзей</span>
                <span class="text-primary fw-bold">10</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Количество заказов</span>
                <span class="text-primary fw-bold">10</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Получено CashBack</span>
                <span class="text-primary fw-bold">1000 ₽</span>
            </li>
        </ul>
    </div>

</template>
