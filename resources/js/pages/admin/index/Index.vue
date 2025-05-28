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
                <div class="bg-mm-primary text-h1">MediaManager</div>
            </v-col>
        </v-row>
    </v-container>

</template>

<script>

import ItsMenuButton from "../components/ItsMenuButton.vue";
import ColBox from "../components/ColBox.vue";
export default {

    components: { ItsMenuButton, ColBox },

    async beforeMount() {
    },


    unmounted() {
    },

    data() {
        return {
            active_element: 'home',

        };
    },

    computed: {

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