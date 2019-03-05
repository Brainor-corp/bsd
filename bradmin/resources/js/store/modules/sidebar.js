// initial state
const state = {
    sidebarOpen: false,
    sidebarFixed: false,
    sidebarClass: '',
    logoUrl: window.logoUrl,
    logoMiniUrl: window.logoMiniUrl,
};

// mutations
const mutations = {
    sidebarOpenState (state, newState) {
        state.sidebarOpen = newState;
    },
    sidebarFixedState (state, newState) {
        state.sidebarFixed = newState;
    },
    sidebarClassChange (state, newState) {
        state.sidebarClass = newState;
    },
};

export default {
    state,
    mutations
}