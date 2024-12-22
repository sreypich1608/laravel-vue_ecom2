import { createStore } from 'vuex';

const store = createStore({
    state: {
        deleteModalObj: {
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            deleteIndex: -1,
            isDeleted: false,
        },
        user:false,
        userPermission:null,
    },
    getters: {
        getDeleteModalObj(state) {
            return state.deleteModalObj;
        },
        getUserPermission(state) {
            return state.userPermission;
        },
    },
    mutations: {
        setDeleteModal(state,data) {
            const deleteModalObj = {
                showDeleteModal: false,
                deleteUrl: '',
                data: null,
                deleteIndex: -1,
                isDeleted: data,
            }
            state.deleteModalObj = deleteModalObj
        },
        setDeleteModalObj(state, payload) {
            // Ensure we are properly updating the state with the correct data
            state.deleteModalObj = { ...state.deleteModalObj, ...payload };
        },
        setUpdateUser(state, payload) {
            state.user = payload ;  // Spread the payload to update the state
        },
        setUserPermission(state, payload) {
            state.userPermission = payload ;  // Spread the payload to update the state
        },
    },
});

export default store;
