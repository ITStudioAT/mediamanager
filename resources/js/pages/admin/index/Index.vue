<template>

    <v-container fluid class="ma-0 w-100 pa-2">
        <!-- MENÜ -->
        <v-row class="d-flex flex-row ga-2 my-4 mt-0 w-100" no-gutters>
            <v-col cols="12" class="d-flex flex-row flex-wrap align-center ga-2">
                <its-menu-button title="Homepage" icon="mdi-home"
                    :color="active_element === 'home' ? 'mm-bg-primary text-white' : 'mm-bg-secondary'"
                    @click="activate('home')" />

            </v-col>
        </v-row>

        <v-row>
            <v-col class="d-flex flex-row flex-wrap align-center ga-2">
                <FolderUp :parent="parent" v-for="(parent, i) in parent_folders" :is_current="parent == current_folder"
                    @click="onFolderUp(parent)" />
            </v-col>
        </v-row>

        <v-row class="my-4">
            <v-col class="d-flex flex-row align-center ga-2 flex-wrap">
                <Folder :folder="folder" v-for="(folder, i) in folders" @click="onFolder(folder)" />
            </v-col>
        </v-row>
        <v-row class="my-4">
            <ColBox :title="current_folder?.name" color="var(--mm-bg-color-folder)" icon="mdi-file">
                <div class="pt-1">
                    <div v-for="(file, i) in files">
                        <File :file="file" @click="" />
                    </div>
                </div>
            </ColBox>
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
export default {

    components: { ItsMenuButton, ColBox, Folder, File, FolderUp },

    async beforeMount() {
        this.mediamanagerStore = useMediamanagerStore();
        await this.mediamanagerStore.folderStructure();
    },


    unmounted() {
    },

    data() {
        return {
            mediamanagereStore: null,
            active_element: 'home',
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['folders', 'files', 'current_folder', 'parent_folders']),
    },

    methods: {




        onFolderUp(parent) {
            const found = this.parent_folders.find((item) => item.path == parent.path);
            const index = this.parent_folders.findIndex(item => item.path === parent.path);
            if (index !== -1) {
                this.parent_folders.splice(index);
            }

            if (this.parent_folders.length >= 1) { this.current_folder = this.parent_folders[this.parent_folders.length - 1]; } else { this.current_folder = null; }

            this.mediamanagerStore.folderStructure(this.current_folder?.path);
        },

        onFolder(folder) {
            this.parent_folders.push(folder);
            this.current_folder = folder;
            this.mediamanagerStore.folderStructure(folder?.path);
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