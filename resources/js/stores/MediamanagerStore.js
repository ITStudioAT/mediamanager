import { defineStore } from 'pinia'

export const useMediamanagerStore = defineStore("MMMediamanagerStore", {

    state: () => {
        return {
            is_loading: 0,
            is_loading_preview: 0,
            current_folder: null,
            parent_folders: [],
            folders: [],
            files: [],
            preview_files: [],
            selected_files: [],


        }
    },

    actions: {


        async saveFilename(data) {
            this.is_loading++;
            try {
                const response = await axios.post("/api/mediamanager/save_filename", { data });
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async destroyFiles(files, path = null) {
            this.is_loading++;
            try {

                const response = await axios.post("/api/mediamanager/destroy_files", { files, path });
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },

        async destroyFolder(path) {
            this.is_loading++;
            console.log(path);
            try {

                const response = await axios.post("/api/mediamanager/destroy_folder", { path });
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },


        async folderStructure(path = null) {
            this.is_loading++;
            try {

                const response = await axios.get("/api/mediamanager/folder_structure", { params: { path } });
                this.folders = response.data?.folders;
                this.files = response.data?.files;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading--;
            }
        },


        async createPreview(path = null) {
            this.is_loading_preview++;
            try {

                const response = await axios.get("/api/mediamanager/create_preview", { params: { path } });
                this.preview_files = response.data;
            } catch (error) {
                this.redirect(error.response.status, error.response.data.message, 'error');
            } finally {
                this.is_loading_preview--;
            }
        },




    }
})

