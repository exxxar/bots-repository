import axios from "axios";

export default {
    async makeAxiosFactory(link, method = 'GET', data = null, config = null) {
        let result;

        let tgData = window.Telegram ? (window.Telegram.WebApp.initData || null ) : null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        axios.defaults.headers.common['X-CASHMAN-SLUG-ID'] = slugId ? btoa(slugId) : null
        axios.defaults.headers.common['X-CASHMAN-BOT-DOMAIN'] = botDomain ? btoa(botDomain) : null
        axios.defaults.headers.common['X-CASHMAN-TG-DATA'] = tgData ? btoa(tgData) : null

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
    async loadActualProducts(ids = []) {

        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null


        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ids: ids
        }

        let link = `/bot-client/shop/products/by-ids`
        let method = 'POST'

        let _axios = this.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            const products = response.data.data;
            return Promise.resolve(products);
        }).catch(err => {
            return Promise.reject(err);
        })
    },
}
