import axios from "axios";

export default {
    async makeAxiosFactory(link, method = 'GET', data = null, config = null) {

        if (!navigator.onLine){
            return Promise.reject("Вы не в сети!");
        }

        let result;

        let botDomain = window.currentBot.bot_domain || null

        axios.defaults.headers.common['X-CASHMAN-MOBILE-BOT-DOMAIN'] = botDomain ? btoa(botDomain) : null

    /*    let tgData = window.Telegram ? (window.Telegram.WebApp.initData || null ) : null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        axios.defaults.headers.common['X-CASHMAN-SLUG-ID'] = slugId ? btoa(slugId) : null
        axios.defaults.headers.common['X-CASHMAN-BOT-DOMAIN'] = botDomain ? btoa(botDomain) : null
        axios.defaults.headers.common['X-CASHMAN-TG-DATA'] = tgData ? btoa(tgData) : null
*/
        switch (method.toUpperCase()) {
            default:
            case 'GET':
                result = await axios.get(link);
                break;
            case 'POST':
                result = await axios.post(link, data, config)
                break;
            case 'PUT':
                result = await axios.put(link, data)
                break;
            case 'DELETE':
                result = await axios.delete(link)
                break;
        }

        return result;
    },


}
