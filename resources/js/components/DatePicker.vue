<template>
    <v-dialog
        ref="dialog"
        v-model="modal"
        :return-value.sync="date"
        persistent
        width="290px"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                v-model="date"
                :label="label"
                :name="name"
                outlined
                readonly
                v-on="on"
                :rules="rules"
            ></v-text-field>
        </template>
        <v-date-picker v-model="date" locale="ru-ru">
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modal = false">Отмена</v-btn>
            <v-btn text color="primary" @click="setDate()"
                >OK</v-btn
            >
        </v-date-picker>
    </v-dialog>
</template>

<script>
export default {
    props: ["value", "label", "name", "rules"],
    data() {
        return {
            modal: false,
            date: this.value
        };
    },
    methods: {
        setDate(){
            this.$refs.dialog.save(this.date);
            this.$emit("input", this.date);
        }
    },
    watch: {
        value(v){
            this.date = v;
        }
    }
};
</script>
