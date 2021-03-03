import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({

    // 共有するデータ
    state: {
        count: 0
    },

    // ミューテーション
    // ※stateのデータ変更はミューテーションが行う
    mutations: {
        setCount(state, payload) {
        state.count = state.count + payload;
        }
    },

    // ゲッター
    getters: {
        getCount(state) {
        return state.count;
        }
    },

    // アクション
    actions: {
        countAction(context, payload) {
        context.commit('setCount', payload);
        }
    }
})