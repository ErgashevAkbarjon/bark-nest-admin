<template>
    <v-row justify="center">
        <v-col cols="10">
            <v-data-table
                :headers="headers"
                :items="regions"
                class="elevation-3"
                disable-pagination
                hide-default-footer
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Регионы</v-toolbar-title>
                        <v-divider class="mx-4" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    color="primary"
                                    dark
                                    class="mb-2"
                                    v-on="on"
                                >
                                    Добавить
                                </v-btn>
                            </template>
                            <v-card>
                                <v-form :action="formData.action" method="POST">
                                    <input
                                        type="hidden"
                                        name="_method"
                                        :value="formData.method"
                                    />
                                    <input
                                        type="hidden"
                                        name="_token"
                                        :value="csrf"
                                    />
                                    <v-card-title>
                                        <span class="headline">{{
                                            formTitle
                                        }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <div
                                            class="text-center title"
                                            v-if="regionToDelete"
                                        >
                                            Вы уверены что хотите удалить
                                            регион: {{ regionToDelete.name }} ?
                                        </div>
                                        <v-container v-else>
                                            <v-row>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-text-field
                                                        label="Название"
                                                        name="name"
                                                        :rules="required"
                                                        :value="
                                                            regionToEdit
                                                                ? regionToEdit.name
                                                                : null
                                                        "
                                                    />
                                                </v-col>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-select
                                                        :items="regionTypes"
                                                        label="Тип региона"
                                                        name="region_type_id"
                                                        :rules="required"
                                                        item-value="id"
                                                        item-text="name"
                                                        :value="
                                                            regionToEdit
                                                                ? regionToEdit.region_type_id
                                                                : null
                                                        "
                                                    />
                                                </v-col>
                                                <v-col cols="12" sm="6" md="4">
                                                    <v-select
                                                        :items="regionList"
                                                        label="Родительский регион"
                                                        name="parent_id"
                                                        item-value="id"
                                                        item-text="name"
                                                        :value="
                                                            regionToEdit
                                                                ? regionToEdit.parent_id
                                                                : null
                                                        "
                                                    />
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="close"
                                        >
                                            Отмена
                                        </v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            type="submit"
                                        >
                                            {{ formData.actionName }}
                                        </v-btn>
                                    </v-card-actions>
                                </v-form>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.name="{ item }">
                    <a
                        class="accent--text"
                        v-if="item.subregions.length"
                        :style="{ textDecoration: 'none'}"
                        :href="'/regions?parent_id=' + item.id"
                    >
                        {{ item.name }}
                    </a>

                    <span v-else>{{ item.name }}</span>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editRegion(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="deleteRegion(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ["regions", "regionTypes", "regionList"],
    data() {
        return {
            dialog: false,
            headers: [
                { text: "Имя", value: "name" },
                { text: "Тип", value: "type.name" }
            ],
            required: [v => !!v || "Обязательное поле"],
            regionToEdit: null,
            regionToDelete: null
        };
    },
    mounted() {
        if (this.authIsAdmin) {
            this.headers.push({
                text: "Действия",
                value: "actions",
                sortable: false,
                align: "right"
            });
        }
    },
    computed: {
        csrf() {
            return Laravel.csrf;
        },
        formData() {
            if (this.regionToDelete) {
                return {
                    method: "DELETE",
                    action: "/regions/" + this.regionToDelete.id,
                    actionName: "Удалить"
                };
            }

            if (this.regionToEdit) {
                return {
                    method: "PUT",
                    action: "/regions/" + this.regionToEdit.id,
                    actionName: "Сохранить"
                };
            }

            return {
                method: "POST",
                action: "/regions/",
                actionName: "Добавить"
            };
        },
        formTitle() {
            switch (this.formData.method) {
                case "POST":
                    return "Новый регион";
                case "PUT":
                    return "Изменить регион";
                case "DELETE":
                    return "";
            }
        },
        authIsAdmin() {
            return Laravel.auth.role_id == 1;
        }
    },

    watch: {
        dialog(val) {
            val || this.close();
        }
    },

    methods: {
        editRegion(region) {
            if(!this.authIsAdmin) return;

            this.regionToEdit = region;
            this.dialog = true;
        },

        deleteRegion(region) {
            if(!this.authIsAdmin) return;

            this.regionToDelete = region;
            this.dialog = true;
        },

        close() {
            this.regionToEdit = null;
            this.regionToDelete = null;
            this.dialog = false;
        }
    }
};
</script>
