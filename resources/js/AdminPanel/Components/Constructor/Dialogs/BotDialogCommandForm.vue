<script setup>
import BotMenuConstructor from "@/AdminPanel/Components/Constructor/KeyboardConstructor.vue";
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import RegularExpressionHelper from "@/AdminPanel/Components/Constructor/Helpers/RegularExpressionHelper.vue";
import Pagination from '@/AdminPanel/Components/Pagination.vue';
import BotDialogResultRules from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogResultRules.vue";
import BotDialogVariablesHelper from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogVariablesHelper.vue";
</script>
<template>
    <form v-on:submit.prevent="submit">
        <div class="mb-2 px-0">
            <button type="submit" class="btn btn-outline-success">
                <i class="fa-regular fa-floppy-disk mr-2"></i>
                <span v-if="commandForm.id">Обновить диалог #{{ commandForm.id }}</span>
                <span v-else>Добавить диалог</span>
            </button>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link"
                   @click="tab=0"
                   v-bind:class="{'active':tab===0}"
                   aria-current="page" href="javascript:void(0)"><i class="fa-regular fa-comment-dots mr-2"></i>
                    Основное</a>
            </li>
            <li class="nav-item">
                <a class="nav-link"
                   @click="tab=1"
                   v-bind:class="{'active':tab===1}"
                   href="javascript:void(0)"><i class="fa-solid fa-link mr-2"></i> Связывание диалогов</a>
            </li>
            <li class="nav-item" v-if="!commandForm.is_inform">
                <a class="nav-link"
                   @click="tab=2"
                   v-bind:class="{'active':tab===2}"
                   href="javascript:void(0)"><i class="fa-brands fa-uncharted mr-2"></i> Дополнительное</a>
            </li>
            <li class="nav-item" v-if="commandForm.is_empty">
                <a class="nav-link"
                   @click="tab=3"
                   v-bind:class="{'active':tab===3}"
                   href="javascript:void(0)"
                   aria-disabled="true"><i class="fa-solid fa-hashtag mr-2"></i> Вычисление результата</a>
            </li>
        </ul>

        <div class="py-2 px-0" v-if="tab===0">

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="commandForm.is_inform"
                       :id="'need-inform-dialog'+(commandForm.id||'new')"
                       checked>
                <label class="form-check-label" :for="'need-inform-dialog'+(commandForm.id||'new')">
                    Диалог только выводит данные
                </label>
            </div>

            <div class="form-check mb-2" v-if="!commandForm.is_inform">
                <input class="form-check-input" type="checkbox" v-model="commandForm.is_empty"
                       :id="'need-empty-dialog'+(commandForm.id||'new')"
                       checked>
                <label class="form-check-label" :for="'need-empty-dialog'+(commandForm.id||'new')">
                    Диалог завершает цепочку (только вывод информации)
                </label>
            </div>


            <div class="alert alert-info"
                 v-if="commandForm.is_inform"
                 role="alert">
                Данный диалог позволит вам сразу вызвать связанный с ним другой диалог без ожидания ввод данных от
                пользователя!
            </div>


            <BotDialogVariablesHelper
                v-model="commandForm.pre_text"
                :bot="bot"></BotDialogVariablesHelper>
            <div class="form-floating mb-2">
                <textarea class="form-control" :id="'commandForm-pre-text-'+commandForm.id"
                          placeholder="Начни с малого..." v-model="commandForm.pre_text" required>
                </textarea>
                <label :for="'commandForm-pre-text-'+commandForm.id">Текст диалога</label>
            </div>

            <template v-if="commandForm.is_empty">

                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-light">
                            Данные блоки настраивают отображение данных администратором
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="commandForm.send_params.send_by_text"
                                   type="checkbox" role="switch" :id="'send-result-by-text'+(commandForm.id||'new')">
                            <label class="form-check-label"
                                   :for="'send-result-by-text'+(commandForm.id||'new')">Отправить текстом</label>
                        </div>

                        <template v-if="commandForm.send_params.send_by_text">
                            <div class="alert alert-light">
                                Выберите шаблон из списка
                                <BotDialogVariablesHelper
                                    class="d-inline"
                                    v-model="commandForm.send_params.format"
                                    :bot="bot"></BotDialogVariablesHelper>
                                для форматирования результата
                                или оставьте поле пустым, тогда будут выданы все полученные данные. Если каких-то полей
                                не будет в результате - они будут проигнорированы.
                            </div>

                            <div class="form-floating mb-2">
                                <textarea class="form-control"
                                          :id="'commandForm-send-params-format-'+(commandForm.id||'new')"
                                          placeholder="Начни с малого..." v-model="commandForm.send_params.format">
                                </textarea>
                                <label :for="'commandForm-send-params-format-'+(commandForm.id||'new')">Шаблон
                                    результата</label>
                            </div>
                        </template>


                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="commandForm.send_params.send_by_file"
                                   type="checkbox" role="switch" :id="'send-result-by-file'+(commandForm.id||'new')">
                            <label class="form-check-label"
                                   :for="'send-result-by-file'+(commandForm.id||'new')">Отправить файлом</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   v-model="commandForm.send_params.send_to_mail"
                                   type="checkbox" role="switch" :id="'send-result-to-mail'+(commandForm.id||'new')">
                            <label class="form-check-label"
                                   :for="'send-result-to-mail'+(commandForm.id||'new')">Отправить на почту</label>
                        </div>

                        <div
                            v-if="commandForm.send_params.send_to_mail"
                            class="form-floating mb-2">
                            <input type="email"
                                   v-model="commandForm.send_params.mail"
                                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Почта для отправки результата</label>
                        </div>
                    </div>
                </div>

            </template>

            <div class="mb-2" v-if="!commandForm.is_empty">
                <div class="form-floating">
                    <input type="text"
                           class="form-control"
                           id="floatingPassword"
                           v-model="commandForm.use_result_as"
                           placeholder="Password">
                    <label for="floatingPassword">Сохранить ответ пользователя как внутреннюю переменную</label>
                </div>
            </div>

            <div class="form-check mb-2" v-if="!commandForm.is_empty">
                <input class="form-check-input" type="checkbox" v-model="need_custom_stored_value"
                       id="need-custom-stored-value">
                <label class="form-check-label" for="need-custom-stored-value">
                    Нужно изменить данные в переменной
                </label>
            </div>

            <div class="mb-2" v-if="!commandForm.is_empty&&need_custom_stored_value">
                <div class="form-floating">
                    <input type="text"
                           class="form-control"
                           id="custom_stored_value"
                           v-model="commandForm.custom_stored_value"
                           placeholder="Password">
                    <label for="custom_stored_value">Поместить эти данные в результат</label>
                </div>
            </div>


            <div class="form-floating mb-2" v-if="!commandForm.is_empty&&!commandForm.is_inform">
            <textarea class="form-control"
                      :disabled="commandForm.is_inform"
                      :id="'commandForm-post-text-'+commandForm.id"
                      placeholder="Начни с малого..." v-model="commandForm.post_text">
            </textarea>
                <label :for="'commandForm-post-text-'+commandForm.id">Текст после успешного завершения
                    диалога</label>
            </div>


        </div>

        <div class="py-2 px-0" v-if="tab===1">
            <div class="mb-2">
                <div class="row" v-if="filteredCommands.length>0">
                    <div class="col-12">
                        <h6>Выбрать следующий диалог:</h6>
                    </div>

                    <div class="col-12 mb-2" v-if="commandForm.id">
                        <button
                            type="button"
                            @click="toggleRepeatSelfDialog"
                            v-bind:class="{'btn-outline-primary':commandForm.next_bot_dialog_command_id!==commandForm.id, 'btn-primary':commandForm.next_bot_dialog_command_id===commandForm.id}"
                            class="btn">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            Зациклить диалог на себе в случае ошибки
                        </button>
                    </div>

                    <div class="col-md-12 mb-1" v-for="(command, index) in filteredCommands">
                        <button type="button"
                                class="btn btn-outline-primary w-100 d-flex justify-content-between align-items-center"
                                style="text-align:left;">
                        <span>
                               <i class="fa-solid fa-unlink text-danger mr-2"
                                  @click="doCommandLink(null)"
                                  v-if="commandForm.next_bot_dialog_command_id == command.id"></i>
                            <i
                                @click="doCommandLink(command.id)"
                                class="fa-solid fa-link mr-2 text-success"
                                v-else></i> [#{{ command.id }}] Текст диалога: {{ command.pre_text || 'Без текста' }}
                        </span>
                        </button>
                    </div>

                    <Pagination
                        v-on:pagination_page="nextDialogs"
                        v-if="dialog_commands_paginate_object"
                        :pagination="dialog_commands_paginate_object"/>
                </div>
                <div class="row" v-else>
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            Диалогов для связывания нет
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2 px-0" v-if="tab===2">


            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="need_additional_functions"
                       id="need-additional-functions">
                <label class="form-check-label" for="need-additional-functions">
                    Нужен дополнительный функционал управления
                </label>
            </div>


            <div class="form-floating mb-2" v-if="!commandForm.is_empty&&need_additional_functions">
            <textarea type="text" class="form-control" :id="'commandForm-error-text-'+commandForm.id"
                      placeholder="Начни с малого..." v-model="commandForm.error_text">
            </textarea>
                <label :for="'commandForm-error-text-'+commandForm.id">Текст на случай ошибки корректности
                    данных</label>
            </div>

            <div class="mb-2" v-if="!commandForm.is_empty&&need_additional_functions">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-2">Регулярное выражение для
                        валидации данных

                    </p>

                    <RegularExpressionHelper
                        :param="'input_pattern'"
                        v-on:callback="addTextTo"
                    />
                </div>

                <div class="form-floating">
                    <input type="text"
                           v-model="commandForm.input_pattern"
                           maxlength="255"
                           class="form-control"
                           :id="'commandForm-input-pattern-'+commandForm.id" placeholder="Регулярное выражение">
                    <label :for="'commandForm-result-channel-'+commandForm.id">Регулярное выражение</label>
                </div>
            </div>

            <div class="mb-2" v-if="need_additional_functions">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-2">Канал для отправки данных

                    </p>

                    <TelegramChannelHelper
                        v-if="bot"
                        :token="bot.bot_token"
                        :param="'result_channel'"
                        v-on:callback="addTextTo"
                    />
                </div>

                <div class="form-floating">
                    <input type="number"
                           v-model="commandForm.result_channel"
                           class="form-control"
                           :id="'commandForm-result-channel-'+commandForm.id" placeholder="id канала">
                    <label :for="'commandForm-result-channel-'+commandForm.id">id канала</label>
                </div>

            </div>

            <div class=" mb-2" v-if="need_additional_functions">

                <div class="form-floating">
                    <select class="form-select"
                            v-model="commandForm.store_to"
                            id="store_variants" aria-label="Floating label select example">
                        <option selected>Не выбрано</option>
                        <option :value="item.key" v-for="item in store_variants">{{
                                item.title || 'Не указано'
                            }}
                        </option>
                    </select>
                    <label for="store_variants">Сохранить в переменную</label>
                </div>

            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox"
                       v-model="need_images"
                       id="need-dialog-image" checked>
                <label class="form-check-label" for="need-dialog-image">
                    В диалоге нужно изображение
                </label>
            </div>

            <div class="card mb-2" v-if="need_images">
                <div class="card-body">
                    <h6>Фотографии к диалогу</h6>
                    <div class="photo-preview d-flex justify-content-start flex-wrap w-100">
                        <label for="location-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                            <span>+</span>
                            <input type="file" id="location-photos" multiple accept="image/*"
                                   @change="onChangePhotos"
                                   style="display:none;"/>

                        </label>
                        <div class="mb-2 img-preview" style="margin-right: 10px;"
                             v-for="(img, index) in photos"
                             v-if="photos.length>0">
                            <img v-lazy="getPhoto(img).imageUrl">
                            <div class="remove">
                                <a @click="removePhoto('photos',index)">Удалить</a>
                            </div>
                        </div>
                        <div class="mb-2 img-preview"
                             v-if="commandForm.images&&bot"
                             style="margin-right: 10px;"
                             v-for="(img, index) in commandForm.images">
                            <img v-lazy="'/images-by-bot-id/'+bot.id+'/'+img">
                            <div class="remove">
                                <a @click="removePhoto('images',index)">Удалить</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="need_inline_keyboard"
                       id="need-dialog-menu-inline" checked>
                <label class="form-check-label" for="need-dialog-menu-inline">
                    В диалоге нужно меню тексту
                </label>
            </div>

            <div class="card mb-2" v-if="need_inline_keyboard">
                <div class="card-header">
                    <h6>Кнопки к тексту вопроса</h6>
                </div>
                <div class="card-body">
                    <BotMenuConstructor
                        :type="'inline'"
                        v-model="commandForm.inline_keyboard"/>
                </div>

            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="need_reply_keyboard"
                       id="need-dialog-menu-reply" checked>
                <label class="form-check-label" for="need-dialog-menu-reply">
                    В диалоге нужно нижнее меню
                </label>
            </div>


            <div class="card mb-2" v-if="need_reply_keyboard">
                <div class="card-header">
                    <h6>Кнопки в виде нижнего меню</h6>
                </div>
                <div class="card-body">
                    <BotMenuConstructor
                        :type="'reply'"
                        v-model="commandForm.reply_keyboard"
                    />
                </div>

            </div>


            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="need_chains" id="need-chains"
                       checked>
                <label class="form-check-label" for="need-chains">
                    Нужны многоуровневые диалоги
                </label>
            </div>

            <div class="mb-2" v-if="need_chains">
                <button type="button" class="btn btn-outline-primary" @click="addAnswer">Добавить вариант ответа
                </button>
                <table class="w-100 my-3"
                       style="border-collapse: separate;border-spacing: 5px 10px;"
                       v-if="commandForm.answers.length>0">

                    <tbody>
                    <template v-for="(item, index) in commandForm.answers">
                        <tr style="border-top:1px #eaeaea solid;">
                            <td></td>
                            <td></td>
                            <td><strong>Ответ</strong></td>
                            <td><strong>Следующий диалог</strong></td>
                        </tr>
                        <tr>
                            <td style="width:40px;">
                          <span
                              @click="commandForm.answers[index].in_edit_mode = true"
                              v-if="!commandForm.answers[index].in_edit_mode"><i
                              class="fa-solid fa-toggle-off cursor-pointer text-secondary"></i></span>
                                <span
                                    @click="commandForm.answers[index].in_edit_mode = false"
                                    v-else><i class="fa-solid fa-toggle-on cursor-pointer text-primary"></i></span>
                            </td>
                            <th scope="row">{{ index + 1 }}</th>

                            <td>
                                <div class="form-floating">
                                    <input type="text"
                                           v-model="commandForm.answers[index].answer"
                                           class="form-control" id="floatingInput" placeholder="name@example.com">
                                    <label for="floatingInput">Точный текст ответа</label>
                                </div>
                            </td>

                            <td>
                                <div class="input-group">


                                    <div class="form-floating">
                                        <input type="text" class="form-control"
                                               v-model="commandForm.answers[index].next_bot_dialog_command_id"
                                               id="floatingInput"
                                               placeholder="name@example.com" required>
                                        <label for="floatingInput">Выберите\введите диалог или введите его номер</label>
                                    </div>
                                    <div class="dropdown d-flex">
                                        <button class="btn btn-outline-secondary w-100"
                                                style="border-radius:0px 5px 5px 0px;"
                                                data-bs-auto-close="outside"
                                                type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                                        </button>
                                        <div class="dropdown-menu p-2"
                                             style="width:400px;max-height:300px; overflow-y:auto;">
                                            <ul class="list-group">
                                                <li class="list-group-item cursor-pointer font-12"
                                                    @click="commandForm.answers[index].next_bot_dialog_command_id = null">
                                                    Не
                                                    выбран
                                                </li>
                                                <li class="list-group-item cursor-pointer font-12"
                                                    style="line-height:100%;text-align:left;"
                                                    @click="commandForm.answers[index].next_bot_dialog_command_id = command.id"
                                                    v-for="command in getDialogCommands">
                                                    #{{ command.id || '-' }} {{ command.pre_text || '-' }}
                                                </li>
                                            </ul>
                                            <div class="px-3 pt-2">

                                                <Pagination
                                                    v-on:pagination_page="nextDialogs"
                                                    v-if="dialog_commands_paginate_object"
                                                    :pagination="dialog_commands_paginate_object"/>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </td>
                            <td>
                                <div class="dropdown d-flex justify-content-center align-items-center">
                                    <button class="btn btn-link" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="fa-solid fa-bars"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                               @click="removeDialogAnswer(item, index)"
                                               href="javascript:void(0)">Удалить</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="commandForm.answers[index].in_edit_mode">
                            <td></td>
                            <td></td>
                            <td>

                                <div class="input-group">
                                    <div class="form-floating">
                                        <input type="text"
                                               v-model="commandForm.answers[index].pattern"
                                               class="form-control" id="floatingInput"
                                               placeholder="name@example.com">
                                        <label for="floatingInput">Шаблон предполагаемого текста</label>
                                    </div>
                                    <div class="dropdown d-flex">
                                        <button class="btn btn-outline-secondary w-100"
                                                style="border-radius:0px 5px 5px 0px;"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                   @click="commandForm.answers[index].pattern = null"
                                                   href="javascript:void(0)">Не выбран</a></li>
                                            <li><a class="dropdown-item"
                                                   @click="commandForm.answers[index].pattern = pattern.value"
                                                   v-for="pattern in patterns"
                                                   href="javascript:void(0)">{{ pattern.title || '-' }}</a></li>
                                        </ul>
                                    </div>

                                </div>


                            </td>
                            <td>
                                <div class="form-floating">
                                    <input type="text"
                                           v-model="commandForm.answers[index].custom_stored_value"
                                           class="form-control" :id="'custom_stored_value-'+index"
                                           placeholder="name@example.com">
                                    <label :for="'custom_stored_value-'+index">Поместить данный текст в
                                        переменную</label>
                                </div>
                            </td>
                        </tr>
                    </template>

                    </tbody>
                </table>
                <div class="alert alert-primary mt-2" role="alert" v-else>
                    Добавьте один или несколько вариантов ответов
                </div>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" v-model="need_set_flags" id="need-set-flags"
                       checked>
                <label class="form-check-label" for="need-set-flags">
                    Установить флаги в соответствующее значение
                </label>
            </div>


            <div class=" mb-2" v-if="need_set_flags">
                <p>Выбрать флаги</p>
                <span class="badge text-info mr-2"
                      v-bind:class="{'bg-info text-white':commandForm.result_flags.indexOf(item.key)!=-1}"
                      @click="selectFlag(item)"
                      v-for="item in flags_variants">{{ item.title }}</span>

            </div>
        </div>
        <div class="py-2 px-0" v-if="tab===3">
            <BotDialogResultRules v-model="commandForm.rules"></BotDialogResultRules>
        </div>


    </form>
</template>
<script>


import {mapGetters} from "vuex";

export default {
    props: ["item", "bot"],
    data() {
        return {
            tab: 0,
            patterns: [
                {
                    title: 'Номер телефона РФ',
                    value: '/^\\s?(\\+\\s?7|8)([- ()]*\\d){10}$/'
                },
                {
                    title: 'Дата в формате ДД/ММ/ГГГГ',
                    value: '/(\\d{2})\\/(\\d{2})\\/(\\d{4})$/'
                },
                {
                    title: 'Время в формате ЧЧ:ММ[:СС]',
                    value: '#^[01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#'
                }


            ],
            test: [],
            loading: true,
            dialog_commands: [],
            dialog_commands_paginate_object: null,
            need_images: false,
            need_inline_keyboard: false,
            need_reply_keyboard: false,
            need_custom_stored_value: false,
            need_additional_functions: false,
            need_chains: false,
            need_set_flags: false,
            //сохранить данные в параметр
            //установить флаги
            flags_variants: [
                {
                    title: 'Является VIP',
                    key: 'is_vip',
                    value: null,
                },
                {
                    title: 'Является Админом',
                    key: 'is_admin',
                    value: null,
                },
                {
                    title: 'За работой',
                    key: 'is_work',
                    value: null,
                },
                {
                    title: 'Является менеджером',
                    key: 'is_manager',
                    value: null,
                },
            ],
            store_variants: [
                {
                    title: 'Имя',
                    key: 'name',
                },
                {
                    title: 'Почта',
                    key: 'email',
                },
                {
                    title: 'Возраст',
                    key: 'age',
                },
                {
                    title: 'Город',
                    key: 'city',
                },
                {
                    title: 'Страна',
                    key: 'country',
                },
                {
                    title: 'Адрес',
                    key: 'address',
                },
                {
                    title: 'Телефон',
                    key: 'phone',
                },
                {
                    title: 'День рождение',
                    key: 'birthday',
                }
            ],
            commandForm: {
                id: null,
                slug: null,
                pre_text: null,
                post_text: null,
                error_text: null,
                bot_id: null,
                input_pattern: null,
                inline_keyboard_id: null,
                reply_keyboard_id: null,
                images: null,
                next_bot_dialog_command_id: null,
                bot_dialog_group_id: null,
                is_empty: false,
                is_inform: false,
                custom_stored_value: null,

                send_params: {
                    send_by_text: true,
                    format: null,
                    send_by_file: false,
                    send_to_mail: false,
                    mail: null,
                },
                result_flags: [],
                store_to: null,
                use_result_as: null,

                result_channel: null,
                inline_keyboard: null,
                reply_keyboard: null,

                answers: [],
                rules: [],
            },
            photos: []
        }
    },
    watch: {
        'commandForm.is_inform': function (newVal, oldVal) {
            if (this.commandForm.is_inform) {
                this.commandForm.is_empty = false
                this.commandForm.post_text = null
                this.commandForm.answers = []
            }

        },


        'need_custom_stored_value': function (newVal, oldVal) {
            if (!this.need_custom_stored_value) {
                this.commandForm.custom_stored_value = null
            }

        },
        'need_inline_keyboard': function (newVal, oldVal) {
            if (!this.need_inline_keyboard) {
                this.commandForm.inline_keyboard_id = null
                this.commandForm.inline_keyboard = null
            }

        },
        'need_reply_keyboard': function (newVal, oldVal) {
            if (!this.need_reply_keyboard) {
                this.commandForm.reply_keyboard_id = null
                this.commandForm.reply_keyboard = null
            }

        },
        'need_set_flags': function (newVal, oldVal) {
            if (!this.need_set_flags) {
                this.commandForm.result_flags = []
            }

        },
        'need_images': function (newVal, oldVal) {
            if (!this.need_images) {
                this.commandForm.images = null
            }

        },
    },
    computed: {
        ...mapGetters(['getDialogCommands', 'getDialogCommandsPaginateObject']),
        filteredCommands() {
            if (!this.dialog_commands)
                return []

            return this.dialog_commands.filter(command => command.id != this.commandForm.id)

        }
    },
    mounted() {


        if (this.item) {
            this.$nextTick(() => {
                this.commandForm = {
                    id: this.item.id || null,
                    slug: this.item.slug || null,
                    pre_text: this.item.pre_text || null,
                    post_text: this.item.post_text || null,
                    error_text: this.item.error_text || null,
                    custom_stored_value: this.item.custom_stored_value || null,
                    is_empty: this.item.is_empty || false,
                    is_inform: this.item.is_inform || false,

                    input_pattern: this.item.input_pattern || null,
                    inline_keyboard_id: this.item.inline_keyboard_id || null,
                    reply_keyboard_id: this.item.reply_keyboard_id || null,
                    images: this.item.images || [],
                    next_bot_dialog_command_id: this.item.next_bot_dialog_command_id || null,
                    bot_dialog_group_id: this.item.bot_dialog_group_id || null,
                    result_channel: this.item.result_channel || null,
                    inline_keyboard: this.item.inline_keyboard || null,
                    reply_keyboard: this.item.reply_keyboard || null,

                    result_flags: this.item.result_flags || [],
                    store_to: this.item.store_to || null,
                    answers: this.item.answers || [],
                    use_result_as: this.item.use_result_as || null,
                    rules: this.item.rules || [],

                    send_params: {
                        send_by_text: true,
                        format: null,
                        send_by_file: false,
                        send_to_mail: false,
                        mail: null,
                    },

                }



                if (this.item.send_params) {
                    this.commandForm.send_params.format = this.item.send_params.format || null
                    this.commandForm.send_params.send_by_text = this.item.send_params.send_by_text || true
                    this.commandForm.send_params.send_by_file = this.item.send_params.send_by_file || false
                    this.commandForm.send_params.send_to_mail = this.item.send_params.send_to_mail || false
                    this.commandForm.send_params.mail = this.item.send_params.mail || null
                }

                if (this.bot)
                    this.commandForm.bot_id = this.bot.id || null

                if (this.commandForm.inline_keyboard_id != null)
                    this.need_inline_keyboard = true

                if (this.commandForm.reply_keyboard_id != null)
                    this.need_reply_keyboard = true

                if (this.commandForm.images.length > 0)
                    this.need_images = true

                if (this.commandForm.result_flags.length > 0)
                    this.need_set_flags = true

                if (this.commandForm.answers.length > 0)
                    this.need_chains = true

                if (this.commandForm.custom_stored_value) {
                    this.need_custom_stored_value = true
                }

            })
        }

        if (this.bot)
            this.$nextTick(() => {
                this.commandForm.bot_id = this.bot.id
            })

        /*else
            this.loadGroups();*/
        this.loadDialogs()
    },
    methods: {

        toggleRepeatSelfDialog() {
            if (this.commandForm.next_bot_dialog_command_id != null && this.commandForm.next_bot_dialog_command_id !== this.commandForm.id)
                this.commandForm.next_bot_dialog_command_id = this.commandForm.id
            else
                this.commandForm.next_bot_dialog_command_id = this.commandForm.next_bot_dialog_command_id === null ? this.commandForm.id : null
        },
        addAnswer() {
            this.commandForm.answers.push({
                id: null,
                bot_dialog_command_id: null,
                answer: null,
                pattern: null,
                custom_stored_value: null,
                next_bot_dialog_command_id: null,
            })
        },
        nextDialogs(index) {
            this.loadDialogs(index)
        },
        removeDialogAnswer(item, index) {

            if (item.id == null) {
                this.commandForm.answers.splice(index, 1)
                return;
            }

            this.commandForm.answers.splice(index, 1)
            this.loading = true
            this.$store.dispatch("removeDialogAnswer", {
                dataObject: {
                    dialogAnswerId: item.id,
                },
            }).then(resp => {
                this.loading = false
                this.loadDialogs()
            }).catch(() => {
                this.loading = false
            })
        },
        loadDialogs(page = 0) {
            this.loading = true
            this.$store.dispatch("loadLinkedDialogCommands", {
                dataObject: {
                    botId: this.bot.id || null,
                    order: 'id',
                    direction: 'asc'
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                let dataObject = resp.data
                this.dialog_commands = dataObject.data || []
                delete dataObject.data
                this.dialog_commands_paginate_object = dataObject
            }).catch(() => {
                this.loading = false
            })
        },
        doCommandLink(commandId) {
            this.loading = true

            this.commandForm.next_bot_dialog_command_id = commandId
        },
        submit() {
            this.loading = true

            let data = new FormData();
            Object.keys(this.commandForm)
                .forEach(key => {
                    const item = this.commandForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.photos) {
                for (let i = 0; i < this.photos.length; i++)
                    data.append('files[]', this.photos[i]);

                data.delete("photos")
            }

            this.$store.dispatch((this.commandForm.id ?
                    "updateDialogCommand" :
                    "createDialogCommand")
                , {
                    dialogCommandForm: data
                }).then((response) => {

                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Успешная обработка диалоговой команды",
                    type: 'success'
                });

                if (this.commandForm.id == null) {
                    this.commandForm = {
                        id: null,
                        slug: null,
                        pre_text: null,
                        post_text: null,
                        error_text: null,
                        input_pattern: null,
                        inline_keyboard_id: null,
                        images: null,
                        next_bot_dialog_command_id: null,
                        bot_dialog_group_id: null,
                        result_channel: null,
                        inline_keyboard: null,
                        is_inform: false,
                        is_empty: false,
                        result_flags: [],
                        answers: [],
                        store_to: null,
                        custom_stored_value: null,
                        use_result_as: null,
                    }

                    this.photos = []

                }

                this.$emit("callback")
            }).catch(err => {
                this.loading = false
            })
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto(name, index) {
            this[name].splice(index, 1)
        },
        onChangePhotos(e) {
            const files = e.target.files
            for (let i = 0; i < files.length; i++)
                this.photos.push(files[i])
        },

        addTextTo(object = {param: null, text: null}) {
            this.commandForm[object.param] = object.text;

        },

        selectFlag(item) {

            if (!this.commandForm.result_flags || !Array.isArray(this.commandForm.result_flags))
                this.commandForm.result_flags = []


            let index = this.commandForm.result_flags.indexOf(item.key)
            if (index === -1)
                this.commandForm.result_flags.push(item.key)
            else
                this.commandForm.result_flags.splice(index, 1)
        }


    }
}
</script>
