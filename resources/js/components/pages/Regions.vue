<template>
    <v-row justify="center">
        <v-col cols="3">
            <div>Regions</div>
            <ul>
                <li v-for="(region, i) in regions" :key="i">
                    <a
                        :href="`/regions?parent_id=${region.id}`"
                        v-if="region.subregions.length"
                        >{{ region.name }}</a
                    >
                    <span v-else>{{ region.name }}</span>
                </li>
            </ul>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ["regions"],
    mounted() {
        console.log(this.regions);
    }
};
</script>
