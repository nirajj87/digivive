
<script setup lang="ts">
	import logo from '@images/logo.svg'
</script>
<template>
    <div>
		<div class="loading">
			<!-- <img :src="logo" class="logo-img" /> -->
			<div class="effect-1 effects"></div>
			<div class="effect-2 effects"></div>
			<div class="effect-3 effects"></div>
		</div>
		<span>{{$t("loading")}} . . .</span>
	</div>
</template>