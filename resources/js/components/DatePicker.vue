<template>
    <v-menu
        ref="menu"
        v-model="menu"
        :close-on-content-click="false"
        :return-value.sync="date"
        transition="scale-transition"
        offset-y
        min-width="290px"
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
                :dense="dense"
            ></v-text-field>
        </template>
        <v-date-picker 
            :value="date"
            @input="setDate" 
            no-title 
            locale="ru-ru"
        />
    </v-menu>
</template>

<script>
export default {
    props: ["value", "label", "name", "rules", "dense"],
    data() {
        return {
            menu: false,
            date: this.value
        };
    },
    methods: {
        setDate(date) {
            this.date = date;
            this.$refs.menu.save(this.date);
            this.$emit("input", this.date);
        }
    },
    watch: {
        value(v) {
            this.date = v;
        }
    }
};
</script>
