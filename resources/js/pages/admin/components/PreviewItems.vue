<template>
    <v-row class="d-flex flex-row flex-wrap ga-2">

        <div v-for="(file, i) in preview_files" :key="i" class="" @click="onClick(file)">
            <v-img :src="file.path" :height="file.height" :width="file.width" cover />


            <div class="text-caption" :class="divClass(file)" :title="file.name"
                style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"
                :style="'width:' + file.width + 'px;'">
                {{ file.name }}
            </div>


        </div>
    </v-row>
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
