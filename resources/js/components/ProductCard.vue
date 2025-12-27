<template>
  <div class="card">
    <img :src="product.image" alt="Product Image">
    <h3>{{ product.name }}</h3>
    <p>{{ product.size }} | {{ product.color }}</p>
    <p>
      <span v-if="product.sale_price" class="line-through text-gray-400">
        ${{ product.price }}
      </span>
      <span class="text-lg font-bold text-green-600">
        ${{ product.sale_price || product.price }}
      </span>
    </p>
    <span v-if="product.is_featured" class="badge">Featured</span>
  </div>
</template>

<script setup>
defineProps(['product'])
</script>
