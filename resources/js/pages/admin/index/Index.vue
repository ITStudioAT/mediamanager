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

        <!-- FILEUPLOAD -->

        <v-row class="my-4" no-gutters v-if="is_loading == 0">
            <v-col>
                <FileUpload :path="current_folder?.path" @fileUploadFinished="onFileUploadFinished" />
            </v-col>
        </v-row>
        <v-row>
            {{ current_folder }}
        </v-row>

        <!-- FOLDER-UP -->
        <v-row no-gutters class="my-4" v-if="parent_folders.length > 0 && is_loading == 0">
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <FolderUp :parent="parent" v-for="(parent, i) in parent_folders" :is_current="parent == current_folder"
                    @click="onFolderUp(parent)" />
            </v-col>
        </v-row>

        <!-- FOLDERS -->
        <v-row no-gutters class="my-4" v-if="is_loading == 0">
            <v-col class="d-flex flex-row align-center ga-2 flex-wrap">
                <Folder :folder="folder" v-for="(folder, i) in folders" @click="onFolder(folder)" />
            </v-col>
        </v-row>


        <!-- ACTION-BUTTONS -->
        <v-row no-gutters class="my-4" v-if="is_loading == 0">
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <!-- Alle selektieren -->
                <v-btn color="secondary" v-if="selected_files.length < files.length" @click="onSelectAll"><v-icon
                        icon="mdi-select-all" /></v-btn>
                <!-- Alle abwählen -->
                <v-btn color="secondary" v-if="selected_files.length > 0" @click="onSelectOff"><v-icon
                        icon="mdi-select-off" /></v-btn>
                <!-- Umbenennen -->
                <v-btn color="secondary" v-if="selected_files.length == 1" @click="rename"><v-icon
                        icon="mdi-rename" /></v-btn>
                <!-- Löschen -->
                <v-btn color="warning" v-if="selected_files.length > 0 && delete_level == 0"
                    @click="delete_level = 1"><v-icon icon="mdi-delete" /></v-btn>
                <!-- Doch nicht löschen -->
                <v-btn color="success" v-if="selected_files.length > 0 && delete_level == 1"
                    @click="delete_level = 0"><v-icon icon="mdi-delete-off" /></v-btn>
                <!-- Löschen durchführen -->
                <v-btn color="error" v-if="selected_files.length > 0 && delete_level == 1" @click="destroyFiles"><v-icon
                        icon="mdi-delete" /></v-btn>

            </v-col>
        </v-row>


        <v-row no-gutters class="my-4">
            <!-- FILE-LIST -->
            <ColBox :title="current_folder?.name" :subtitle="files.length + ' Dateien'"
                color="var(--mm-bg-color-folder)" icon="mdi-file">
                <div class="pt-1">
                    <v-list v-model:selected="selected_files" select-strategy="classic" :disabled="is_loading > 0">
                        <v-list-item v-for="(file, i) in files" :value="file.name" :key="i">
                            <File :file="file" />
                        </v-list-item>
                    </v-list>
                </div>
            </ColBox>


            <!-- PREVIEW -->
            <v-col>
                <v-card tile flat :loading="is_loading_preview > 0">
                    <v-card-text class="d-flex flex-row align-center justify-center" style="min-height: 300px;"
                        v-if="is_loading_preview > 0">
                        <div class="text-h6">Vorbereiten der Vorschau...</div>
                    </v-card-text>
                    <v-card-text class="d-flex flex-row flex-wrap align-end ga-2">
                        <PreviewItems :preview_files="preview_files" />
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- RENAME -->
        <v-dialog v-model="is_rename" persistent transition="dialog-bottom-transition" max-width="500">
            <v-card tile flat color="primary">
                <v-card-title>
                    Umbenennen
                </v-card-title>
                <v-card-text class="bg-secondary">
                    <v-form ref="form" v-model="is_valid">
                        <v-row no-gutters>
                            <v-col cols="12">
                                <v-text-field v-model="data.filename" label="Dateiname"
                                    :rules="[required(), maxLength(255)]" />
                            </v-col>
                        </v-row>
                        <v-row no-gutters class="mt-4">
                            <v-col cols="6">
                                <v-btn block flat tile text="Abbruch" color="error" prepend-icon="mdi-close"
                                    @click="is_rename = false" />
                            </v-col>
                            <v-col cols="6">
                                <v-btn block flat tile text="Speichern" color="success" prepend-icon="mdi-content-save"
                                    @click="onSaveFilename(data)" />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
            </v-card>
            <div class="bg-white">
                {{ data }}
            </div>
        </v-dialog>

    </v-container>

</template>


<script>
import { useValidationRulesSetup } from "../../../helpers/rules";
import { mapWritableState } from "pinia";
import { useMediamanagerStore } from "../../../stores/MediamanagerStore";
import ItsMenuButton from "../components/ItsMenuButton.vue";
import ColBox from "../components/ColBox.vue";
import Folder from "../components/Folder.vue";
import FolderUp from "../components/FolderUp.vue";
import File from "../components/File.vue";
import PreviewItems from "../components/PreviewItems.vue";
import FileUpload from "../components/FileUpload.vue";
export default {

    setup() { return useValidationRulesSetup(); },

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
            is_rename: false,
            data: {},
            is_valid: false,
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['folders', 'files', 'current_folder', 'parent_folders', 'preview_files', 'is_loading_preview', 'is_loading', 'selected_files']),
    },

    methods: {

        rename() {
            const fullName = this.selected_files[0];
            const dotIndex = fullName.lastIndexOf(".");
            const nameWithoutExt = fullName.substring(0, dotIndex);
            const extension = fullName.substring(dotIndex + 1);
            this.data = {
                path: this.current_folder ? this.current_folder.path : '',
                current_filename: fullName,
                filename: nameWithoutExt,
                extension: extension,
            }
            this.is_rename = true;
        },

        async onSaveFilename(data) {
            await this.mediamanagerStore.saveFilename(data);
        },

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

            this.selected_files = [];
            this.preview_files = [];
            const found = this.parent_folders.find((item) => item.path == parent.path);
            const index = this.parent_folders.findIndex(item => item.path === parent.path);
            if (index !== -1) {
                this.parent_folders.splice(index);
            }

            if (this.parent_folders.length >= 1) { this.current_folder = this.parent_folders[this.parent_folders.length - 1]; } else { this.current_folder = null; }

            this.mediamanagerStore.folderStructure(this.current_folder?.path);
            this.mediamanagerStore.createPreview(this.current_folder?.path);
        },

        onFolder(folder) {
            this.parent_folders.push(folder);
            this.current_folder = folder;

            this.selected_files = [];
            this.preview_files = [];
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