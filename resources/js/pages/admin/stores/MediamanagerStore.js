import { defineStore } from 'pinia'

export const useMediamanagerStore = defineStore("MMMediamanagerStore", {

    state: () => {
        return {
            is_loading: 0,

        }
    },

    actions: {


        async folderStructure(path = null) {
            this.is_loading++;
            try {

                const response = await axios.get("/api/mediamanager/folder_structure", { params: { path } });
                console.log(response);
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },




    }
})

