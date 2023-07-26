<script setup lang="ts">
import VueApexCharts from 'vue3-apexcharts'
import { hexToRgb } from '@layouts/utils'
import { useTheme } from 'vuetify'

const vuetifyTheme = useTheme()
const currentTheme = vuetifyTheme.current.value.colors
const seriescircle = [90]

const chartOptionsCircle = computed(() => {
    const currentTheme = vuetifyTheme.current.value.colors
    const variableTheme = vuetifyTheme.current.value.variables
    return {
        labels: ['Completed Task'],
        chart: {
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                offsetY: 10,
                startAngle: -140,
                endAngle: 130,
                hollow: {
                    size: '65%',
                },
                track: {
                    background: currentTheme.surface,
                    strokeWidth: '100%',
                },
                dataLabels: {
                    name: {
                        offsetY: -20,
                        color: `rgba(${hexToRgb(currentTheme['on-surface'])},${variableTheme['disabled-opacity']})`,
                        fontSize: '14px',
                        fontWeight: '400',
                        fontFamily: 'Public Sans',
                    },
                    value: {
                        offsetY: 10,
                        color: `rgba(${hexToRgb(currentTheme['on-background'])},${variableTheme['high-emphasis-opacity']})`,
                        fontSize: '38px',
                        fontWeight: '600',
                        fontFamily: 'Public Sans',
                    },
                },
            },
        },
        colors: [currentTheme.primary],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                shadeIntensity: 0.5,
                gradientToColors: [currentTheme.primary],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 0.6,
                stops: [30, 70, 100],
            },
        },
        stroke: {
            dashArray: 10,
        },
        grid: {
            padding: {
                top: -20,
                bottom: 5,
            },
        },
        states: {
            hover: {
                filter: {
                    type: 'none',
                },
            },
            active: {
                filter: {
                    type: 'none',
                },
            },
        },
        responsive: [
            {
                breakpoint: 960,
                options: {
                    chart: {
                        height: 280,
                    },
                },
            },
        ],
    }
})

const supportTicket = [
    {
        avatarColor: 'primary',
        avatarIcon: 'tabler-ticket',
        title: 'New Tickets',
        subtitle: '142',
    },
    {
        avatarColor: 'info',
        avatarIcon: 'tabler-circle-check',
        title: 'Open Tickets',
        subtitle: '28',
    },

    {
        avatarColor: 'warning',
        avatarIcon: 'tabler-clock',
        title: 'Response Time',
        subtitle: '1 Day',
    },
]
</script>
<template>
    <section>
        <VCard>
            <VCardItem class="pb-0">
                <VCardTitle>Support Tracker</VCardTitle>
                <VCardSubtitle>Last 7 Days</VCardSubtitle>
            </VCardItem>

            <VCardText>
                <VRow>
                    <VCol cols="12" md="4">
                        <div class="mt-lg-7 mt-lg-2 mb-lg-9 mb-4">
                            <h4 class="text-h4">164</h4>
                            <p class="text-sm mb-0">Total Tickets</p>
                        </div>

                        <VList class="card-list">
                            <VListItem v-for="ticket in supportTicket" :key="ticket.title" :title="ticket.title"
                                :subtitle="ticket.subtitle">
                                <template #prepend>
                                    <VAvatar rounded size="30" :color="ticket.avatarColor" variant="tonal">
                                        <VIcon :icon="ticket.avatarIcon" />
                                    </VAvatar>
                                </template>
                            </VListItem>
                        </VList>
                    </VCol>
                    <VCol cols="12" md="8">
                        <VueApexCharts :options="chartOptionsCircle" :series="seriescircle" height="395" />
                    </VCol>
                </VRow>
            </VCardText>
        </VCard>
    </section>
</template>