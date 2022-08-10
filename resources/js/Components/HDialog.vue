<template>
  <div>
    <div class="dialog-container" v-if="state">
      <div class="dialog">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
  defineProps(['state'])
</script>

<style scoped>
  .dialog-container{
    @apply absolute inset-0 bg-zinc-700 backdrop-blur-sm bg-opacity-70 flex justify-center
  }
  .dialog{
    @apply w-full max-w-3xl min-h-[200px] bg-white rounded-xl p-0 absolute top-40
  }
</style>