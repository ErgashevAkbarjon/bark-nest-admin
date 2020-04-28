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
                                <v-btn color="primary" type="submit">
                                    {{ labels.submit }}
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                    <v-data-table
                        :headers="electricityData.headers"
                        :items="electricityData.data"
                        class="elevation-4"
                        :no-data-text="labels.noData"
                        :loading-text="labels.loading"
                        :loading="electricityLoading"
                    />
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
            electricityPaginaiton: null
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

            let regionIdsString = this.selectedDistricts
                .map(r => r.id)
                .toString();

            let url = `/api/electricity?groupBy=date&date_from=${this.dateFrom}&date_to=${this.dateTo}&region_id=${regionIdsString}`;

            axios
                .get(url)
                .then(r => {
                    this.setElectricityData(r.data);
                })
                .catch(e => {
                    this.electricityLoading = false;
                });
        },
        setPaginationIfExists(data){
            if(data.hasOwnProperty('data')){
                this.electricityPaginaiton = {...data};
                delete electricityPaginaiton.data;

                return true;
            }
        },
        setElectricityData(data) {
            this.electricityData.headers = [{ text: "Дата", value: "date" }];
            
            for (const district of this.selectedDistricts) {
                this.electricityData.headers.push({
                    text: district.name,
                    value: `${district.id}`
                })
            }

            let electricities = data;

            let dataWithPagination = this.setPaginationIfExists(data);

            if(dataWithPagination){
                electricities = {...data.data};
            }

            let preparedData = [];

            for (const e in electricities) {
                if (electricities.hasOwnProperty(e)) {
                    
                    let readyData = {date: e};

                    for (const electricityInDate of electricities[e]) {
                        readyData[electricityInDate.region_id] = electricityInDate.hours;
                    }

                    preparedData.push(readyData);
                }
            }
            
            this.electricityData.data = preparedData;

            this.electricityLoading = false;
        }
    },
    watch: {
        region(v){
            this.selectedDistricts = [];
        }
    }
};
</script>
