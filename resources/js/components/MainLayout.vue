<template>
    <div>
        <v-navigation-drawer v-model="drawer" app>
            <v-list dense>
                <v-list-item
                    link
                    href="/electricity"
                    :input-value="isCurrentLink('/electricity')"
                >
                    <v-list-item-action>
                        <v-icon>mdi-flash</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Электричество</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item
                    link
                    href="/regions"
                    :input-value="isCurrentLink('/regions')"
                >
                    <v-list-item-action>
                        <v-icon>mdi-map</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Регионы</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar app color="primary" dark>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
            <v-toolbar-title>Электроснабжение</v-toolbar-title>
        </v-app-bar>

        <v-content>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col class="text-center">
                        <slot></slot>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </div>
</template>

<script>
export default {
    data() {
        return {
            drawer: false
        };
    },
    methods: {
        isCurrentLink(link) {
            return location.pathname == link;
        }
    }
};
</script>
