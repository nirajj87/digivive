<script setup lang="ts">
const transitions = [
    {
        avatarIcon: 'tabler-device-desktop',
        avatarColor: 'primary',
        user: 'User 1',
        paymentmode: 'Web',
        price: '75',
    },
    {
        avatarIcon: 'tabler-device-mobile',
        avatarColor: 'success',
        user: 'User 2',
        paymentmode: 'Android',
        price: '480',
    },
    {
        avatarIcon: 'tabler-device-mobile',
        avatarColor: 'success',
        user: 'User 3',
        paymentmode: 'Android',
        price: '268',
    },
    {
        avatarIcon: 'tabler-device-mobile',
        avatarColor: 'secondary',
        user: 'User 4',
        paymentmode: 'IOS',
        price: '699',
    },
    {
        avatarIcon: 'tabler-device-desktop',
        avatarColor: 'primary',
        user: 'User 5',
        paymentmode: 'Web',
        price: '98',
    },
    {
        avatarIcon: 'tabler-device-desktop',
        avatarColor: 'primary',
        user: 'User 6',
        paymentmode: 'Web',
        price: '126',
    },
    {
        avatarIcon: 'tabler-device-mobile',
        avatarColor: 'secondry',
        user: 'User 7',
        paymentmode: 'IOS',
        price: '1290',
    },
]
</script>
<template>
    <section>
        <VCard title="Transactions">
            <VCardText>
                <VList class="card-list">
                    <VListItem v-for="transition in transitions" :key="transition.user">
                        <template #prepend>
                            <VAvatar size="34" :color="transition.avatarColor" variant="tonal" rounded>
                                <VIcon :icon="transition.avatarIcon" />
                            </VAvatar>
                        </template>

                        <VListItemTitle class="font-weight-semibold">
                            {{ transition.user }}
                        </VListItemTitle>
                        <VListItemSubtitle class="opacity-100 text-disabled">
                            {{ transition.paymentmode }}
                        </VListItemSubtitle>

                        <template #append>
                            <div class="d-flex align-center">
                               <VIcon icon="tabler-currency-rupee" size="16" /> {{transition.price }}
                            </div>
                        </template>
                    </VListItem>
                </VList>
            </VCardText>
        </VCard>
    </section>
</template>