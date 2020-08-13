<template>
    <v-text-field
        ref="input"
        :label="label"
        type="number"
        step="0.01"
        min="0.00"
        :name="name"
        outlined
        :value="time"
        @input="hoursChanged($event)"
        :rules="rules"
    />
</template>

<script>
export default {
    props: {
        value: null,
        label: '',
        name: '',
        rules: Array
    },
    data(){
        return {
            time: this.value
        }
    },
    methods: {
        hoursChanged(hours){

            let minutes = (hours - parseInt(hours)).toFixed(2);
            
            minutes = parseFloat(minutes);

            if(minutes > 0.59){
                minutes = 0.00;
            }

            this.time = (parseInt(hours) + minutes).toFixed(2);
        }
    },
    watch: {
        time(v){
            this.$emit('input', v);
        },
        value(v) {
            this.time = v;
        }
    }
}
</script>