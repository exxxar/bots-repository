<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
import GlobalSlugList from "@/AdminPanel/Components/Constructor/Slugs/GlobalSlugList.vue";
import BotSlugListSimple from "@/AdminPanel/Components/Constructor/Slugs/BotSlugListSimple.vue";

import BotDialogGroupListSimple from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogGroupList.vue";
import InlineInjectionsHelper from "@/AdminPanel/Components/Constructor/Helpers/InlineInjectionsHelper.vue";
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import PageRules from "@/AdminPanel/Components/Constructor/Pages/PageRules.vue";
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import PagePreview from "@/AdminPanel/Components/Constructor/Pages/PagePreview.vue";
import FastPageForm from "@/AdminPanel/Components/Constructor/Pages/FastPageForm.vue";
import ChatDialog from "@/AdminPanel/Components/Chat/ChatMini.vue";
</script>
<template>

    <div class="row mt-2">
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                <strong class="fw-bold">Внимание!</strong> Вы можете создать сразу все страницы через <a
                href="javascript:void(0)"
                class="text-primary fw-bold"
                @click="fastCreate"><i class="fa-solid fa-bolt"></i> быстрое создание страниц</a>, а затем наполнить их
                контентом!
            </div>
        </div>
    </div>

    <div class="row" v-if="pageForm.id">
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                <strong class="fw-bold">Внимание!</strong> Вы в режиме редактирования страницы (кнопки) <strong
                style="font-size:16px;">{{
                    page.slug ? page.slug.command : 'Без названия'
                }}</strong>, если хотите создать новую нажмите <a
                href="javascript:void(0)" class="btn btn-primary ml-2" @click="clearForm">Создать новую страницу</a>
            </div>
        </div>
    </div>


    <form
        v-if="bot"
        id="page-construct"
        class="py-3"
        v-on:submit.prevent="openSaveModal">

        <div class="row" v-if="hasParts">
            <div class="col-12 mb-2">
                <h6>На странице выбраны следующие разделы</h6>
                <ul class="mini-pics justify-content-start d-inline-flex text-primary">
                    <li
                        class="mr-1 "
                        title="Изображения"
                        v-bind:class="{'active':need_page_images}">
                        <i class="fa-regular fa-images"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Стикеры"
                        v-bind:class="{'active':need_page_sticker}">
                        <i class="fa-regular fa-note-sticky"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Видео"
                        v-bind:class="{'active':need_page_video}">
                        <i class="fa-solid fa-photo-film"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Аудио"
                        v-bind:class="{'active':need_page_audios}">
                        <i class="fa-regular fa-file-audio"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Документы"
                        v-bind:class="{'active':need_page_documents}">
                        <i class="fa-regular fa-file-word"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Страницы"
                        v-bind:class="{'active':need_attach_page}">
                        <i class="fa-solid fa-link"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Скрипты"
                        v-bind:class="{'active':need_attach_slug}">
                        <i class="fa-solid fa-scroll"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Диалоги"
                        v-bind:class="{'active':need_attach_dialog}">
                        <i class="fa-regular fa-comment-dots"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Правила"
                        v-bind:class="{'active':need_rules}">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </li>
                    <li
                        class="mr-1"
                        title="Нижнее меню"
                        v-bind:class="{'active':need_reply_menu}">
                        <i class="fa-regular fa-keyboard"></i>
                    </li>
                    <li
                        title="Текстовое меню"
                        v-bind:class="{'active':need_inline_menu}">
                        <i class="fa-solid fa-ellipsis"></i>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-12 mb-2">
                <div class="d-flex justify-content-between">
                    <label class="form-label" id="bot-domain">
                        Команда
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>

                </div>


                <input type="text" class="form-control"
                       placeholder="Команда"
                       aria-label="Команда"
                       v-model="pageForm.command"
                       maxlength="255"
                       aria-describedby="bot-domain" required>
            </div>

            <div class="col-md-4 col-12 mb-2 d-flex justify-content-end align-items-end">


                <div class="btn-group"
                     style="background:white;"
                     role="group" aria-label="Basic example">

                    <button type="submit" v-if="pageForm.id||need_clean"
                            title="Сохранить страницу"
                            id="save-page-btn"
                            class="btn btn-primary min-menu-btn">
                        <i class="fa-regular fa-floppy-disk"></i>
                    </button>
                    <button type="button" @click="clearForm"
                            title="Очистить \ Новая страница"
                            v-if="pageForm.id||need_clean"
                            class="btn btn-outline-primary min-menu-btn">
                        <i class="fa-solid fa-xmark text-danger"></i>
                    </button>
                    <button type="button"
                            title="Список страниц"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvas"
                            class="btn btn-outline-primary text-primary  min-menu-btn">
                        <i class="fa-solid fa-list-ol"></i>
                    </button>
                </div>
            </div>


        </div>


        <div class="row" v-show="!pageForm.is_external">


            <div class="col-12 mb-2">
                <div class="d-flex justify-content-between">
                    <label class="form-label  mb-0" id="bot-domain">
                        Текстовое содержимое страницы
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <InlineInjectionsHelper
                        v-model="pageForm.content"
                        v-on:submit-relink="submitReLink"
                        :field-id="'#main-text-field'"
                    />
                </div>


                <div class="form-floating">
                                <textarea class="form-control"
                                          v-model="pageForm.content"
                                          maxlength="4096"
                                          placeholder="Введите текст"
                                          id="main-text-field" style="min-height: 150px"></textarea>
                    <label for="main-text-field">Содержимое страницы <span
                        v-if="pageForm.content">{{ pageForm.content.length }}/4096 </span></label>
                </div>

            </div>

            <div class="col-12 mb-2">
                <ul class="nav nav-tabs justify-content-center">

                    <li
                        class="nav-item "
                        @click="tab=0"
                    >
                        <a
                            v-bind:class="{'active':tab===0,'primary-mark':need_reply_menu}"
                            class="nav-link d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-regular fa-keyboard"></i>
                            <span>Нижнее меню</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=1"
                    >
                        <a
                            v-bind:class="{'active':tab===1,'primary-mark':need_inline_menu}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-solid fa-ellipsis"></i>
                            <span>Меню под текстом</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=2"
                    >
                        <a
                            v-bind:class="{'active':tab===2,'primary-mark':need_page_images}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-regular fa-images"></i>
                            <span>Изображения</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=3"
                    >
                        <a
                            v-bind:class="{'active':tab===3,'primary-mark':need_page_video}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-solid fa-photo-film"></i>
                            <span>Видео</span>
                        </a>
                    </li>

                    <li
                        class="nav-item "
                        @click="tab=5"
                    >
                        <a
                            v-bind:class="{'active':tab===5,'primary-mark':need_page_audios}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-regular fa-file-audio"></i>
                            <span>Аудио</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=6"
                    >
                        <a
                            v-bind:class="{'active':tab===6,'primary-mark':need_page_documents}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-regular fa-file-word"></i>
                            <span>Документы</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=7"
                    >
                        <a
                            v-bind:class="{'active':tab===7,'primary-mark':need_attach_page}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-solid fa-link"></i>
                            <span>Другая страница</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=8"
                    >
                        <a
                            v-bind:class="{'active':tab===8,'primary-mark':need_attach_slug}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-solid fa-scroll"></i>
                            <span>Скрипт</span>
                        </a>
                    </li>
                    <li
                        class="nav-item "
                        @click="tab=9">
                        <a
                            v-bind:class="{'active':tab===9,'primary-mark':need_attach_dialog}"
                            class="nav-link  d-flex flex-column justify-content-center align-items-center font-12"
                            href="javascript:void(0)">
                            <i class="fa-regular fa-comment-dots"></i>
                            <span>Начало диалога</span>
                        </a>
                    </li>

                    <li
                        class="nav-item "
                    >
                        <div class="dropdown">
                            <button
                                class="nav-link dropdown-toggle d-flex flex-column justify-content-center align-items-center font-12"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-hamburger"></i> Другие возможности
                            </button>
                            <ul class="dropdown-menu">
                                <li @click="tab=10"><a class="dropdown-item"
                                                       v-bind:class="{'active':tab===10,'primary-mark':need_rules}"
                                                       href="javascript:void(0)">
                                    <i class="fa-solid fa-scale-balanced"></i> Правила на странице</a>
                                </li>
                                <li @click="tab=11"><a class="dropdown-item"
                                                       v-bind:class="{'active':tab===11,'primary-mark':need_secure_page}"
                                                       href="javascript:void(0)">
                                    <i class="fa fa-key" aria-hidden="true"></i> Безопасная страница</a>
                                </li>
                                <li @click="tab=12"><a class="dropdown-item"
                                                       v-bind:class="{'active':tab===12,'primary-mark':need_payed_page}"
                                                       href="javascript:void(0)">
                                    <i class="fa fa-money-bill" aria-hidden="true"></i> Платная страница</a>
                                </li>
                                <li @click="tab=13"><a class="dropdown-item"
                                                       v-bind:class="{'active':tab===13,'primary-mark':need_cashback_page}"
                                                       href="javascript:void(0)">
                                    <i class="fa-solid fa-sack-dollar"></i> Начислить бонусы</a>
                                </li>
                                <li @click="tab=4"><a class="dropdown-item"
                                                      v-bind:class="{'active':tab===4,'primary-mark':need_page_sticker}"
                                                      href="javascript:void(0)">
                                    <i class="fa-regular fa-note-sticky"></i> Стикеры</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-show="tab===0">
                <div class="col-12 mb-2" v-if="need_reply_menu">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_reply_menu"
                               type="checkbox" id="need-reply-menu">
                        <label class="form-check-label" for="need-reply-menu">
                            Нижнее меню страницы
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_reply_menu">
                    <div class="card">
                        <div class="card-header d-flex justify-between align-items-center">
                            <h6>Конструктор нижнего меню</h6>

                            <button class="btn " type="button"
                                    v-bind:class="{'btn-outline-primary':!showReplyTemplateSelector,'btn-primary':showReplyTemplateSelector}"
                                    @click="showReplyTemplateSelector = !showReplyTemplateSelector"
                            >

                                <span v-if="!showReplyTemplateSelector">  Открыть шаблоны меню</span>
                                <span v-else> Скрыть шаблоны меню</span>
                            </button>


                        </div>


                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label" id="bot-domain">
                                        <Popper>
                                            <i class="fa-regular fa-circle-question mr-1"></i>
                                            <template #content>
                                                <div> Заголовок для нижнего меню
                                                </div>
                                            </template>
                                        </Popper>
                                        Заголовок
                                        <!--                            <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>-->
                                    </label>
                                    <input type="text" class="form-control"
                                           placeholder="Заголовок меню"
                                           aria-label="Заголовок меню"
                                           v-model="pageForm.reply_keyboard_title"
                                           maxlength="255"
                                           aria-describedby="bot-domain">
                                </div>
                            </div>

                            <KeyboardList
                                class="mb-2"
                                :type="'reply'"
                                v-if="showReplyTemplateSelector"
                                v-on:select="selectReplyKeyboard"
                                :select-mode="true"/>


                            <BotMenuConstructor
                                v-else
                                :type="'reply'"
                                v-model="pageForm.reply_keyboard"/>

                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       v-model="need_page_create_from_keyboard"
                                       type="checkbox" role="switch" id="need-page-create-from-keyboard">
                                <label class="form-check-label" for="need-page-create-from-keyboard">
                                    Нужно создать страницы на основе клавиатуры
                                </label>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Нижнее меню</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Создание и настройка нижнего меню страницы бота. Добавление и редактирование функций кнопок
                            бота на странице.
                            Данное меню отображается пользователю всегда.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_reply_menu"
                                @click="need_reply_menu=true"
                                v-bind:class="{'btn-primary':!need_reply_menu,'btn-outline-secondary':need_reply_menu}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Nizhnee-menyu-bota-01-03" target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===1">
                <div class="col-12 mb-2" v-if="need_inline_menu">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_inline_menu"
                               type="checkbox" id="need-inline-menu">
                        <label class="form-check-label" for="need-inline-menu">
                            Меню под текстом страницы
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_inline_menu">
                    <div class="card">


                        <div class="card-header d-flex justify-between align-items-center">
                            <h6>Конструктор меню в сообщении</h6>
                            <button class="btn " type="button"
                                    v-bind:class="{'btn-outline-primary':!showInlineTemplateSelector,'btn-primary':showInlineTemplateSelector}"
                                    @click="showInlineTemplateSelector = !showInlineTemplateSelector"
                            >

                                <span v-if="!showInlineTemplateSelector">  Открыть шаблоны меню</span>
                                <span v-else> Скрыть шаблоны меню</span>
                            </button>
                        </div>


                        <div class="card-body">

                            <KeyboardList
                                class="mb-2"
                                :type="'inline'"
                                v-if="showInlineTemplateSelector"
                                v-on:select="selectInlineKeyboard"
                                :select-mode="true"/>

                            <BotMenuConstructor
                                :type="'inline'"
                                v-else
                                v-model="pageForm.inline_keyboard"/>


                        </div>
                    </div>


                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Меню под текстом</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            В отличии от нижнего меню, которое отображается всегда, меню под текстом показывается под
                            контентом только в момент отображения этого контента.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_inline_menu"
                                @click="need_inline_menu=true"
                                v-bind:class="{'btn-primary':!need_inline_menu,'btn-outline-secondary':need_inline_menu}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Menyu-k-tekstu-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===2">
                <div class="col-12 mb-2" v-if="need_page_images">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_page_images"
                               type="checkbox"
                               id="need-page-images">
                        <label class="form-check-label" for="need-page-images">
                            Изображения на странице (максимум 10)
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-if="need_page_images">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Загрузите изображения с файловой системы</h6>
                        </div>
                        <div class="card-body d-flex justify-content-start">

                            <label for="photos" style="margin-right: 10px;" class="photo-loader ml-2">
                                +
                                <input type="file" id="photos"
                                       multiple
                                       accept="image/*" @change="onChangePhotos"
                                       style="display:none;"/>

                            </label>
                            <div class="row">
                                <div class="col-12 d-flex flex-wrap" v-if="photos.length>0">
                                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                                         v-for="(img, index) in photos">
                                        <img v-lazy="getPhoto(img).imageUrl"/>

                                        <div class="remove">
                                            <a @click="removePhoto(index)">Удалить</a>
                                        </div>
                                    </div>
                                </div>
                                <h6 v-if="pageForm.images.length>0">Ранее загруженные фотографии</h6>
                                <div class="col-12 d-flex flex-wrap" v-if="pageForm.images.length>0">

                                    <div class="mb-2 img-preview" style="margin-right: 10px;"
                                         v-for="(img, index) in pageForm.images">
                                        <img
                                            v-lazy="'/images-by-bot-id/'+bot.id+'/'+img">
                                        <div class="remove">
                                            <a @click="removeImage(index)">Удалить</a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Изображения</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете добавить к одной странице от <b>1</b> до <b>10</b> изображений. Изображения (от
                            2х) будут собраны в группу и показаны под текстовым содержимым страницы.
                            Если изображение одно, то текст будет отображен под ним.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_page_images"
                                @click="need_page_images=true"
                                v-bind:class="{'btn-primary':!need_page_images,'btn-outline-secondary':need_page_images}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Dobavlenie-izobrazhenij-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===3">
                <div class="col-12 mb-2" v-if="need_page_video">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_page_video"
                               type="checkbox"
                               id="need-page-video">
                        <label class="form-check-label" for="need-page-video">
                            Видео к странице
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-if="need_page_video">
                    <BotMediaList
                        :need-video="true"
                        :need-video-note="true"
                        :selected="pageForm.videos"
                        v-on:select="selectVideo"></BotMediaList>
                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Видео</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете добавить к одной странице от <b>1</b> до <b>10</b> видео. Ролики (от 2х) будут
                            собраны в группу и показаны под текстовым содержимым страницы.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_page_video"
                                @click="need_page_video=true"
                                v-bind:class="{'btn-primary':!need_page_video,'btn-outline-secondary':need_page_video}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Dobavlenie-video-na-stranicu-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===4">
                <div class="col-12 mb-2" v-if="need_page_sticker">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_page_sticker"
                               type="checkbox"
                               id="need-page-sticker">
                        <label class="form-check-label" for="need-page-sticker">
                            Стикер к странице
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-if="need_page_sticker">
                    <BotMediaList
                        :need-sticker="true"
                        :selected="[pageForm.sticker]"
                        v-on:select="selectSticker"></BotMediaList>
                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Стикеры</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете переслать любой стикер в бота (от лица администратора) и затем этот стикер
                            использовать для взаимодействия с пользователем.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_page_sticker"
                                @click="need_page_sticker=true"
                                v-bind:class="{'btn-primary':!need_page_sticker,'btn-outline-secondary':need_page_sticker}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Dobavlenie-stikera-na-stranicu-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===5">
                <div class="col-12 mb-2" v-if="need_page_audios">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_page_audios"
                               type="checkbox"
                               id="need-page-audio">
                        <label class="form-check-label" for="need-page-audio">
                            Звук к странице
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-if="need_page_audios">
                    <BotMediaList
                        :need-audio="true"
                        :selected="pageForm.audios"
                        v-on:select="selectAudio"></BotMediaList>
                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Аудио</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете добавить к одной странице от <b>1</b> до <b>10</b> аудио. Музыка (от 2х) будет
                            собрана в группу и показана под текстовым содержимым страницы.
                            Если вы хотите использовать <b class="text-danger">голосовое</b>, то можно использовать
                            максимум <b class="text-danger">одну запись</b>. Или же, вы можете конвертировать её в mp3 и
                            залить как музыкальный файл в бота.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_page_audios"
                                @click="need_page_audios=true"
                                v-bind:class="{'btn-primary':!need_page_audios,'btn-outline-secondary':need_page_audios}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Dobavlenie-audio-na-stranicu-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===6">
                <div class="col-12 mb-2" v-if="need_page_documents">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_page_documents"
                               type="checkbox"
                               id="need-page-document">
                        <label class="form-check-label" for="need-page-document">
                            Документ \ презентацию к странице \ картинку в оригинале
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-if="need_page_documents">
                    <BotMediaList
                        :need-document="true"
                        :selected="pageForm.documents"
                        v-on:select="selectDocument"></BotMediaList>
                </div>

                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Документы</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете добавить к одной странице от <b>1</b> до <b>10</b> документов. Документы (от 2х)
                            будут собраны в группу и показана под текстовым содержимым страницы.
                            В качестве файлов вы можете использовать текстовые документы, презентации, файлы-pdf,
                            изображения в оригинальном размере.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_page_documents"
                                @click="need_page_documents=true"
                                v-bind:class="{'btn-primary':!need_page_documents,'btn-outline-secondary':need_page_documents}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Dobavlenie-dokumenta-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===7">

                <div class="col-12 mb-2">
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Связь со страницей</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете привязать вызов другой страницы непосредственно после вызова текущей. Это позволит
                            выводить контент по цепочке для пользователя.
                        </p>
                        <p class="my-3 text-success font-bold" v-if="pageForm.next_page_id">Связано со страницей
                            #{{ pageForm.next_page_id }} </p>
                        <div class="d-inline-flex gap-2 mb-5">

                            <button
                                v-if="pageForm.next_page_id"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill btn-danger"
                                type="button"
                                @click="clearLinks">Очистить
                            </button>
                            <button
                                v-else
                                data-bs-toggle="modal" data-bs-target="#page-list-modal"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill btn-primary"
                                type="button">
                                Связать
                            </button>


                            <a href="https://telegra.ph/Svyazyvanie-so-stranicej-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>


                        </div>

                        <h6 class="mb-3">Конструктор цепочек</h6>
                        <div class="d-flex justify-content-center align-items-center flex-wrap">
                            <span class="px-2">Текущая страница</span>
                            <i class="fa-solid fa-arrow-right"></i>
                            <div class="d-flex justify-content-center align-items-center px-3 py-2"
                                 v-for="(item, index) in links">
                                <button type="button"
                                        @click="removeLink(index)"
                                        v-bind:class="{'btn-danger':chainCollapseTest(item)}"
                                        class="btn btn-primary position-relative rounded-5">
                                    {{ item.slug.command || '-' }}
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    #{{ item.id }}
                                  </span>
                                </button>
                                <i class="fa-solid fa-arrow-right ml-2" v-if="index<links.length-1"></i>
                            </div>
                            <i class="fa-solid fa-arrow-right" v-if="links.length>0"></i>
                            <button type="button"
                                    data-bs-toggle="modal" data-bs-target="#page-list-modal"
                                    class="btn btn-outline-secondary position-relative rounded-5 ml-5">
                                <i class="fa-solid fa-plus"></i>
                            </button>

                        </div>


                    </div>
                </div>
            </div>

            <div v-show="tab===8">
                <div class="col-12 mb-2" v-if="need_attach_slug">

                    <div class="alert alert-info" role="alert">
                        Если в вашем боте нет каких-то предустановленных скриптов вы можете найти их в глобальном
                        магазине скриптов
                    </div>
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_show_global_slug_list"
                               type="checkbox"
                               id="need-show-global-slug-list">
                        <label class="form-check-label" for="need-show-global-slug-list">
                            Добавить скрипты из глобального магазина?
                        </label>
                    </div>

                    <GlobalSlugList :can-add="true"
                                    v-if="bot&&need_show_global_slug_list"
                                    :bot="bot"
                                    v-on:callback="loadSlugs"/>
                </div>
                <div class="col-12 mb-2" v-if="need_attach_slug">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_attach_slug"
                               type="checkbox"
                               id="need-slug-attach">
                        <label class="form-check-label" for="need-slug-attach">
                            Привязать скрипт
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_attach_slug">

                    <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert"
                         v-if="pageForm.next_bot_menu_slug_id">
                        Связано со скриптом #{{ pageForm.next_bot_menu_slug_id }}


                        <a
                            class="btn btn-link text-danger"
                            @click="pageForm.next_bot_menu_slug_id = null">Очистить</a>
                    </div>


                    <BotSlugListSimple v-if="bot&&load_slug_simple_list"
                                       :global="true"
                                       :can-select="true"
                                       :selected="[pageForm.next_bot_menu_slug_id]"
                                       v-on:callback="associateSlug"
                                       :bot="bot"/>
                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Скрипты</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Скрипты содержат основной функционал системы.
                            Вы можете к каждой странице привязать один скрипт, который будет вызван после вывода
                            основного содержимого страницы.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_attach_slug"
                                @click="need_attach_slug=true"
                                v-bind:class="{'btn-primary':!need_attach_slug,'btn-outline-secondary':need_attach_slug}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Svyazyvanie-so-skriptom-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===9">
                <div class="col-12 mb-2" v-if="need_attach_dialog">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_attach_dialog"
                               type="checkbox"
                               id="need-dialog-attach">
                        <label class="form-check-label" for="need-dialog-attach">
                            Привязать начало диалога
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_attach_dialog">
                    <p v-if="pageForm.next_bot_dialog_command_id">Связано с диалогом #{{
                            pageForm.next_bot_dialog_command_id
                        }}
                        <a
                            class="btn btn-link"
                            @click="pageForm.next_bot_dialog_command_id = null">Очистить</a></p>
                    <BotDialogGroupListSimple v-if="bot"
                                              v-on:select-dialog="associateDialog"
                                              :bot="bot"/>
                </div>

                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Диалоги</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Диалоги страницы - это инструмент запроса данных от пользователя. Вы можете привязать начало
                            диалога к странице, а сам диалог будет вызван по цепочке.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_attach_dialog"
                                @click="need_attach_dialog=true"
                                v-bind:class="{'btn-primary':!need_attach_dialog,'btn-outline-secondary':need_attach_dialog}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Dobavlenie-dialoga-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===10">
                <div class="col-12 mb-2" v-if="need_rules">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_rules"
                               type="checkbox" id="need-rules">
                        <label class="form-check-label" for="need-rules">
                            Нужны правила загрузки страницы
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_rules">
                    <PageRules
                        :bot="bot"
                        :rules-form="pageForm"
                    />
                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Правила отображения страницы</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Вы можете создать правила, согласно которым будет отображена та или иная страница. Вы можете
                            перенаправить пользователя с одной страницы на другую, в случае если ваше условие не
                            выполнено.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_rules"
                                @click="need_rules=true"
                                v-bind:class="{'btn-primary':!need_rules,'btn-outline-secondary':need_rules}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Pravila-na-stranice-01-03"
                               target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===11">
                <div class="col-12 mb-2" v-if="need_secure_page">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_secure_page"
                               type="checkbox" id="need-secure-page">
                        <label class="form-check-label" for="need-secure-page">
                            Безопасная страница
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_secure_page">

                    <div class="input-group mb-2">

                        <div class="form-floating">
                            <input type="text"
                                   v-model="pageForm.password"
                                   class="form-control" id="floatingPassword" placeholder="Пароль" required>
                            <label for="floatingPassword">Пароль страницы</label>
                        </div>

                        <span class="input-group-text" id="basic-addon1">
                            <a href="javascript:void(0)" @click="renewPassword"> <i class="fa fa-refresh"
                                                                                    aria-hidden="true"></i></a>
                        </span>
                    </div>


                    <div class="form-floating">
                        <textarea class="form-control"
                                  v-model="pageForm.password_description"
                                  placeholder="Пояснение" id="floatingTextarea2" style="height: 200px"></textarea>
                        <label for="floatingTextarea2">
                            Пояснение для пользователя
                            <span class="small"
                                  v-if="(pageForm.password_description||'').length>0">{{
                                    pageForm.password_description.length
                                }}/512</span>
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Безопасная страница</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Создайте безопасную страницу, доступ к которой будет только у тех людей, кому вы дадите
                            пароль! Люди, которые получат один раз пароль, смогут постоянно заходить на данную страницу,
                            даже если вы смените его.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_secure_page"
                                @click="need_secure_page=true"
                                v-bind:class="{'btn-primary':!need_secure_page,'btn-outline-secondary':need_secure_page}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Nizhnee-menyu-bota-01-03" target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===12">
                <div class="col-12 mb-2" v-if="need_payed_page">
                    <div class="form-check">
                        <input class="form-check-input"
                               v-model="need_payed_page"
                               type="checkbox" id="need-payed-page">
                        <label class="form-check-label" for="need-payed-page">
                            Платная страница
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2" v-if="need_payed_page">

                    <div class="alert alert-light" v-if="bot.payment_provider_token == null">Вы не добавили тоукен
                        платежной системы в <span
                            @click="goToBotSettings"
                            class="cursor-pointer text-primary fw-bold text-decoration-underline">настройках бота!</span>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="number"
                               min="100"
                               :disabled="bot.payment_provider_token == null"
                               v-model="pageForm.price"
                               class="form-control" id="page-price" placeholder="Цена" required>
                        <label for="page-price">Цена за открытие страницы, руб</label>
                    </div>


                    <div class="form-floating">
                        <textarea class="form-control"
                                  :disabled="bot.payment_provider_token == null"
                                  v-model="pageForm.price_description"
                                  placeholder="Пояснение" id="floatingTextarea2" style="height: 200px"></textarea>
                        <label for="floatingTextarea2">
                            Пояснение для пользователя
                            <span class="small"
                                  v-if="(pageForm.price_description||'').length>0">{{
                                    pageForm.price_description.length
                                }}/512</span>
                        </label>
                    </div>

                </div>
                <div class="col-12 mb-2" v-else>
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Платная страница</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            Данная страница позволяет запросить оплату с пользователя за её просмотр. Задайте цену
                            страницы, описание перед оплатой и др. настройки оплаты. Пользователи, оплатившие услугу,
                            получат постоянный доступ к этой странице.
                        </p>
                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                :disabled="need_payed_page"
                                @click="need_payed_page=true"
                                v-bind:class="{'btn-primary':!need_payed_page,'btn-outline-secondary':need_payed_page}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                Добавить
                            </button>
                            <a href="https://telegra.ph/Nizhnee-menyu-bota-01-03" target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-show="tab===13">


                <div class="col-12 mb-2">
                    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-2">

                        <div class="d-flex justify-content-center mb-3">
                            <img v-lazy="'../images/icon.png'" alt="" width="100" height="100">
                        </div>


                        <h1 class="text-body-emphasis">Начисление бонусов</h1>
                        <p class="col-lg-8 mx-auto fs-5 text-muted">
                            При переходе на данную страницу пользователь разово может получить указанную сумму бонусов!
                            Начисление произойдет перед выводом контента страницы.
                        </p>

                        <template v-if="need_cashback_page">
                            <div class="row d-flex justify-content-center my-2">
                                <div class="col-md-4">
                                    <div class="form-floating mb-2 w-100">
                                        <input type="number"
                                               min="0"
                                               v-model="pageForm.cashback"
                                               class="form-control w-100" id="page-price" placeholder="Бонусы" required>
                                        <label for="page-price">Сумма начисления бонусов, руб</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12 my-3">
                                    <h6>Нужно ли запрашивать какие-то данные у пользователя?</h6>
                                    <div class="d-flex d-flex justify-content-center">
                                        <button
                                            @click="pageForm.cashback_config.need_request_user_data=!pageForm.cashback_config.need_request_user_data"
                                            v-bind:class="{
                                                'btn-primary': pageForm.cashback_config.need_request_user_data ,
                                                'btn-outline-primary': !pageForm.cashback_config.need_request_user_data
                                            }"
                                            class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill mr-2"
                                            type="button">
                                            Да
                                        </button>
                                        <button
                                            @click="pageForm.cashback_config.need_request_user_data=!pageForm.cashback_config.need_request_user_data"
                                            v-bind:class="{
                                                'btn-primary': !pageForm.cashback_config.need_request_user_data ,
                                                'btn-outline-primary': pageForm.cashback_config.need_request_user_data
                                            }"
                                            class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill"
                                            type="button">
                                            Нет
                                        </button>
                                    </div>
                                </div>


                            </div>

                            <div class="row justify-content-center"
                                 v-if="pageForm.cashback_config.need_request_user_data">
                                <div class="col-md-12 my-3">
                                    <h6>Упрощенный вариант сбора данных?</h6>
                                    <p class="fst-italic">Упрощенный вариант подразумевает получение только номера
                                        телефона!</p>
                                    <div class="d-flex d-flex justify-content-center">
                                        <button
                                            @click="pageForm.cashback_config.simple_form=!pageForm.cashback_config.simple_form"
                                            v-bind:class="{
                                                'btn-primary': pageForm.cashback_config.simple_form ,
                                                'btn-outline-primary': !pageForm.cashback_config.simple_form
                                            }"
                                            class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill mr-2"
                                            type="button">
                                            Да
                                        </button>
                                        <button
                                            @click="pageForm.cashback_config.simple_form=!pageForm.cashback_config.simple_form"
                                            v-bind:class="{
                                                'btn-primary': !pageForm.cashback_config.simple_form ,
                                                'btn-outline-primary': pageForm.cashback_config.simple_form
                                            }"
                                            class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill"
                                            type="button">
                                            Нет
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6 my-5">
                                    <div class="form-floating">
                                        <textarea class="form-control"
                                                  maxlength="255"
                                                  v-model="pageForm.cashback_config.description"
                                                  placeholder="Leave a comment here" id="floatingTextarea2"
                                                  style="height: 100px"></textarea>
                                        <label for="floatingTextarea2">
                                            Пояснение для пользователя

                                            <span
                                                v-if="(pageForm.cashback_config.description||'').length>0">{{
                                                    pageForm.cashback_config.description.length
                                                }} / 255</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="d-inline-flex gap-2 mb-5">
                            <button
                                @click="need_cashback_page=!need_cashback_page"
                                v-bind:class="{'btn-primary':!need_cashback_page,'btn-danger':need_cashback_page}"
                                class="d-inline-flex align-items-center btn btn-lg px-4 rounded-pill" type="button">
                                <span v-if="!need_cashback_page">Добавить</span>
                                <span v-if="need_cashback_page">Убрать</span>
                            </button>

                            <a href="https://telegra.ph/Nizhnee-menyu-bota-01-03" target="_blank"
                               class="d-inline-flex align-items-center btn btn-outline-secondary btn-lg px-4 rounded-pill"
                            >
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <div class="row">
        <div class="col-12">

            <div class="alert alert-info" role="alert">
                <h6>Страница поддерживает комбинации клавиш:</h6>
                <ul class="mb-0 mr-0 p-0">
                    <li><strong>Ctrl+S</strong> - открыть\закрыть окно сохранения</li>
                    <li><strong>Ctrl+D</strong> - просмотр страницы (для сохраненных страниц)</li>
                    <li><strong>Ctrl+пробел</strong> - открыть\закрыть список страниц</li>
                    <li><strong>Ctrl+backspace</strong> - очистить страницу\новая страница</li>
                    <li><strong>Ctrl+стрелка вправо</strong> - переключение между вкладка вперед</li>
                    <li><strong>Ctrl+стрелка влево</strong> - переключение между вкладка назад</li>
                </ul>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="fast-pages-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Быстрое создание страниц</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <FastPageForm v-on:callback="callbackFastPageCreate"></FastPageForm>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Не сохранять</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="save-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Сохранение страницы</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-if="bot">
                        <div class="col-12 mb-2 ">
                            <h6 class="d-flex justify-between">
                                <span>Вы создаете страницу для <a :href="'https://t.me/'+bot.bot_domain"
                                                                  target="_blank">{{ bot.bot_domain }}</a></span>
                            </h6>
                        </div>
                    </div>

                    <form v-on:submit.prevent="submitPage" class="row">
                        <div class="col-12 mb-2">
                            <h6>На странице выбраны следующие разделы</h6>
                            <ul class="mini-pics text-primary">
                                <li
                                    v-bind:class="{'active':need_page_images}">
                                    <i class="fa-regular fa-images"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_page_sticker}">
                                    <i class="fa-regular fa-note-sticky"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_page_video}">
                                    <i class="fa-solid fa-photo-film"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_page_audios}">
                                    <i class="fa-regular fa-file-audio"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_page_documents}">
                                    <i class="fa-regular fa-file-word"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_attach_page}">
                                    <i class="fa-solid fa-link"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_attach_slug}">
                                    <i class="fa-solid fa-scroll"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_attach_dialog}">
                                    <i class="fa-regular fa-comment-dots"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_rules}">
                                    <i class="fa-solid fa-scale-balanced"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_reply_menu}">
                                    <i class="fa-regular fa-keyboard"></i>
                                </li>
                                <li
                                    v-bind:class="{'active':need_inline_menu}">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 mb-2" v-if="pageForm.id">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="need_show_qr_and_link"
                                       type="checkbox"
                                       id="need-show-qr-and-link">
                                <label class="form-check-label" for="need-show-qr-and-link">
                                    Показать ссылку на страницу и QR-код
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mb-2" v-if="pageForm.id&&need_show_qr_and_link">
                            <p class="mb-2">Ссылка на текущую страницу: <span
                                style="word-wrap:break-word;"
                                class="bg-secondary text-white font-bold cursor-pointer"
                                @click="copyToClipBoard(pageLink)">{{
                                    pageLink
                                }}</span></p>

                            <div class="alert alert-info mb-2">
                                <strong class="fw-bold">Внимание!</strong> Используйте метки <strong
                                class="fw-bold text-primary">на английском языке</strong> или <strong
                                class="fw-bold text-primary">число</strong>!
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text"
                                       maxlength="100"
                                       v-model="utm_source"
                                       class="form-control" id="utm-source" placeholder="name@example.com">
                                <label for="utm-source">Укажите метку для статистики</label>
                            </div>

                            <div class="d-flex justify-content-center">
                                <img v-lazy="qr" style="width:200px;height:200px;">
                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="form-label" id="bot-domain">
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>Напишите для себя пояснение для чего нужна данная страница.
                                            Это поможет другим менеджерам лучше понять Вас.
                                        </div>
                                    </template>
                                </Popper>
                                Описание страницы
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                            </label>
                            <textarea type="text" class="form-control"
                                      placeholder="Описание страницы"
                                      aria-label="Описание страницы"
                                      v-model="pageForm.comment"
                                      maxlength="255"
                                      aria-describedby="bot-domain" required>
            </textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="pageForm.is_external"
                                       type="checkbox" id="is-external">
                                <label class="form-check-label" for="is-external">
                                    Внешнее управление страницей
                                </label>
                            </div>
                            <div class="alert alert-danger" role="alert" v-if="pageForm.is_external">
                                Обработка ссылок передана на внешний адрес, указанный в настройках бота! Все запросы
                                по данной кнопке будут отправлены на указанный URL-адрес:
                                <strong>{{ bot.callback_link || 'не указано' }}.</strong>
                                <p class="mb-0" v-if="!bot.callback_link">Внешня ссылка не установлена! Перейдите в
                                    раздел <strong>"Информация о боте"</strong>, затем <strong>Другие
                                        настройки</strong> и установите ссылку для внешнего управления в поле
                                    <strong>"Ссылка на внешний сервис обработки данных"</strong>!</p>
                            </div>

                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="pageForm.need_log_user_action"
                                       type="checkbox" id="need_log_user_action">
                                <label class="form-check-label" for="need_log_user_action">
                                    Логировать действия пользователя в канал при переходе
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="need_stay_after_save"
                                       type="checkbox" id="need-stay-after-save">
                                <label class="form-check-label" for="need-stay-after-save">
                                    Остаться на текущей странице после сохранения
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit"
                                    id="submit-page"
                                    class="btn btn-success w-100 p-3">Сохранить
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Не сохранять</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

    <div class="modal fade" id="page-list-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <PagesList
                        :current="pageForm.id"
                        :selected="selectedLinkIds"
                        v-on:callback="attachPage"
                        :editor="false"/>
                </div>

            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end w-25" tabindex="-1" id="offcanvas"
         data-bs-keyboard="true"
         data-bs-scroll="true"
         data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title d-none d-sm-block">Ваши страницы (кнопки)</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-12">
                    <PagesList
                        v-if="loaded_page_list"
                        :editor="true"
                        :current="page"
                        v-on:callback="pageListCallback"/>
                </div>
            </div>

        </div>
    </div>

    <div class="offcanvas offcanvas-end w-25" tabindex="-1" id="page-preview"
         data-bs-keyboard="true"
         data-bs-scroll="true"
         data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title d-none d-sm-block">Демонстрация страницы</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">

            <PagePreview
                v-if="page"
                :page="page"></PagePreview>
            <p v-else>Сперва сохраните страницу</p>

