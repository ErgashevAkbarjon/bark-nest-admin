<template>
    <v-row justify="center">
        <v-col cols="10">
            <v-card class="elevation-3">
                <v-card-title>
                    Добавление данных
                </v-card-title>
                <v-card-text>
                    <v-form ref="form">
                        <v-row>
                            <v-col>
                                <date-picker
                                    label="Выберите дату"
                                    v-model="date"
                                    :rules="requiredRule"
                                />
                            </v-col>
                            <v-col>
                                <v-select
                                    label="Выберите область"
                                    :items="regions"
                                    v-model="region"
                                    outlined
                                    item-text="name"
                                    return-object
                                    :rules="requiredRule"
                                />
                            </v-col>
                        </v-row>

                        <v-row v-if="region">
                            <v-col
                                cols="4"
                                v-for="(district, i) in region.subregions"
                                :key="i"
                            >
                                <district-electricity-form 
                                    :district="district"
                                />
                            </v-col>
                        </v-row>
                        <div
                            class="text-right"
                            v-if="region"
                            @click="saveRegionData()"
                        >
                            <v-btn type="button" color="green" dark>Сохранить</v-btn>
                        </div>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import DistrictElectricityForm from "../DistrictElectricityForm";

export default {
    props: ["regions"],
    components: { DistrictElectricityForm },
    data() {
        return {
            date: null,
            region: null,
            requiredRule: [v => !!v || "Обязательное поле"]
        };
    },
    watch: {
        region(v) {
            for (const district of v.subregions) {
                district.hours = "24.00";
                district.dayPeriod = "00:00 - 12:00";
                district.nightPeriod = "12:00 - 00:00";
            }
        }
    },
    methods: {
        saveRegionData() {
            const valid = this.$refs.form.validate();
            if(!valid) return;

            let dataToStore = this.region.subregions.map(r => ({
                region_id: r.id,
                date: this.date,
                hours: r.hours,
                day_period: r.dayPeriod,
                night_period: r.nightPeriod
            }));

            axios.post("/electricity", { regionData: dataToStore }).then(r => {
                this.$refs.form.reset();
            });
        }
    }
};
</script>
