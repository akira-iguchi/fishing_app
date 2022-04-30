// クッキーからトークンを取り出して、HTTP ヘッダーにそのトークンを含めてリクエストを送信しても CSRF チェックがかかる
import { getCookieValue } from './util'

window.axios = require('axios')

// Ajaxリクエストであることを示す X-Requested-With ヘッダーを付与
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.axios.interceptors.request.use(config => {
    // クッキーからトークンを取り出してヘッダーに添付する
    config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')

    return config
})

window.axios.interceptors.response.use(
    response => response,
    error => error.response || error
)