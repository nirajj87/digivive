<script setup lang="ts">
    import amazonEchoDot from '@images/eCommerce/amazon-echo-dot.png'
    import appleWatch from '@images/eCommerce/apple-watch.png'
    import headphone from '@images/eCommerce/headphone.png'
    import iphone from '@images/eCommerce/iphone.png'
    import nike from '@images/eCommerce/nike.png'
    import sonyDualsense from '@images/eCommerce/sony-dualsense.png'

const popularProducts = [
  {
    avatarImg: iphone,
    title: 'Lagle Pore Charena',
    platform: 'Web',
    views: '999',
  },
  {
    avatarImg: nike,
    title: 'Jangal Pahari',
    platform: 'IOS',
    views: '724',
  },
  {
    avatarImg: headphone,
    title: 'Prem Bandhan',
    platform: 'Web',
    views: '99',
  },
  {
    avatarImg: appleWatch,
    title: 'Amar Mayer Shapath',
    platform: 'Android',
    views: '249',
  },
  {
    avatarImg: amazonEchoDot,
    title: 'Gaandariya- Romantic Duets',
    platform: 'Android',
    views: '7940',
  },
  {
    avatarImg: sonyDualsense,
    title: 'Gandariya- Tin Projanmo',
    platform: 'Web',
    views: '12948',
  },
]
</script>
<template>
    <section>
        <VCard
    title="Popular Products"
    subtitle="Total 10.4k Visitors"
  >


    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="product in popularProducts"
          :key="product.title"
        >
          <template #prepend>
            <VAvatar
              size="44"
              rounded
              :image="product.avatarImg"
            />
          </template>

          <VListItemTitle class="font-weight-medium">
            {{ product.title }}
          </VListItemTitle>
          <VListItemSubtitle>
            Platform : {{ product.platform }}
          </VListItemSubtitle>

          <template #append>
            <div class="d-flex align-center">
              <span class="font-weight-medium text-medium-emphasis me-2">{{ product.views }}</span>
            </div>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
    </section>
</template>