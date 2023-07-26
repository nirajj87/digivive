<script setup lang="ts">
    import websiteAnalytics from './website-analytics.vue';
    import salesOverview from './sales-overview.vue'
    import revenueGenerated from './revenue-generated.vue'
    import earningsReports from './earning-reports.vue'
    import supportTracker from './support-tracker.vue';
    import salesByCountries from './sales-by-countries.vue'
    import totalEarnings from './total-earning.vue'
    import monthlyCampaign from './monthly-campaign.vue'
    import sourceVisits from './source-visits.vue'
    import popularAssest from './popular-assets.vue'
    import transactionsList from './transactions-list.vue'
    import Expenses from './expenses.vue'
    import lastMonthProfit from './last-month-profit.vue'
    import monthlyReport from './monthly-report.vue'
    import Statistics from './statistics.vue'
    import projectsActivity from './projects-activity.vue'
</script>

<template>
    <section>
        <VRow>
            
            <VCol cols="12" md="6">
                <websiteAnalytics />
            </VCol>
            <VCol cols="12" md="3">
                <salesOverview />
            </VCol>
            <VCol cols="12" md="3">
                <Expenses />
            </VCol>
            <VCol cols="12" md="6">
                <earningsReports />
            </VCol>
            <VCol cols="12" md="6">
                <supportTracker />
            </VCol>
            <VCol cols="12" md="3">
                <revenueGenerated />
            </VCol>
            <VCol cols="12" md="5">
                <lastMonthProfit />
            </VCol>
            <VCol cols="12" md="4">
                <monthlyReport />
            </VCol>
            <VCol cols="12">
                <Statistics />
            </VCol>
            <VCol cols="12" md="4">
                <salesByCountries />
            </VCol>
            <VCol cols="12" md="4">
                <totalEarnings />
            </VCol>
            <VCol cols="12" md="4">
                <monthlyCampaign />
            </VCol>
            <!-- <VCol cols="12" md="4">
                <projectsActivity />
            </VCol>
            <VCol cols="12" md="4">
                <sourceVisits />
            </VCol>
            <VCol cols="12" md="4">
                <popularAssest />
            </VCol>
            <VCol cols="12" md="4">
                <transactionsList />
            </VCol> -->
        </VRow>
    </section>
</template>

