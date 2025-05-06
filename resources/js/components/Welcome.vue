<script setup>
import { ref, onMounted } from "vue";

const featuredProducts = ref([])
const loading = ref(true)

onMounted(async () => {
    try {
        const response = await fetch('/api/featured-products')
        const res = await response.json();
        featuredProducts.value = res.data;
    } catch (error) {
        console.error('Error fetching featured products:', error)
        
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <!-- <div class="px-4 py-5 my-5 text-center bg-body-tertiary rounded-3">
        <img class="img-fluid" :src="UWIWordmark" alt="UWI Wordmark" width="300" />
        <h1 class="display-5 fw-bold text-body-emphasis">Welcome</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">You've successfully loaded up the Laravel and VueJS starter template for this assignment.</p>
        </div>
    </div> -->
<div class="container mx-auto p-6">
    <!-- Hero Section -->
    <section class="text-center py-20 bg-gray-100 rounded-xl shadow-lg">
      <h1 class="text-5xl font-bold text-gray-800">Capture & Frame Your Memories</h1>
      <p class="mt-4 text-lg text-gray-600">High-quality photo prints, canvas, and custom frames.</p>
      <RouterLink to="/shop">
        <button class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700">
          Shop Now
        </button>
      </RouterLink>
    </section>

    <!-- Featured Prints -->
    <section class="mt-16">
      <h2 class="text-3xl font-semibold text-gray-800 mb-6">Featured Prints</h2>
      <div v-if="loading" class="text-center">Loading...</div>
      <div v-else class="grid md:grid-cols-3 gap-6">
        <div v-if="featuredProducts.length > 0">
          <div v-for="product in featuredProducts" :key="product.id" class="bg-white p-4 shadow-lg rounded-lg">
            <img :src="product.image" :alt="product.name" class="w-full h-64 object-cover rounded-lg">
            <h3 class="mt-4 text-lg font-semibold">{{ product.name }}</h3>
            <p class="text-gray-600">{{ product.price }} USD</p>
            <RouterLink :to="`/product/${product.id}`">
              <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                View Details
              </button>
            </RouterLink>
          </div>
        </div>
        <p v-else>No featured products available.</p>
      </div>
    </section>

    <!-- How It Works -->
    <section class="mt-16 bg-gray-100 p-10 rounded-xl shadow-lg">
      <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">How It Works</h2>
      <div class="grid md:grid-cols-3 gap-6 text-center">
        <div>
          <h3 class="text-xl font-semibold">1. Upload Your Photo</h3>
          <p class="text-gray-600">Choose a high-quality image for printing.</p>
        </div>
        <div>
          <h3 class="text-xl font-semibold">2. Select Frame & Size</h3>
          <p class="text-gray-600">Pick a frame style and dimensions.</p>
        </div>
        <div>
          <h3 class="text-xl font-semibold">3. Get It Delivered</h3>
          <p class="text-gray-600">Receive your framed photo at your doorstep.</p>
        </div>
      </div>
    </section>
</div>

</template>

<style scoped>

</style>
