<template>

    <v-card class="mm-bg-folder mm-text-folder mm-border-folder" width="150" height="100">
        <v-card-text class="text-caption d-flex flex-column align-center justify-center h-100 w-100">
            <div>{{ folder.name }}</div>
            <v-icon class="mm-icon-folder" icon="mdi-folder" size="large" />
            <div>{{ folder.folders_count }} Ordner, {{ folder.files_count }} Dateien</div>
        </v-card-text>
    </v-card>

</template>


<script>
export default {
    props: ['folder'],

    data() {
        return {

        };
    },

    methods: {

    }

}
</script>
