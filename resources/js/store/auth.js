// ストアを参照することで、どのコンポーネントでも参照できる
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

// ステートはデータの入れ物そのもの。ログイン中のユーザーデータなどが該当
const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
}

// ゲッターはステートの内容から算出される値
// stateから参照するより簡単に参照できる
const getters = {
    check: state => !! state.user,
    AuthUser: state => state.user ? state.user : ''
}

// ミューテーションはステートを更新するためのメソッド、同期処理
const mutations = {
    setUser (state, user) {
        state.user = user
    },
    setApiStatus (state, status) {
        state.apiStatus = status
    },
    setLoginErrorMessages (state, messages) {
        state.loginErrorMessages = messages
    },
    setRegisterErrorMessages (state, messages) {
        state.registerErrorMessages = messages
    },
}

// アクションはステートを更新するためのメソッド、非同期処理
// API との通信などの非同期処理を行った後にミューテーションを呼び出してステートを更新する
const actions = {
    async register (context, data) {
        // アクションの第一引数にはコンテキストオブジェクトが渡され、
        // コンテキストオブジェクトにはミューテーションを呼び出すための commit メソッドなどが入る。
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/signup', data)

        if (response.status === CREATED) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setRegisterErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    async login (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/login', data)

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setLoginErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    async guestLogin (context, data) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/guest', data)

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', response.data)
            return false
        }

        context.commit('setApiStatus', false)
        if (response.status === UNPROCESSABLE_ENTITY) {
            context.commit('setLoginErrorMessages', response.data.errors)
        } else {
            context.commit('error/setCode', response.status, { root: true })
        }
    },
    async logout (context) {
        context.commit('setApiStatus', null)
        const response = await axios.post('/api/logout')

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', null)
            return false
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    },
    async currentUser (context) {
        context.commit('setApiStatus', null)
        const response = await axios.get('/api/user')
        const user = response.data || null

        if (response.status === OK) {
            context.commit('setApiStatus', true)
            context.commit('setUser', user)
            return false
        }

        context.commit('setApiStatus', false)
        context.commit('error/setCode', response.status, { root: true })
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}