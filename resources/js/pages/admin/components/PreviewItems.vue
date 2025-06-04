<template>
    <v-row class="d-flex flex-row flex-wrap ga-2">

        <div v-for="(file, i) in preview_files" :key="i" class="">
            <v-img :src="file.path" :height="file.height" :width="file.width" cover @click="onClick(file)" />
            <div class="d-flex flex-column flex-wrap" :style="'width:' + file.width + 'px;'">
                <div class="text-caption" :class="divClass(file)" :title="file.name"
                    style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                    {{ file.name }}
                </div>
                <div class="d-flex flex-row flex-wrap align-center ga-1">
                    <v-btn size="small" @click="showFullImage(file)"><v-icon icon="mdi-magnify-expand" /></v-btn>
                    <v-btn size="small" :href="'/mediamanager/download?file=' + file.original_path"
                        target="_blank"><v-icon icon="mdi-download" /></v-btn>
                </div>
            </div>
        </div>
    </v-row>

    <v-dialog v-model="is_full_image" fullscreen transition="dialog-bottom-transition" @click="is_full_image = false">
        <div>
            <v-img :src="full_image.original_path" max-height="90vh" max-width="100%" contain />
            <div class="d-flex justify-center">
                <div class="text-center bg-white text-body-2 pa-1"> {{ full_image.name }}</div>
            </div>
        </div>
    </v-dialog>

</template>


<script>
import { mapWritableState } from "pinia";
import { useMediamanagerStore } from "../../../stores/MediamanagerStore";

export default {
    props: ['preview_files'],

    async beforeMount() {
        this.mediamanagerStore = useMediamanagerStore();
    },

    data() {
        return {
            mediamanagereStore: null,
            is_full_image: false,
            full_image: null,
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['selected_files']),
    },

    methods: {

        showFullImage(image) {
            this.full_image = image;
            this.is_full_image = true;

        },

        onClick(file) {
            if (this.selected_files.includes(file.name)) {
                const index = this.selected_files.indexOf(file.name);
                if (index !== -1) {
                    this.selected_files.splice(index, 1);
                }
            } else {
                this.selected_files.push(file.name);
            }

        },

        divClass(file) {
            var my_class = '';
            if (this.selected_files.includes(file.name)) { my_class += ' font-weight-bold mm-bg-folder'; }
            return my_class;
        }

    }

}
</script>
<style scoped>
.hover {
    cursor: pointer;
}
</style>
