<script setup>
import { ref, onMounted } from 'vue'
import Welcome from "../components/Welcome.vue"

const isLoading = ref(true)
const hasError = ref(false)
const errorMessage = ref('')

onMounted(() => {
  // Simulate component loading
  setTimeout(() => {
    isLoading.value = false
  }, 300)
})

const handleError = (error) => {
  hasError.value = true
  errorMessage.value = error?.message || 'Failed to load content'
  isLoading.value = false
}
</script>

<template>
  <div class="home-container">
    <!-- Loading State -->
    <div v-if="isLoading" class="loading-state">
      <div class="spinner-wrapper">
        <div class="spinner"></div>
        <p class="loading-text">Loading...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="hasError" class="error-state">
      <div class="error-content">
        <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <h2>Something went wrong</h2>
        <p>{{ errorMessage }}</p>
        <button @click="() => window.location.reload()" class="retry-button">
          Try Again
        </button>
      </div>
    </div>

    <!-- Content -->
    <div v-else class="content-wrapper">
      <Welcome @error="handleError" />
    </div>
  </div>
</template>

<style scoped>
.home-container {
  min-height: 100vh;
  position: relative;
}

/* Loading State */
.loading-state {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.spinner-wrapper {
  text-align: center;
}

.spinner {
  width: 48px;
  height: 48px;
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-left-color: #2c3e50;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.loading-text {
  color: #2c3e50;
  font-size: 0.95rem;
  font-weight: 500;
  letter-spacing: 0.5px;
}

/* Error State */
.error-state {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
  padding: 2rem;
}

.error-content {
  text-align: center;
  max-width: 400px;
}

.error-icon {
  width: 64px;
  height: 64px;
  color: #e53e3e;
  margin: 0 auto 1.5rem;
}

.error-content h2 {
  font-size: 1.5rem;
  color: #2d3748;
  margin-bottom: 0.75rem;
  font-weight: 700;
}

.error-content p {
  color: #718096;
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.retry-button {
  background: #e53e3e;
  color: white;
  border: none;
  padding: 0.75rem 2rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.95rem;
}

.retry-button:hover {
  background: #c53030;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
}

.retry-button:active {
  transform: translateY(0);
}

/* Content Wrapper */
.content-wrapper {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>