<!--            <template v-if="bot">
                <ChatDialog :domain="bot.bot_domain"></ChatDialog>
            </template>-->
        </div>
    </div>


</template>
<script>

import {mapGetters} from "vuex";
import {Base64} from 'js-base64';

export default {
    data() {
        return {

            need_page_create_from_keyboard: false,

            tab: 0,

            links: [],
            saveModal: null,
            pageModal: null,
            fastPageModal: null,
            pagePreviewModal: null,
            page: null,

            need_clean: false,
            load: false,
            photos: [],
            showReplyTemplateSelector: false,
            showInlineTemplateSelector: false,
            loaded_page_list: true,
            load_slug_simple_list: true,
            showMenu: false,

            need_show_qr_and_link: false,
            utm_source: null,
            need_stay_after_save: true,
            need_show_global_slug_list: false,
            need_page_sticker: false,
            need_page_audios: false,
            need_page_documents: false,
            need_page_video: false,
            need_page_images: false,
            need_inline_menu: false,
            need_reply_menu: false,
            need_attach_page: false,
            need_attach_dialog: false,
            need_attach_slug: false,
            need_rules: false,
            need_secure_page: false,
            need_payed_page: false,
            need_cashback_page: false,

            bot: null,
            pageForm: {
                id: null,
                content: '',
                command: null,
                slug: null,
                comment: null,

                password: null,
                password_description: null,

                price: null,
                cashback: null,
                cashback_config: {
                    need_request_user_data: false,
                    simple_form: true,
                    description: null,
                },
                price_description: null,

                videos: [],
                images: [],
                audios: [],
                documents: [],
                sticker: null,
                reply_keyboard_title: null,
                reply_keyboard: null,

                inline_keyboard: null,
                is_external: false,
                need_log_user_action: false,

                next_page_id: null,
                next_bot_dialog_command_id: null,
                next_bot_menu_slug_id: null,

                rules_if: null,
                rules_else_page_id: null,
                rules_if_message: null,
                rules_else_message: null,


            },
        }
    },
    watch: {
        'need_cashback_page': function (newVal, oldVal) {
            if (!this.need_cashback_page) {
                this.pageForm.cashback = null
            }

        },

        'need_payed_page': function (newVal, oldVal) {
            if (!this.need_payed_page) {
                this.pageForm.price = null
                this.pageForm.price_description = null
            }

        },
        'need_secure_page': function (newVal, oldVal) {
            if (!this.need_secure_page) {
                this.pageForm.password = null
                this.pageForm.password_description = null
            }

        },
        'need_page_images': function (newVal, oldVal) {
            if (!this.need_page_images) {
                this.photos = []
                this.pageForm.images = []
            }

        },
        'need_page_audios': function (newVal, oldVal) {
            if (!this.need_page_audios) {
                this.pageForm.audios = []
            }

        },
        'need_page_documents': function (newVal, oldVal) {
            if (!this.need_page_documents) {
                this.pageForm.documents = []
            }

        },
        'need_page_sticker': function (newVal, oldVal) {
            if (!this.need_page_sticker) {
                this.pageForm.sticker = null
            }

        },
        'need_page_video': function (newVal, oldVal) {
            if (!this.need_page_video) {
                this.pageForm.videos = []
            }

        },
        'need_inline_menu': function (newVal, oldVal) {

            if (!this.need_inline_menu) {
                this.pageForm.inline_keyboard = null
                this.pageForm.inline_keyboard_id = null
            }

        },
        'need_rules': function (newVal, oldVal) {
            if (!this.need_rules) {
                this.pageForm.rules_if = null
                this.pageForm.rules_else_page_id = null
                this.pageForm.rules_if_message = null
                this.pageForm.rules_else_message = null


            }

        },
        'need_reply_menu': function (newVal, oldVal) {
            if (!this.need_reply_menu) {
                this.pageForm.reply_keyboard = null
                this.pageForm.reply_keyboard_id = null
            }
        },
        'need_attach_page': function (newVal, oldVal) {
            if (!this.need_attach_page) {
                this.pageForm.next_page_id = null

            }
        },
        'need_attach_dialog': function (newVal, oldVal) {
            if (!this.need_attach_dialog) {
                this.pageForm.next_bot_dialog_command_id = null

            }
        },
        'need_attach_slug': function (newVal, oldVal) {
            if (!this.need_attach_slug) {
                this.pageForm.next_bot_menu_slug_id = null

            }
        },

        pageForm: {
            handler: function (newValue) {
                if (this.pageForm.reply_keyboard != null)
                    this.need_reply_menu = true

                if (this.pageForm.inline_keyboard != null)
                    this.need_inline_menu = true

                if (this.pageForm.images.length > 0)
                    this.need_page_images = true

                if (this.pageForm.next_bot_dialog_command_id != null)
                    this.need_attach_dialog = true

                if (this.pageForm.next_bot_menu_slug_id != null)
                    this.need_attach_slug = true

                if (this.pageForm.next_page_id != null)
                    this.need_attach_page = true

                if (this.pageForm.rules_if != null)
                    this.need_rules = true

                if (this.pageForm.videos.length > 0)
                    this.need_page_video = true

                if (this.pageForm.audios.length > 0)
                    this.need_page_audios = true

                if (this.pageForm.documents.length > 0)
                    this.need_page_documents = true

                if (this.pageForm.sticker)
                    this.need_page_sticker = true

                if (this.pageForm.password != null)
                    this.need_secure_page = true

                if (this.pageForm.price != null)
                    this.need_payed_page = true

                if (this.pageForm.cashback != null)
                    this.need_cashback_page = true

                this.need_clean = true
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.pageLink
        },
        selectedLinkIds() {
            let links = this.links.map(o => o["id"])
            if (this.page == null)
                return links

            return this.page.id != null ? [this.page.id, ...links] : links;
        },
        hasParts() {
            return this.need_page_sticker ||
                this.need_page_audios ||
                this.need_page_documents ||
                this.need_page_video ||
                this.need_page_images ||
                this.need_inline_menu ||
                this.need_reply_menu ||
                this.need_attach_page ||
                this.need_attach_dialog ||
                this.need_attach_slug ||
                this.need_secure_page ||
                this.need_payed_page ||
                this.need_cashback_page ||
                this.need_rules
        },
        pageLink() {
            if (!this.pageForm.id)
                return "Ссылка недоступна"

            let tmpId = "";
            for (let i = 0; i < 10 - ("" + this.pageForm.id).length; i++)
                tmpId += "0"
            tmpId += this.pageForm.id;

            return "https://t.me/" + this.getCurrentBot.bot_domain + "?start="
                + Base64.encode("004" + tmpId + (this.utm_source != null ? "utm" + this.utm_source : ""))


        }
    },
    mounted() {
        if (this.page) {
            this.preparePageForm(this.page)
        } else
            this.clearForm()


        this.loadCurrentBot().then(() => {
            this.saveModal = new bootstrap.Modal(document.getElementById('save-modal'), {})
        })

        this.pageModal = new bootstrap.Offcanvas(document.getElementById('offcanvas'), {
            scroll: true,
        })

        this.pagePreviewModal = new bootstrap.Offcanvas(document.getElementById('page-preview'), {
            scroll: true,
        })
        window.addEventListener("keydown", (e) => {

            if (e.ctrlKey && e.keyCode == 8) {
                e.preventDefault();

                this.clearForm()
            }

            if (e.ctrlKey && e.code == 'KeyD') {
                e.preventDefault();
                this.pagePreviewModal.toggle()
            }

            if (e.ctrlKey && e.keyCode == 32) {
                e.preventDefault();
                this.pageModal.toggle()
            }

            if (e.ctrlKey && e.code == 'KeyS') {
                e.preventDefault();

                const formSubmit = document.querySelector("#save-page-btn")

                if (!formSubmit)
                    this.$notify({
                        title: "Конструктор страниц",
                        text: "Вы еще не начали создавать страницу!",
                        type: 'warning'
                    });
                else
                    formSubmit.click();
            }


            if (e.ctrlKey && e.keyCode == 39) {
                e.preventDefault();
                if (this.tab === 10)
                    this.tab = 0
                else
                    this.tab++;
            }


            if (e.ctrlKey && e.keyCode == 37) {
                e.preventDefault();
                if (this.tab === 0)
                    this.tab = 10
                else
                    this.tab--;
            }


        });

    },

    methods: {
        submitReLink(event) {
            this.need_inline_menu = true
            this.tab = 1

            let btn = {
                text: '🔗Поделиться ссылкой',
                url: `https://t.me/share/url?url=${event.url}&text=${event.text}`,
            }

            console.log("menu", this.pageForm.inline_keyboard)
            if (this.pageForm.inline_keyboard == null)
                this.pageForm.inline_keyboard =
                    {
                        menu: [
                            [
                                {
                                    ...btn
                                }
                            ]
                        ]
                    }
            else
                this.pageForm.inline_keyboard.push([{
                    ...btn
                }])


        },
        goToBotSettings() {
            this.$emit("bot-settings")
        },
        callbackFastPageCreate() {
            this.loaded_page_list = false

            this.$nextTick(() => {
                this.loaded_page_list = true
            })

            this.fastPageModal.hide()
        },
        fastCreate() {
            this.fastPageModal = new bootstrap.Modal(document.getElementById('fast-pages-create'), {})
            this.fastPageModal.show()
        },
        chainCollapseTest(item) {
            return this.page == null ?
                this.links.filter(link => link.id == item.id).length > 1 :
                [this.page, ...this.links].filter(link => link.id == item.id).length > 1
        },
        loadPageChains() {
            return this.$store.dispatch("loadPageChains", {
                dataObject: {
                    start_page_id: this.page.id
                }
            }).then((response) => {
                this.links = response.data
            })
        },
        renewPassword() {

            let letters = '0123456789ABCDEF';
            let tmp = "";
            for (let i = 0; i < 10; i++) {
                tmp += letters[Math.floor(Math.random() * letters.length)];
            }

            this.pageForm.password = tmp
        },
        removeLink(index) {
            this.links.splice(index, 1)
        },
        loadSlugs() {
            this.load_slug_simple_list = false
            this.need_show_global_slug_list = false
            this.$nextTick(() => {
                this.load_slug_simple_list = true

            })
        },
        preparePageForm(page) {

            this.need_show_qr_and_link = false
            this.need_show_global_slug_list = false
            this.need_page_sticker = false
            this.need_page_audios = false
            this.need_page_documents = false
            this.need_page_video = false
            this.need_page_images = false
            this.need_inline_menu = false
            this.need_reply_menu = false
            this.need_attach_page = false
            this.need_attach_dialog = false
            this.need_attach_slug = false
            this.need_secure_page = false
            this.need_payed_page = false
            this.need_cashback_page = false
            this.need_rules = false

            this.links = []
            this.page = page
            this.photos = []
            this.$nextTick(() => {

                this.pageForm.id = page.id
                this.pageForm.slug_id = page.slug ? page.slug.id : null
                this.pageForm.content = page.content
                this.pageForm.password = page.password || null
                this.pageForm.password_description = page.password_description || null
                this.pageForm.price = page.price || null
                this.pageForm.cashback = page.cashback || null
                if (page.cashback_config)
                    this.pageForm.cashback_config = {
                        need_request_user_data: page.cashback_config.need_request_user_data || false,
                        simple_form: page.cashback_config.simple_form || true,
                        description: page.cashback_config.description || null,
                    }

                this.pageForm.price_description = page.price_description || null
                this.pageForm.command = page.slug ? page.slug.command : null
                this.pageForm.slug = page.slug ? page.slug.slug : null
                this.pageForm.comment = page.slug ? page.slug.comment : null
                this.pageForm.images = page.images || []
                this.pageForm.sticker = page.sticker || null
                this.pageForm.reply_keyboard_title = page.reply_keyboard_title || null

                this.pageForm.reply_keyboard_id = page.reply_keyboard_id || null
                this.pageForm.inline_keyboard_id = page.inline_keyboard_id || null
                this.pageForm.reply_keyboard = page.replyKeyboard || null

                if (this.pageForm.reply_keyboard != null) {
                    let settings = page.reply_keyboard?.settings || null
                    if (settings != null)
                        this.pageForm.reply_keyboard_settings = {
                            resize_keyboard: settings.resize_keyboard || true,
                            one_time_keyboard: settings.one_time_keyboard || false,
                            input_field_placeholder: settings.input_field_placeholder || null,
                            is_persistent: settings.is_persistent || false,
                        }
                }

                this.pageForm.inline_keyboard = page.inlineKeyboard || null
                this.pageForm.next_page_id = page.next_page_id || null
                this.pageForm.next_bot_dialog_command_id = page.next_bot_dialog_command_id || null
                this.pageForm.next_bot_menu_slug_id = page.next_bot_menu_slug_id || null

                this.pageForm.is_external = page.is_external || false
                this.pageForm.need_log_user_action = page.need_log_user_action || false
                this.pageForm.rules_if = page.rules_if || null
                this.pageForm.rules_else_page_id = page.rules_else_page_id || null
                this.pageForm.audios = page.audios || []
                this.pageForm.documents = page.documents || []
                this.pageForm.videos = page.videos || []

                this.pageForm.rules_if_message = page.rules_if_message || null
                this.pageForm.rules_else_message = page.rules_else_message || null


                if (this.pageForm.id != null && this.pageForm.next_page_id != null)
                    this.loadPageChains()
            })

        },
        copyToClipBoard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.$notify({
                    title: "Копирование",
                    text: "Ссылка скопирована в буфер"
                })
            }).catch((err) => {
                this.$notify({
                    title: "Копирование",
                    text: "Ошибка копирования",
                    type: "error"
                })
            });
        },
        associateDialog(item) {
            this.pageForm.next_bot_dialog_command_id = item.id
        },
        associateSlug(item) {
            this.pageForm.next_bot_menu_slug_id = item.id
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        clearLinks() {
            this.links = []
            this.pageForm.next_page_id = null
        },
        attachPage(item) {
            if (this.links.length == 0) {
                if (item.id != this.pageForm.id) {
                    this.$notify({
                        title: "Конструктор страниц",
                        text: "Вы успешно связали страницы!",
                        type: 'success'
                    });
                    this.pageForm.next_page_id = item.id
                } else
                    this.$notify({
                        title: "Конструктор страниц",
                        text: "Вы не можете связать данную страницу с собой",
                        type: 'error'
                    });
            }

            let inLinks = this.page == null ?
                this.links.findIndex(link => link.id === item.id) != -1 :
                this.links.findIndex(link => link.id === item.id) != -1 || item.id === this.page.id

            if (!inLinks)
                this.links.push(item)
        },

        clearForm() {
            this.photos = []
            this.pageForm = {
                id: null,
                content: null,
                command: null,
                slug: null,
                comment: null,
                sticker: null,
                password: null,
                password_description: null,
                price: null,
                cashback: null,
                cashback_config: {
                    need_request_user_data: false,
                    simple_form: true,
                    description: null,
                },
                price_description: null,
                images: [],
                reply_keyboard: null,
                inline_keyboard: null,
                is_external: false,
                need_log_user_action: false,
                videos: [],
                audios: [],
                documents: [],
                reply_keyboard_title: null,
                reply_keyboard_id: null,

                inline_keyboard_id: null,

                next_page_id: null,
                next_menu_slug: null,
                next_bot_dialog_command_id: null,
                next_bot_menu_slug_id: null,

                rules_if_message: null,
                rules_else_message: null,

            }
            this.photos = []

            this.showReplyTemplateSelector = false
            this.showInlineTemplateSelector = false


            this.need_page_images = false
            this.need_page_video = false
            this.need_inline_menu = false
            this.need_reply_menu = false
            this.need_attach_page = false
            this.need_attach_dialog = false
            this.need_attach_slug = false
            this.need_rules = false
            this.need_page_audios = false
            this.need_page_documents = false
            this.need_page_sticker = false
            this.need_payed_page = false
            this.need_cashback_page = false
            this.need_secure_page = false

            this.links = [];

            this.$nextTick(() => {
                this.need_clean = false
            })
        },
        openSaveModal() {
            if ((this.pageForm.comment || '').length === 0)
                this.pageForm.comment = this.pageForm.command

            this.saveModal.show()
        },
        submitPage() {

            let data = new FormData();
            Object.keys(this.pageForm)
                .forEach(key => {
                    const item = this.pageForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.need_page_create_from_keyboard)
                data.append("need_page_create_from_keyboard", this.need_page_create_from_keyboard)

            if (this.bot)
                data.append("bot_id", this.bot.id)

            if (this.photos.length > 0)
                for (let i = 0; i < this.photos.length; i++) {
                    data.append('photos[]', this.photos[i]);
                }

            /*   if (this.pageForm.images.length === 0 || typeof this.pageForm.images == 'string')
                   data.delete("images")*/

            this.$store.dispatch((this.pageForm.id == null ? "createPage" : "updatePage"), {
                pageForm: data
            }).then((response) => {

                if (this.pageForm.id == null) {

                    this.preparePageForm(response.data)
                    /*  this.pageForm.id = response.data.id|| null
                      this.pageForm.slug_id = response.data.slug.id|| null
                      this.pageForm.slug = response.data.slug|| null
                      this.pageForm.command = response.data.slug.command|| null
                      this.pageForm.comment = response.data.slug.comment || null*/

                }

                if (this.links.length > 0)
                    this.$store.dispatch("storePageChains", {
                        dataObject: {
                            start_page_id: response.data.id,
                            links: this.links.map(o => o["id"])
                        }
                    })

                if (!this.need_stay_after_save) {
                    this.load = true

                    this.$nextTick(() => {
                        this.load = false

                        this.clearForm()
                    })
                }

                this.loaded_page_list = false

                this.$nextTick(() => {
                    this.loaded_page_list = true
                })

                this.saveModal.hide()

                this.$emit("callback", response.data)
                this.$notify({
                    title: "Конструктор ботов",
                    text: (this.pageForm.id == null ? "Страница успешно создана!" : "Страница успешно обновлена!"),
                    type: 'success'
                });


                this.$store.dispatch("loadPages", {
                    dataObject: {
                        botId: this.bot.id,
                        needNewFirst: true,

                    },
                    page: localStorage.getItem(`cashman_pagelist_${this.bot.id}_page_index`) || 0

                })

            }).catch(err => {

            })

        },
        saveInlineKeyboard(keyboard) {
            this.pageForm.inline_keyboard = keyboard
        },
        saveSettings(settings) {

            console.log("settings save", settings)
            this.pageForm.reply_keyboard_settings = {
                resize_keyboard: settings.resize_keyboard || true,
                one_time_keyboard: settings.one_time_keyboard || false,
                input_field_placeholder: settings.input_field_placeholder || null,
                is_persistent: settings.is_persistent || false,
            }

        },
        selectReplyKeyboard(keyboard) {
            this.pageForm.reply_keyboard = keyboard

            this.showReplyTemplateSelector = false;
        },
        selectInlineKeyboard(keyboard) {
            this.pageForm.inline_keyboard = keyboard

            this.showInlineTemplateSelector = false;
        },
        /*  saveReplyKeyboard(keyboard) {
              this.pageForm.reply_keyboard = keyboard
          },
          saveReplyKeyboardSettings(settings) {
              this.pageForm.reply_keyboard_settings = settings
          },*/
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(index) {
            this.photos.splice(index, 1)
        },
        removeImage(index) {
            this.pageForm.images.splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },


        onChange(data) {
            this.pageForm.content = data
        },

        selectPhoto(item) {
            if (!this.pageForm.images)
                this.pageForm.images = []

            let index = this.pageForm.images.indexOf(item.file_id)

            if (index !== -1)
                this.pageForm.images.splice(index, 1)
            else
                this.pageForm.images.push(item.file_id)
        },

        selectSticker(item) {
            this.pageForm.sticker = item.file_id
        },
        selectVideo(item) {

            if (!this.pageForm.videos)
                this.pageForm.videos = []

            let index = this.pageForm.videos.indexOf(item.file_id)

            if (index !== -1)
                this.pageForm.videos.splice(index, 1)
            else
                this.pageForm.videos.push(item.file_id)
        },
        selectAudio(item) {

            if (!this.pageForm.audios)
                this.pageForm.audios = []

            let index = this.pageForm.audios.indexOf(item.file_id)

            if (item.type === "voice") {

                if (index !== -1) {
                    this.pageForm.audios.splice(index, 1)
                    return;
                }

                this.pageForm.audios = [item.file_id]
                return;
            }

            if (index !== -1)
                this.pageForm.audios.splice(index, 1)
            else
                this.pageForm.audios.push(item.file_id)
        },
        selectDocument(item) {

            if (!this.pageForm.documents)
                this.pageForm.documents = []

            let index = this.pageForm.documents.indexOf(item.file_id)

            if (index !== -1)
                this.pageForm.documents.splice(index, 1)
            else
                this.pageForm.documents.push(item.file_id)
        },
        pageListCallback(page) {
            this.$notify("Вы выбрали страницу из списка! Все остальные действия будут производится для этой страницы");
            this.load = true
            this.preparePageForm(page)
            this.$nextTick(() => {
                this.load = false
            });
        },

    }
}
</script>
<style lang="scss">
.min-menu-btn {
    min-width: 50px;
    min-height: 50px;
}

