// util == 役に立つもの

export const OK = 200
export const CREATED = 201
export const NOT_FOUND = 404
export const UNAUTHORIZED = 419
export const UNPROCESSABLE_ENTITY = 422
export const INTERNAL_SERVER_ERROR = 500

export function getCookieValue (searchKey) {
    if (typeof searchKey === 'undefined') {
        return ''
    }

    let val = ''

    // document.cookieの形式 → name=12345;token=67890;key=abcde
    document.cookie.split(';').forEach(cookie => {
        const [key, value] = cookie.split('=')
        if (key === searchKey) {
            return val = value
        }
    })

    return val
}