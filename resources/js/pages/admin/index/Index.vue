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

        <v-row class="my-4">
            <v-col>
                <Folder :folder="folder" v-for="(folder, i) in folders" @click="" />
            </v-col>
        </v-row>
        <v-row class="my-4">
            <ColBox title="Dateien" icon="mdi-file">
                <div class="pt-1">
                    <div v-for="(file, i) in files">
                        <File :file="file" />
                    </div>
                </div>
            </ColBox>
        </v-row>
        <v-row class="my-4">
            <v-col>
                {{ files }}
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
import File from "../components/File.vue";
export default {

    components: { ItsMenuButton, ColBox, Folder, File },

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
        ...mapWritableState(useMediamanagerStore, ['folders', 'files', 'path']),
    },

    methods: {
        activate(element) {
            console.log(element);
            if (this.active_element == element) {
                this.active_element = null;
            } else {
                this.active_element = element;
            }

            console.log("now:" + this.active_element);
        },

    }

}
</script>