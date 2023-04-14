//переход по ссылкам

function SendMessage(){
     var re = /^(\+)?(\(\d{2,3}\) ?\d|\d)(([ \-]?\d)|( ?\(\d{2,3}\) ?)){5,12}\d$/;
    userNumber=document.getElementById('usernumber').value;
    userName=document.getElementById('username');
    userText=document.getElementById('usertext');
    var valid = re.test(userNumber);
    if (valid) output = 'Номер телефона введен правильно!';
    else output = 'Номер телефона введен неправильно!';
    alert( output);
}


function redirectTo(href){
    switch (href){
        case 'logo':
            window.location.href="#logo"
            break;
        case 'freeaccess':
            window.location.href="#freeaccess"
            break;
        case 'wanttouse':
            window.location.href="#wanttouse"
            break;
        case 'sendmessage':
            SendMessage();
            window.location.href="#sendmessage"
            break;
        case 'writetobot':
            window.location.href="#writetobot"
            break;



        case 'social':
            window.location.href="#telegram"
            break;
        case 'vk':
            window.location.href="#vk"
            break;
        case 'facebook':
            window.location.href="#facebook"
            break;
        case 'talkme':
            window.location.href="#talkme"
            break;
        case 'onlinechat':
            window.location.href="#onlinechat"
            break;
        case 'whatsapp':
            window.location.href="#whatsapp"
            break;
        case 'viber':
            window.location.href="#viber"
            break;
        case 'webim':
            window.location.href="#webim"
            break;
        case 'whatsappbusiness':
            window.location.href="#whatsappbusiness"
            break;
        case 'livetex':
            window.location.href="#livetex"
            break;
        case 'avito':
            window.location.href="#avito"
            break;
        case 'odnoclassniki':
            window.location.href="#odnoclassniki"
            break;
        case 'instagram':
            window.location.href="#instagram"
            break;
        case 'add':
            window.location.href="#add"
            break;

    }}

document.getElementById("trigger").onclick = function() {
    open()};

function open() {
    document.getElementById("menu").classList.toggle("show");
    document.getElementById("trigger").classList.toggle("show");
}

//Тарифы
const services=["Online-chat","Бот для мессенджеров","Бот для Whatsapp","Сбор заявок","Графический редактор","Отправка медиафайлов","CRM","Перенос клиентской базы",
    "Инструменты оператора","Статистика","Рассылки","Bitrix, AmoCRM","Работа с API","Запись/бронирование",
    "Работа с Google таблицами","Программируемая логика","Высокая нагрузка","Оплаты в боте"]

const base="base",
    standart="standart",
    premium="premium";
const tarifs = new Map();
tarifs.set(base,10);
tarifs.set(standart,12);
tarifs.set(premium,18);

$(document).ready ( function(){

    FillServices();
    SetTarifs();
})
function FillServices(){
    var el=document.getElementById("services");
    services.forEach(v=>{
        var li = document.createElement('li');
        li.textContent=v;
        el.appendChild(li);
    }
)
}
function SetTarifs(){

    var elindex=0;
    for( var [key,value] of tarifs){

        var el=document.getElementById(key);
        services.forEach(v=>{
            var li = document.createElement('li');
            var div = document.createElement('div');
            var p=document.createElement('p');
            p.textContent=v;
            if(elindex<value){
                li.classList.toggle("have");
            }
            else{
                li.classList.toggle("donthave");
            }
            li.appendChild(p)
            li.appendChild(div)

            el.appendChild(li);
            elindex++;})
       elindex=0;
        }
    }


   
//Каруселька


callOwlCarousel()
function callOwlCarousel(){
        $('.owl-carousel').owlCarousel({
            touchDrag: true,
            dots: true,
            loop: true,
            items: 1,
        })
}

//Чатик
document.getElementById("chattriger").onclick = function() {
    openChat()};
document.getElementById("chatclose").onclick = function() {
    openChat()};

function openChat() {
    document.getElementById("chatcontainer").classList.toggle("show");
    document.getElementById("chattriger").classList.toggle("notshow");
   /* document.getElementById('chatcontainer').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      sendMassegeBot(document.getElementById('usermassege1'),1);
    }
});*/
    PressEnter('chatcontainer', 'usermassege1', 1 ); 
}
function PressEnter(cntainerId, inputId, chatNumber) {
     document.getElementById(cntainerId).addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
      sendMassegeBot(document.getElementById(inputId),chatNumber);
    }
     });
}


function answerMassegeBot(answer,id){
    content = document.getElementsByClassName("contentChat")[id];

    var text = document.createElement('div');
    text.classList.toggle("text")
    text.textContent=answer;

    var botMassege=document.createElement('div');
    botMassege.classList.toggle("botmessage")
    botMassege.appendChild(text);

    content.appendChild(botMassege);

}

function sendAnswer( text,id){

    if(text == 'Привет!'){
        answerMassegeBot("Мяу...",id);
    }
    else if(text=="Пока!"){
        answerMassegeBot("Мяу, мяу, мяу...",id);
    }
    else {
        answerMassegeBot("Я вас не понимаю!",id);
    }
}

function sendMassegeBot(usermassege,id){
if(usermassege.value!='') {
    content = document.getElementsByClassName("contentChat")[id];

    var text = document.createElement('div');
    text.classList.toggle("text")
    text.textContent = usermassege.value;

    var userMassege = document.createElement('div');
    userMassege.classList.toggle("usermessage")
    userMassege.appendChild(text);

    content.appendChild(userMassege);

    sendAnswer(usermassege.value,id);
    usermassege.value = "";

}
}

