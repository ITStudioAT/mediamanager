<template>

    <div v-for="(file, i) in preview_files" :key="i" class="d-flex flex-column align-center hover"
        style="max-width: 150px;" @click="onClick(file)">
        <v-img :src="file.path" class="w-100 h-100" />
        <div class="text-caption" :class="divClass(file)" :title="file.name"
            style="display: block; width: 100%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
            {{ file.name }}
        </div>
    </div>
</template>


<script>
import { mapWritableState } from "pinia";
import { useMediamanagerStore } from "../stores/MediamanagerStore";

export default {
    props: ['preview_files'],

    async beforeMount() {
        this.mediamanagerStore = useMediamanagerStore();
    },

    data() {
        return {
            mediamanagereStore: null,
        };
    },

    computed: {
        ...mapWritableState(useMediamanagerStore, ['selected_files']),
    },

    methods: {

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
