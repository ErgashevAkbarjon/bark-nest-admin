<template>
    <v-row justify="center">
        <v-col cols="10">
            <v-card class="elevation-3">
                <v-card-title>
                    Поиск данных
                    <v-spacer />
                    <v-btn color="primary" dark href="/electricity/create">
                        Добавить
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <date-picker label="Выберите дату" v-model="date" />
                        </v-col>
                        <v-col>
                            <v-select
                                label="Выберите область"
                                :items="regions"
                                v-model="region"
                                outlined
                                item-text="name"
                                return-object
                            />
                        </v-col>
                        <v-col>
                            <v-select
                                label="Выберите район"
                                :items="districts"
                                v-model="district"
                                outlined
                                item-text="name"
                                return-object
                            />
                        </v-col>
                    </v-row>
                    <v-data-table
                        :headers="headers"
                        :items="electricity"
                        class="elevation-2"
                        hide-default-footer
                        no-data-text="Заполните поля выше"
                        loading-text="Загрузка"
                        :loading="electricityLoading"
                    >
                        <template v-slot:item.actions="{ item }">
                            <v-icon
                                small
                                class="mr-2"
                                @click="editElectricity(item)"
                            >
                                mdi-pencil
                            </v-icon>
                            <v-icon small @click="deleteElectricity(item)">
                                mdi-delete
                            </v-icon>
                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
            <v-dialog
                v-model="dialog"
                max-width="500px"
                @click:outside="closeDialog"
            >
                <v-card>
                    <v-form :action="formData.action" method="POST">
                        <input
                            type="hidden"
                            name="_method"
                            :value="formData.method"
                        />
                        <input type="hidden" name="_token" :value="csrf" />

                        <div v-if="electricityToEdit">
                            <v-card-title>
                                Изменение данных
                            </v-card-title>
                            <v-card-text>
                                <time-input
                                    label="Количество часов"
                                    name="hours"
                                    :value="electricityToEdit.hours"
                                />
                                <v-textarea
                                    label="Коментарии"
                                    name="comment"
                                    outlined
                                    :value="electricityToEdit.comment"
                                />
                            </v-card-text>
                        </div>
                        <div v-if="electricityToDelete">
                            <v-card-text class="text-center body-1">
                                Вы действительно хотите удалить данные по району
                                <b>{{ electricityToDelete.region.name }}</b> за
                                <b>{{ electricityToDelete.date }}</b> ?
                            </v-card-text>
                        </div>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary darken-1"
                                text
                                @click="closeDialog()"
                            >
                                Отмена
                            </v-btn>
                            <v-btn color="primary darken-1" text type="submit">
                                {{ formData.actionName }}
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>
        </v-col>
    </v-row>
</template>

<script>
import TimeInput from "../TimeInput";

export default {
    props: ["regions"],
    components: { TimeInput },
    data() {
        return {
            date: null,
            region: null,
            district: null,
            electricity: [],
            headers: [
                {
                    text: "Область",
                    value: "region.parent.name"
                },
                { text: "Регион", value: "region.name" },
                { text: "Дата", value: "date" },
                { text: "Количество часов", value: "hours" },
                { text: "Дата добавления", value: "created_at" },
                {
                    text: "Действия",
                    value: "actions",
                    sortable: false,
                    align: "right"
                }
            ],
            electricityLoading: false,
            dialog: false,
            electricityToEdit: null,
            electricityToDelete: null
        };
    },
    methods: {
        fetchElectricity() {
            this.electricityLoading = true;

            let url = `/api/electricity?date=${this.date}&region_id=${this.district.id}`;

            axios
                .get(url)
                .then(r => {
                    this.electricityLoading = false;
                    this.electricity = r.data;
                })
                .catch(e => {
                    this.electricityLoading = false;
                    console.log(e);
                });
        },
        editElectricity(electricity) {
            this.electricityToEdit = electricity;
            this.dialog = true;
        },
        deleteElectricity(electricity) {
            this.electricityToDelete = electricity;
            this.dialog = true;
        },
        closeDialog() {
            this.electricityToEdit = null;
            this.electricityToDelete = null;

            this.dialog = false;
        }
    },
    computed: {
        districts() {
            if (!this.region) return [];

            return this.region.subregions;
        },
        formData() {
            if (this.electricityToDelete) {
                return {
                    method: "DELETE",
                    action: "/electricity/" + this.electricityToDelete.id,
                    actionName: "Удалить"
                };
            }

            if (this.electricityToEdit) {
                return {
                    method: "PUT",
                    action: "/electricity/" + this.electricityToEdit.id,
                    actionName: "Сохранить"
                };
            }

            return {
                method: "",
                action: "",
                actionName: ""
            };
        },
        csrf() {
            return Laravel.csrf;
        }
    },
    watch: {
        date(v) {
            if (v && this.district) {
                this.fetchElectricity();
            }
        },
        district(v) {
            if (v && this.date) {
                this.fetchElectricity();
            }
        }
    }
};
</script>
