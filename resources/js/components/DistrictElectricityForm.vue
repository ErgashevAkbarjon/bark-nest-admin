<template>
    <v-card outlined>
        <v-card-subtitle>
            {{ district.name }}
        </v-card-subtitle>
        <v-card-text>
            <time-input
                label="Часы"
                :value="hours"
                @input="updateHours"
                :rules="requiredRule"
            />
            <v-text-field
                label="Часы подачи днем"
                outlined
                :value="dayPeriod"
                @input="updateDayPeriod"
            />
            <v-text-field
                label="Часы подачи ночью"
                outlined
                :value="nightPeriod"
                @input="updateNightPeriod"
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
    data(){
        return {
            hours: this.district.hours,
            dayPeriod: this.district.dayPeriod,
            nightPeriod: this.district.nightPeriod
        }
    },
    components: { TimeInput },
    methods: {
        updateHours(hours){
            this.hours = hours;
            this.district.hours = hours;
        },
        updateDayPeriod(dayPeriod) {
            this.dayPeriod = dayPeriod;
            this.district.dayPeriod = dayPeriod;
            this.tryCalculate();
        },
        updateNightPeriod(nightPeriod) {
            this.nightPeriod = nightPeriod;
            this.district.nightPeriod = nightPeriod;
            this.tryCalculate();
        },
        tryCalculate(){
            let dayTotal = 0.00;
            let nightTotal = 0.00;

            if(this.dayPeriod){
                dayTotal = this.getTimePeriod(this.dayPeriod);
            }

            if(this.nightPeriod){
                nightTotal = this.getTimePeriod(this.nightPeriod);
            }

            
            let total = dayTotal + nightTotal;

            let hours = parseInt(total);

            let minutes = (total - hours) * 60;

            minutes = parseFloat(minutes >= 10 ? '0.' + minutes : '0.0' + minutes);

            hours += minutes;

            this.updateHours(hours.toFixed(2));
        },
        getTimePeriod(periodString){
            let periodsToProcess = periodString;

            // Remove spaces
            periodsToProcess = periodsToProcess.replace(/ /gi, '');
            
            // Split by comma
            periodsToProcess = periodsToProcess.split(',');

            let total = 0.0;

            for (const period of periodsToProcess) {
                let splittedPeriod = period.split('-');

                if(splittedPeriod.length !== 2){
                    continue;
                }

                let someDate = '2020-01-01 ';
                let someDatesTomorrow = '2020-01-02 ';

                let firstPart = new Date(someDate + splittedPeriod[0]);
                let secondPart = new Date( 
                    splittedPeriod[1] == '00:00' ? 
                    someDatesTomorrow + splittedPeriod[1] : 
                    someDate + splittedPeriod[1]
                );

                if(!this.isValidDate(firstPart) || !this.isValidDate(secondPart)){
                    continue;
                }

                total += Math.abs(secondPart - firstPart) / 36e5;
            }

            return total;
        },
        isValidDate(date){
            return date instanceof Date && !isNaN(date);
        }
    },
    computed: {
        requiredRule() {
            return [v => !!v || "Обязательное поле"]
        }
    },
    watch: {
        district(district){
            if(!district) return;

            this.hours = this.district.hours;
            this.dayPeriod = this.district.dayPeriod;
            this.nightPeriod = this.district.nightPeriod;
        }
    }
}
</script>