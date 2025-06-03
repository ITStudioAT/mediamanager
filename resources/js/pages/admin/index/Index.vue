<template>

    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- MENÜ -->
        <v-row class="d-flex flex-row ga-2 mt-0 w-100 my-4" no-gutters>
            <v-col cols="12" class="d-flex flex-row flex-wrap align-center ga-2">
                <its-menu-button title="Homepage" icon="mdi-home"
                    :color="active_element === 'home' ? 'mm-bg-primary text-white' : 'mm-bg-secondary'"
                    @click="activate('home')" />

            </v-col>
        </v-row>
        <v-row>
            <v-col>
                FOLDER:
                {{ current_folder }}
            </v-col>
        </v-row>
        <v-row class="my-4" no-gutters>
            <v-col>
                <FileUpload :path="current_folder?.path" @fileUploadFinished="onFileUploadFinished" />
            </v-col>
        </v-row>

        <v-row no-gutters class="my-4" v-if="parent_folders.length > 0">
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <FolderUp :parent="parent" v-for="(parent, i) in parent_folders" :is_current="parent == current_folder"
                    @click="onFolderUp(parent)" />
            </v-col>
        </v-row>

        <v-row no-gutters class="my-4">
            <v-col class="d-flex flex-row align-center ga-2 flex-wrap">
                <Folder :folder="folder" v-for="(folder, i) in folders" @click="onFolder(folder)" />
            </v-col>
        </v-row>

        <v-row no-gutters class="my-4">
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <v-btn color="secondary" v-if="selected_files.length < files.length" @click="onSelectAll"><v-icon
                        icon="mdi-select-all" /></v-btn>
                <v-btn color="secondary" v-if="selected_files.length > 0" @click="onSelectOff"><v-icon
                        icon="mdi-select-off" /></v-btn>
                <v-btn color="secondary" v-if="selected_files.length > 0" @click="onSelectOff"><v-icon
                        icon="mdi-rename" /></v-btn>
                <v-btn color="warning" v-if="selected_files.length > 0 && delete_level == 0"
                    @click="delete_level = 1"><v-icon icon="mdi-delete" /></v-btn>
                <v-btn color="error" v-if="selected_files.length > 0 && delete_level == 1" @click="destroyFiles"><v-icon
                        icon="mdi-delete" /></v-btn>
            </v-col>
        </v-row>
        <v-row no-gutters class="my-4">
            <ColBox :title="current_folder?.name" color="var(--mm-bg-color-folder)" icon="mdi-file">
                <div class="pt-1">
                    <v-list v-model:selected="selected_files" select-strategy="classic">
                        <v-list-item v-for="(file, i) in files" :value="file.name">
                            <File :file="file" />
                        </v-list-item>
                    </v-list>
                </div>
            </ColBox>
            <v-col>
                <v-card tile flat :loading="is_loading_preview > 0">
                    <v-card-text v-if="is_loading_preview > 0">
                        <div class="text-h1">Vorbereiten der Vorschau...</div>
                    </v-card-text>
                    <v-card-text class="d-flex flex-row flex-wrap align-end ga-2">
                        <PreviewItems :preview_files="preview_files" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>




    </v-container>

</template>

<script>


import { mapWritableState } from "pinia";
import { useMediamanagerStore } from "../stores/MediamanagerStore";
import ItsMenuButton from "../components/ItsMenuButton.vue";
import ColBox from "../components/ColBox.vue";
import Folder from "../components/Folder.vue";
import FolderUp from "../components/FolderUp.vue";
import File from "../components/File.vue";
import PreviewItems from "../components/PreviewItems.vue";
import FileUpload from "../components/FileUpload.vue";
export default {

    components: { ItsMenuButton, ColBox, Folder, File, FolderUp, PreviewItems, FileUpload },

    async beforeMount() {
        this.mediamanagerStore = useMediamanagerStore();
        await this.mediamanagerStore.folderStructure();
        this.mediamanagerStore.createPreview();
    },


    unmounted() {
    },

    data() {
        return {
            mediamanagereStore: null,
            active_element: 'home',
            delete_level: 0,
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['folders', 'files', 'current_folder', 'parent_folders', 'preview_files', 'is_loading_preview', 'selected_files']),
    },

    methods: {

        async destroyFiles() {
            this.delete_level = 0;
            this.mediamanagerStore.destroyFiles(this.selected_files, this.current_folder?.path);
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
            this.selected_files = [];
        },

        onFileUploadFinished(response) {
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
            this.selected_files = [];
        },

        goError() {
            console.log("goError");
            this.$emit('error');
        },

        onSelectOff() {
            this.selected_files = [];
        },

        onSelectAll() {
            this.selected_files = this.files.map(file => file.name);
        },

        onFolderUp(parent) {
            const found = this.parent_folders.find((item) => item.path == parent.path);
            const index = this.parent_folders.findIndex(item => item.path === parent.path);
            if (index !== -1) {
                this.parent_folders.splice(index);
            }

            if (this.parent_folders.length >= 1) { this.current_folder = this.parent_folders[this.parent_folders.length - 1]; } else { this.current_folder = null; }

            this.selected_files = [];
            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
        },

        onFolder(folder) {
            this.parent_folders.push(folder);
            this.current_folder = folder;

            this.selected_files = [];
            this.mediamanagerStore.folderStructure(folder?.path);
            this.mediamanagerStore.createPreview(folder?.path);
        },
        activate(element) {
            console.log(element);
            if (this.active_element == element) {
                this.active_element = null;
            } else {
                this.active_element = element;
            }

        },

    }

}
</script>