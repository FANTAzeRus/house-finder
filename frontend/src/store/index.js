import {createStore} from "vuex";
import axios from "axios";
import {setup} from "../config.js";

export default createStore({
  state: {
    app: {
      isReady: false,
      loading: false,
      error: null,
    },

    bedrooms: [],
    bathrooms: [],
    storeys: [],
    garages: [],

    searchResut: [],
  },
  actions: {
    initApp({commit}) {
      commit("startLoading");
      axios.get(`${setup.apiUrl}/init`).then(({data}) => {
        if (data.success) {
          commit("setInitData", data.init_data);
          commit("startApp");
          commit("stopLoading");
        }
      });
    },

    clearSearchResult({commit}) {
      commit("setFindResult", []);
      commit("clearError");
    },

    search({commit}, payload) {
      commit("startLoading");
      const data = {};
      Object.keys(payload)
        .filter((k) => payload[k] !== null)
        .forEach((k) => (data[k] = payload[k]));

      axios
        .post(`${setup.apiUrl}/search`, data)
        .then(({data}) => {
          if (data.success) {
            commit("setFindResult", data.finded);
            commit("stopLoading");
            commit("clearError");

            return;
          }

          commit("setFindResult", []);
          commit("setError", "Couldn't find anything!");
          commit("stopLoading");
        })
        .catch(() => {
          commit("setFindResult", []);
          commit("setError", "Couldn't find anything!");
          commit("stopLoading");
        });
    },
  },
  mutations: {
    startApp(state) {
      state.app.isReady = true;
    },

    startLoading(state) {
      state.app.loading = true;
    },

    stopLoading(state) {
      state.app.loading = false;
    },

    clearError(state) {
      state.app.error = null;
    },

    setError(state, error) {
      state.app.error = error;
    },

    setInitData(state, payload) {
      state.bedrooms = payload.bedrooms;
      state.bathrooms = payload.bathrooms;
      state.storeys = payload.storeys;
      state.garages = payload.garages;
    },

    setFindResult(state, payload) {
      state.searchResut = payload;
    },
  },
  getters: {
    app: (state) => state.app,

    dictionaries: (state) => ({
      bedrooms: state.bedrooms,
      bathrooms: state.bathrooms,
      storeys: state.storeys,
      garages: state.garages,
    }),

    finded: (state) => state.searchResut,
  },
});
