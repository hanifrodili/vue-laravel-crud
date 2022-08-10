<template>
  <div class="w-full flex justify-between items-center px-2">
    <div>
      {{data.from}} - {{data.to}} of {{data.total}}
    </div>
    <div class="pagination-container">
      <div class="pagination-block" @click="$emit('changePage',1)" :class="!data.prev ? 'disabled': ''">
        <svg style="width:20px;height:20px" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M18.41,7.41L17,6L11,12L17,18L18.41,16.59L13.83,12L18.41,7.41M12.41,7.41L11,6L5,12L11,18L12.41,16.59L7.83,12L12.41,7.41Z" />
        </svg>
      </div>
      <div class="pagination-block" @click="$emit('changePage',data.prev)" :class="!data.prev ? 'disabled': ''">
        Prev
      </div>
      <div class=" pagination-block" v-for="(link, index) in data.pages" :key="index"
        :class="link.active ? 'active' : ''" @click="$emit('changePage',getPage(link.url, link.active))"
        v-html="link.label">
      </div>
      <div class="pagination-block" @click="$emit('changePage',data.next)" :class="!data.next ? 'disabled': ''">
        Next
      </div>
      <div class="pagination-block" @click="$emit('changePage', data.last)" :class="!data.next ? 'disabled': ''">
        <svg style="width:20px;height:20px" viewBox="0 0 24 24">
          <path fill="currentColor"
            d="M5.59,7.41L7,6L13,12L7,18L5.59,16.59L10.17,12L5.59,7.41M11.59,7.41L13,6L19,12L13,18L11.59,16.59L16.17,12L11.59,7.41Z" />
        </svg>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';


  // defineProps(['data'])
  const props = defineProps({
    data:{
      type: Object,
      default: {
        from: 1,
        to: 1,
        total: 1,
        current: 1,
        prev: 1,
        next: 1,
        pages: [],
        last: 1
      }
    }
  })

  const startLimit = ref(1)
  const endLimit = ref(5)

  const visiblePage = ref([])
  setTimeout(() => {
    const pages = props.data.pages
    const current = props.data.current
    if (current >= endLimit.value) {
      startLimit.value = endLimit.value + 1
      endLimit.value += endLimit.value
    }
    pages.forEach((item, index) => {
      if (index >= startLimit.value - 1 && index <= endLimit.value - 1) {
        visiblePage.value.push(item)
      }
    })
  }, 500);
 
  
  const getPage = (urlStr, isActive) =>{
    if (urlStr && !isActive) {
      const url = new URL(urlStr);
      const page = url.searchParams.get('page')
      return page
    }
    return false
  }
</script>

<style scoped>


  .pagination-container{
    @apply inline-flex flex-row p-1 gap-1
  }
  .pagination-block{
    @apply h-8 min-w-[32px] w-auto rounded-md shadow-md flex items-center justify-center px-3 hover:bg-gray-200 cursor-pointer select-none
  }
  .pagination-block.active {
    @apply bg-indigo-500 text-white shadow-lg
  }
  .pagination-block.disabled {
    @apply bg-gray-300 text-gray-700 opacity-50 cursor-not-allowed
  }
</style>