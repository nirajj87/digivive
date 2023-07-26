<script lang="ts" setup>
const route = useRoute();
    const userId = Number(route.params.id) ?? 0;
</script>

<template>
    <section>
        <h1>Billing Setting</h1>
    </section>
</template>