.styled-label {
    background: #008cba;
    padding: 10px 10px 10px 30px;
    box-sizing: border-box;

    label {
        color: white;
    }
}

.mini-pics {
    display: flex;
    //background-color: white;
    justify-content: space-around;
    // border-radius: 0px 5px 5px 0px;
    padding: 0;

    li {
        padding: 5px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: 1px #f9f9f9 solid;
        background-color: white;

        &.active {
            background-color: #008cba;
            color: white;
        }

    }
}

.component-menu {
    display: flex;
    position: absolute;
    top: 30px;
    flex-direction: column;
    border: 1px lightgray solid;
    left: 0px;
    background-color: white;

    list-style: none;
    margin: 0;
    padding: 0;
    // border-radius: 0px 5px 5px 0px;


    li {
        padding: 5px 10px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 45px;

        span {
            margin-top: 3px;
            font-size: 8px;
            text-align: center;
            line-height: 100%;
        }

        &.active,
        &:hover {
            background-color: #008cba;
            color: white;
        }

        &.divider {
            padding: 0;
            width: 2px;
            background: #e2e2e2;

            &:hover {
                background: #e2e2e2;
            }
        }
    }


}

.fixed-left-menu {
    position: fixed;
    z-index: 1000;
    left: 20px;
    top: 105px;
    background: white;

    .fixed-menu-btn {
        width: 75px;
        height: 30px;
        border: 1px lightgray solid;
        padding: 0px;
    }


}

.custom-menu {
    position: sticky;
    top: 50px;
    z-index: 100;
}

.custom-menu-2 {
    display: flex;
    top: 30px;
    flex-direction: column;
    border: 1px lightgray solid;
    left: 0px;
    background-color: white;

    list-style: none;
    margin: 0;
    padding: 0;
    // border-radius: 0px 5px 5px 0px;


    li {
        padding: 5px 10px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 45px;

        span {
            margin-top: 3px;
            font-size: 8px;
            text-align: center;
            line-height: 100%;
        }

        &.active,
        &:hover {
            background-color: #008cba;
            color: white;
        }

        &.divider {
            padding: 0;
            width: 2px;
            background: #e2e2e2;

            &:hover {
                background: #e2e2e2;
            }
        }
    }
}

.font-12 {
    font-size: 12px !important;
}

.font-10 {
    font-size: 10px !important;
}

.primary-mark {
    position: relative;

    &:after {
        content: '';
        position: absolute;
        top: 5px;
        right: 5px;
        width: 5px;
        height: 5px;
        background-color: red;
        border-radius: 50%;
    }
}

</style>
