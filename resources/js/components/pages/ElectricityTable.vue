<template>
    <v-row justify="center">
        <v-col cols="12">
            <v-card class="elevation-2 mx-2" outlined>
                <v-card-title>
                    {{ labels.mainTitle }}
                </v-card-title>
                <v-card-text>
                    <v-form @submit="onFormSubmit" ref="form">
                        <v-row>
                            <v-col class="py-0">
                                <v-select
                                    :label="labels.region"
                                    :items="regions"
                                    v-model="region"
                                    outlined
                                    dense
                                    item-text="name"
                                    return-object
                                />
                            </v-col>
                            <v-col class="py-0">
                                <v-select
                                    :label="labels.district"
                                    :no-data-text="labels.region"
                                    :items="districts"
                                    v-model="selectedDistricts"
                                    outlined
                                    dense
                                    multiple
                                    item-text="name"
                                    return-object
                                    :rules="requiredRule"
                                />
                            </v-col>
                            <v-col class="py-0">
                                <date-picker
                                    :label="labels.dateFrom"
                                    v-model="dateFrom"
                                    dense
                                    :rules="requiredRule"
                                />
                            </v-col>
                            <v-col class="py-0">
                                <date-picker
                                    :label="labels.dateTo"
                                    v-model="dateTo"
                                    dense
                                    :rules="requiredRule"
                                />
                            </v-col>
                            <v-col class="py-0">
                                <v-btn color="primary" type="submit" block>
                                    {{ labels.submit }}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                    <v-row >
                        <v-col cols="4">
                            <v-text-field
                                class="mb-3"
                                v-model="search"
                                append-icon="mdi-magnify"
                                :label="labels.search"
                                outlined
                                dense
                                single-line
                                hide-details
                            />
                        </v-col>
                    </v-row>
                    <v-data-table
                        :headers="electricityData.headers"
                        :items="electricityData.data"
                        class="elevation-4"
                        :no-data-text="labels.noData"
                        :loading-text="labels.loading"
                        :loading="electricityLoading"
                        :search="search"
                    >
                        <template v-slot:item="{item, headers}">

                            <td v-for="(header, i) in headers" :key="i">

                                <span v-if="header.value === 'date'">
                                    {{item.date}}
                                </span>

                                <v-tooltip top v-else>
                                    <template v-slot:activator="{ on, attrs }">
                                        <span v-bind="attrs" v-on="on">{{item[header.value][0]}}</span>
                                    </template>

                                    <div>Утренняя подача: {{item[header.value][1]}}</div>
                                    <div>Вечерняя подача: {{item[header.value][2]}}</div>
                                </v-tooltip>

                            </td>

                        </template>
                    </v-data-table>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ["regions", "language"],
    data() {
        return {
            dateFrom: null,
            dateTo: null,
            region: null,
            selectedDistricts: null,
            electricityLoading: false,
            electricityData: {
                headers: [],
                data: []
            },
            search: ''
        };
    },
    computed: {
        labels() {
            switch (this.language) {
                case "tj":
                    return {
                        mainTitle:
                            "Вазъият доир ба таъмини қувваи барқ аз рӯи интихоби истифодабарандаи сомона",
                        dateFrom: "Аз санаи",
                        dateTo: "То санаи",
                        region: "Минтақаро интихоб намоед",
                        district: "Ноҳияро интихоб намоед",
                        submit: "Нишон додан",
                        required: "Бояд пур карда шавад",
                        search: "Чустучу",
                        noData: "",
                        loading: "Интизор шавед ..."
                    };
                case "en":
                    return {
                        mainTitle:
                            "Situation with the electricity supply on the visitor’s selection",
                        dateFrom: "From",
                        dateTo: "To",
                        region: "Select the region",
                        district: "Select the district",
                        submit: "Show",
                        required: "Required",
                        search: "Search",
                        noData: "No data",
                        loading: "Loading ..."
                    };
                default:
                    return {
                        mainTitle:
                            "Ситуация с электроснабжением по выбору пользователя",
                        dateFrom: "Дата с",
                        dateTo: "Дата по",
                        region: "Выберите область",
                        district: "Выберите район",
                        submit: "Показать",
                        required: "Обязательное поле",
                        search: "Поиск",
                        noData: "Нет данных",
                        loading: "Загрузка ..."
                    };
            }
        },
        requiredRule() {
            return [v => !!v || this.labels.required];
        },
        districts() {
            if (!this.region) return [];

            return this.region.subregions;
        }
    },
    methods: {
        onFormSubmit(e) {
            e.preventDefault();

            const validData = this.$refs.form.validate();

            if (validData) {
                this.fetchElectrocities();
            }
        },
        fetchElectrocities() {
            this.electricityLoading = true;
            this.electricityData = {
                headers: [],
                data: []
            };

            let regionIdsString = this.selectedDistricts
                .map(r => r.id)
                .toString();

            let url = `/api/electricity/table?date_from=${this.dateFrom}&date_to=${this.dateTo}&region_id=${regionIdsString}`;

            axios
                .get(url)
                .then(r => {
                    this.setElectricityData(r.data);
                })
                .catch(e => {
                    this.electricityLoading = false;
                });
        },
        setElectricityData(data) {
            this.electricityData.headers = [{ text: "Дата", value: "date" }];

            for (const district of this.selectedDistricts) {
                this.electricityData.headers.push({
                    text: district.name,
                    value: `${district.id}`
                });
            }

            this.electricityData.data = data;

            this.electricityLoading = false;
        }
    },
    watch: {
        region(v) {
            this.selectedDistricts = [];
        }
    }
};
</script>
