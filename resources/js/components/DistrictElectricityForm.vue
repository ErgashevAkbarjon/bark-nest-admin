<template>
    <v-card outlined>
        <v-card-subtitle>
            {{ district.name }}
        </v-card-subtitle>
        <v-card-text>
            <time-input
                label="Часы"
                v-model="district.hours"
                :rules="requiredRule"
            />
            <v-text-field
                label="Часы подачи днем"
                outlined
                v-model="district.dayPeriod"
            />
            <v-text-field
                label="Часы подачи ночью"
                outlined
                v-model="district.nightPeriod"
            />
        </v-card-text>
    </v-card>
</template>

<script>
import TimeInput from "./TimeInput";

export default {
    props: {
        district: Object
    },
    components: { TimeInput },
}
</script>