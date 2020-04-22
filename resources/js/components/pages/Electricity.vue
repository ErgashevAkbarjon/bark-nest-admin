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
                    ></v-data-table>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ["regions"],
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
                { text: "Дата добавления", value: "created_at" }
            ],
            electricityLoading: false
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
        }
    },
    computed: {
        districts() {
            if (!this.region) return [];

            return this.region.subregions;
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
