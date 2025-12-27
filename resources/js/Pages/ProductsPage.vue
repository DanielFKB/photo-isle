<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import ProductCard from '../components/ProductCard.vue'

// State
const products = ref([])
const selectedSizes = ref([])
const selectedColors = ref([])
const showFeatured = ref(false)
const showOnSale = ref(false)
const showCollage = ref(false)
const isLoading = ref(true)
const hasError = ref(false)
const errorMessage = ref('')
const showFilters = ref(true)

// Constants
const sizes = ['4x6', '5x7', '6x8', '8x10', '8.5x11', '10x12', '11x14', '16x20', '18x24', '20x30', '24x36']
const colors = ['Black', 'White', 'Brown', 'Gold', 'Red', 'Blue', 'Green', 'Silver']

// Fetch products
async function fetchProducts() {
  isLoading.value = true
  hasError.value = false
  
  try {
    const res = await fetch("/api/v1/products")
    
    if (!res.ok) {
      throw new Error(`Failed to fetch products: ${res.status} ${res.statusText}`)
    }
    
    const data = await res.json()
    products.value = data.products || []
  } catch (error) {
    hasError.value = true
    errorMessage.value = error.message || 'Failed to load products. Please try again.'
    console.error('Error fetching products:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchProducts)

// Filtered products
const filteredProducts = computed(() => {
  return products.value.filter(product => {
    const matchSize = selectedSizes.value.length === 0 || selectedSizes.value.includes(product.size)
    const matchColor = selectedColors.value.length === 0 || selectedColors.value.includes(product.color)
    const matchFeatured = !showFeatured.value || product.is_featured === true
    const matchOnSale = !showOnSale.value || (product.sale_price && product.sale_price < product.price)
    const matchCollage = !showCollage.value || product.is_collage === true

    return matchSize && matchColor && matchFeatured && matchOnSale && matchCollage
  })
})

// Watch filters and reset to page 1
watch(
  [selectedSizes, selectedColors, showFeatured, showOnSale, showCollage],
  () => {
    currentPage.value = 1
  }
)

// Pagination
const currentPage = ref(1)
const itemsPerPage = 6
const pageCount = computed(() => Math.ceil(filteredProducts.value.length / itemsPerPage))
const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredProducts.value.slice(start, end)
})

function goToPage(page) {
  if (page >= 1 && page <= pageCount.value) {
    currentPage.value = page
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Computed for smart pagination display
const paginationRange = computed(() => {
  const range = []
  const total = pageCount.value
  const current = currentPage.value
  
  if (total <= 7) {
    for (let i = 1; i <= total; i++) {
      range.push(i)
    }
  } else {
    if (current <= 3) {
      range.push(1, 2, 3, 4, '...', total)
    } else if (current >= total - 2) {
      range.push(1, '...', total - 3, total - 2, total - 1, total)
    } else {
      range.push(1, '...', current - 1, current, current + 1, '...', total)
    }
  }
  
  return range
})

// Clear all filters
function clearFilters() {
  selectedSizes.value = []
  selectedColors.value = []
  showFeatured.value = false
  showOnSale.value = false
  showCollage.value = false
}

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return selectedSizes.value.length > 0 || 
         selectedColors.value.length > 0 || 
         showFeatured.value || 
         showOnSale.value || 
         showCollage.value
})

// Toggle mobile filters
function toggleFilters() {
  showFilters.value = !showFilters.value
}
</script>

<template>
  <div class="products-page">
    <div class="container-fluid">
      <!-- Loading State -->
      <div v-if="isLoading" class="loading-container">
        <div class="loading-content">
          <div class="loader"></div>
          <p class="loading-text">Loading products...</p>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="hasError" class="error-container">
        <div class="error-content">
          <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
          <h2>Unable to Load Products</h2>
          <p>{{ errorMessage }}</p>
          <button @click="fetchProducts" class="retry-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
            </svg>
            Try Again
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div v-else class="row content-wrapper">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 col-md-4 filters-column">
          <div class="filters-sticky">
            <!-- Mobile Filter Toggle -->
            <button class="mobile-filter-toggle d-md-none" @click="toggleFilters">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="4" y1="6" x2="20" y2="6"></line>
                <line x1="4" y1="12" x2="20" y2="12"></line>
                <line x1="4" y1="18" x2="20" y2="18"></line>
              </svg>
              Filters
              <span v-if="hasActiveFilters" class="filter-badge">{{ 
                selectedSizes.length + selectedColors.length + 
                (showFeatured ? 1 : 0) + (showOnSale ? 1 : 0) + (showCollage ? 1 : 0) 
              }}</span>
            </button>

            <!-- Filter Panel -->
            <div class="filters-panel" :class="{ 'show': showFilters }">
              <div class="filters-header">
                <h4>Filters</h4>
                <button 
                  v-if="hasActiveFilters" 
                  @click="clearFilters" 
                  class="clear-filters-btn"
                >
                  Clear All
                </button>
              </div>

              <!-- Size Filter -->
              <div class="filter-section">
                <h5 class="filter-title">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                  </svg>
                  Size
                </h5>
                <div class="filter-options">
                  <label 
                    v-for="size in sizes" 
                    :key="size" 
                    class="filter-checkbox"
                  >
                    <input
                      type="checkbox"
                      :id="`size-${size}`"
                      :value="size"
                      v-model="selectedSizes"
                    />
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">{{ size }}</span>
                  </label>
                </div>
              </div>

              <!-- Color Filter -->
              <div class="filter-section">
                <h5 class="filter-title">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 2v20"/>
                    <path d="M2 12h20"/>
                  </svg>
                  Color
                </h5>
                <div class="filter-options">
                  <label 
                    v-for="color in colors" 
                    :key="color" 
                    class="filter-checkbox"
                  >
                    <input
                      type="checkbox"
                      :id="`color-${color}`"
                      :value="color"
                      v-model="selectedColors"
                    />
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">{{ color }}</span>
                  </label>
                </div>
              </div>

              <!-- Special Filters -->
              <div class="filter-section">
                <h5 class="filter-title">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                  </svg>
                  Special
                </h5>
                <div class="filter-options">
                  <label class="filter-checkbox">
                    <input type="checkbox" v-model="showFeatured" />
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">Featured</span>
                  </label>
                  <label class="filter-checkbox">
                    <input type="checkbox" v-model="showOnSale" />
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">On Sale</span>
                  </label>
                  <label class="filter-checkbox">
                    <input type="checkbox" v-model="showCollage" />
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">Collage</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8 products-column">
          <!-- Header -->
          <div class="products-header">
            <div>
              <h2 class="products-title">Our Products</h2>
              <p v-if="filteredProducts.length > 0" class="products-count">
                Showing {{ paginatedProducts.length }} of {{ filteredProducts.length }} 
                {{ filteredProducts.length === 1 ? 'product' : 'products' }}
              </p>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredProducts.length === 0" class="empty-state">
            <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="11" cy="11" r="8"></circle>
              <path d="m21 21-4.35-4.35"></path>
            </svg>
            <h3>No products found</h3>
            <p>Try adjusting your filters to find what you're looking for.</p>
            <button v-if="hasActiveFilters" @click="clearFilters" class="clear-btn">
              Clear All Filters
            </button>
          </div>

          <!-- Products Grid -->
          <div v-else class="products-grid">
            <div
              v-for="(product, index) in paginatedProducts"
              :key="product.id"
              class="product-card-wrapper"
              :style="{ animationDelay: `${index * 0.05}s` }"
            >
              <div class="product-card">
                <div class="product-image-wrapper">
                  <img
                    :src="product.image"
                    :alt="product.title"
                    class="product-image"
                  />
                  <div class="product-badges">
                    <span v-if="product.is_featured" class="badge badge-featured">Featured</span>
                    <span v-if="product.sale_price && product.sale_price < product.price" class="badge badge-sale">Sale</span>
                    <span v-if="product.is_collage" class="badge badge-collage">Collage</span>
                  </div>
                </div>
                <div class="product-info">
                  <h3 class="product-title">{{ product.title }}</h3>
                  <p class="product-description">{{ product.description }}</p>
                  <div class="product-meta">
                    <span class="product-size">{{ product.size }}</span>
                    <span class="product-color">{{ product.color }}</span>
                  </div>
                  <div class="product-price">
                    <template v-if="product.sale_price && product.sale_price < product.price">
                      <span class="price-original">${{ parseFloat(product.price).toFixed(2) }}</span>
                      <span class="price-sale">${{ parseFloat(product.sale_price).toFixed(2) }}</span>
                    </template>
                    <template v-else>
                      <span class="price-current">${{ parseFloat(product.price).toFixed(2) }}</span>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <nav v-if="pageCount > 1" class="pagination-wrapper">
            <ul class="pagination-list">
              <li>
                <button 
                  class="pagination-btn pagination-prev" 
                  :disabled="currentPage === 1"
                  @click="goToPage(currentPage - 1)"
                  aria-label="Previous page"
                >
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                  </svg>
                  Previous
                </button>
              </li>

              <li v-for="(page, index) in paginationRange" :key="index">
                <button
                  v-if="page !== '...'"
                  class="pagination-btn pagination-number"
                  :class="{ active: page === currentPage }"
                  @click="goToPage(page)"
                >
                  {{ page }}
                </button>
                <span v-else class="pagination-ellipsis">...</span>
              </li>

              <li>
                <button 
                  class="pagination-btn pagination-next" 
                  :disabled="currentPage === pageCount"
                  @click="goToPage(currentPage + 1)"
                  aria-label="Next page"
                >
                  Next
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                  </svg>
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;600;700&display=swap');

/* Base Styles */
.products-page {
  min-height: 100vh;
  position: relative;
  font-family: 'DM Sans', sans-serif;
}

.container-fluid {
  position: relative;
  z-index: 1;
  padding: 2rem 3rem;
  max-width: 1600px;
  margin: 0 auto;
}

/* Loading State */
.loading-container {
  min-height: 70vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.loading-content {
  text-align: center;
}

.loader {
  width: 56px;
  height: 56px;
  border: 4px solid rgba(15, 23, 42, 0.1);
  border-left-color: #0f172a;
  border-radius: 50%;
  animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
  margin: 0 auto 1.5rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-text {
  font-size: 1rem;
  color: #475569;
  font-weight: 500;
  letter-spacing: 0.3px;
}

/* Error State */
.error-container {
  min-height: 70vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.error-content {
  text-align: center;
  max-width: 450px;
  background: white;
  padding: 3rem 2.5rem;
  border-radius: 16px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

.error-icon {
  width: 72px;
  height: 72px;
  color: #dc2626;
  margin: 0 auto 1.5rem;
  stroke-width: 1.5;
}

.error-content h2 {
  font-family: 'Crimson Pro', serif;
  font-size: 1.75rem;
  color: #0f172a;
  margin-bottom: 0.75rem;
  font-weight: 700;
}

.error-content p {
  color: #64748b;
  margin-bottom: 2rem;
  line-height: 1.6;
  font-size: 0.95rem;
}

.retry-btn {
  background: #dc2626;
  color: white;
  border: none;
  padding: 0.875rem 2rem;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.95rem;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.retry-btn svg {
  width: 18px;
  height: 18px;
}

.retry-btn:hover {
  background: #b91c1c;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
}

.retry-btn:active {
  transform: translateY(0);
}

/* Content Wrapper */
.content-wrapper {
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Filters Column */
.filters-column {
  padding-right: 2rem;
}

.filters-sticky {
  position: sticky;
  top: 2rem;
}

.mobile-filter-toggle {
  display: none;
  width: 100%;
  padding: 1rem;
  background: white;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  margin-bottom: 1.5rem;
  align-items: center;
  gap: 0.5rem;
  color: #0f172a;
  transition: all 0.3s ease;
  position: relative;
}

.mobile-filter-toggle svg {
  width: 20px;
  height: 20px;
}

.mobile-filter-toggle:hover {
  border-color: #cbd5e1;
  background: #f8fafc;
}

.filter-badge {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  background: #dc2626;
  color: white;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
}

.filters-panel {
  background: white;
  border-radius: 16px;
  padding: 1.75rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.filters-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.75rem;
  padding-bottom: 1.25rem;
  border-bottom: 2px solid #f1f5f9;
}

.filters-header h4 {
  font-family: 'Crimson Pro', serif;
  font-size: 1.5rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.clear-filters-btn {
  background: none;
  border: none;
  color: #dc2626;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.clear-filters-btn:hover {
  background: #fee2e2;
}

.filter-section {
  margin-bottom: 2rem;
}

.filter-section:last-child {
  margin-bottom: 0;
}

.filter-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.filter-title svg {
  width: 16px;
  height: 16px;
  opacity: 0.6;
}

.filter-options {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.filter-checkbox {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  padding: 0.5rem 0;
  transition: all 0.2s ease;
  position: relative;
}

.filter-checkbox input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #cbd5e1;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.filter-checkbox input[type="checkbox"]:checked + .checkbox-custom {
  background: #0f172a;
  border-color: #0f172a;
}

.filter-checkbox input[type="checkbox"]:checked + .checkbox-custom::after {
  content: '';
  width: 6px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.checkbox-label {
  font-size: 0.9rem;
  color: #475569;
  font-weight: 500;
}

.filter-checkbox:hover .checkbox-custom {
  border-color: #94a3b8;
}

/* Products Column */
.products-column {
  padding-left: 2rem;
}

.products-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.products-title {
  font-family: 'Crimson Pro', serif;
  font-size: 2.5rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.5rem 0;
  letter-spacing: -0.5px;
}

.products-count {
  font-size: 0.95rem;
  color: #64748b;
  margin: 0;
  font-weight: 500;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 5rem 2rem;
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.empty-icon {
  width: 80px;
  height: 80px;
  color: #cbd5e1;
  margin: 0 auto 1.5rem;
  stroke-width: 1.5;
}

.empty-state h3 {
  font-family: 'Crimson Pro', serif;
  font-size: 1.75rem;
  color: #0f172a;
  margin-bottom: 0.75rem;
  font-weight: 700;
}

.empty-state p {
  color: #64748b;
  margin-bottom: 2rem;
  font-size: 1rem;
}

.clear-btn {
  background: #0f172a;
  color: white;
  border: none;
  padding: 0.875rem 2rem;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.95rem;
}

.clear-btn:hover {
  background: #1e293b;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.3);
}

/* Products Grid */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
  margin-bottom: 3rem;
}

.product-card-wrapper {
  animation: slideUp 0.5s ease-out backwards;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.product-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.product-image-wrapper {
  position: relative;
  overflow: hidden;
  aspect-ratio: 4/3;
  background: #f1f5f9;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover .product-image {
  transform: scale(1.08);
}

.product-badges {
  position: absolute;
  top: 1rem;
  right: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  align-items: flex-end;
}

.badge {
  padding: 0.4rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  backdrop-filter: blur(10px);
}

.badge-featured {
  background: rgba(15, 23, 42, 0.9);
  color: white;
}

.badge-sale {
  background: rgba(220, 38, 38, 0.9);
  color: white;
}

.badge-collage {
  background: rgba(59, 130, 246, 0.9);
  color: white;
}

.product-info {
  padding: 1.5rem;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-title {
  font-family: 'Crimson Pro', serif;
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.5rem 0;
  line-height: 1.3;
}

.product-description {
  color: #64748b;
  font-size: 0.9rem;
  line-height: 1.6;
  margin-bottom: 1rem;
  flex: 1;
}

.product-meta {
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.product-size,
.product-color {
  padding: 0.35rem 0.75rem;
  background: #f1f5f9;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  color: #475569;
}

.product-price {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-top: auto;
}

.price-original {
  text-decoration: line-through;
  color: #94a3b8;
  font-size: 1rem;
  font-weight: 500;
}

.price-sale {
  color: #dc2626;
  font-size: 1.5rem;
  font-weight: 700;
  font-family: 'Crimson Pro', serif;
}

.price-current {
  color: #0f172a;
  font-size: 1.5rem;
  font-weight: 700;
  font-family: 'Crimson Pro', serif;
}

/* Pagination */
.pagination-wrapper {
  margin-top: 3rem;
  display: flex;
  justify-content: center;
}

.pagination-list {
  display: flex;
  gap: 0.5rem;
  list-style: none;
  padding: 0;
  margin: 0;
  flex-wrap: wrap;
  justify-content: center;
}

.pagination-btn {
  min-width: 44px;
  height: 44px;
  border: 2px solid #e2e8f0;
  background: white;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  color: #475569;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0 1rem;
  font-size: 0.9rem;
}

.pagination-btn svg {
  width: 16px;
  height: 16px;
}

.pagination-btn:hover:not(:disabled) {
  border-color: #0f172a;
  background: #f8fafc;
  transform: translateY(-2px);
}

.pagination-btn.active {
  background: #0f172a;
  color: white;
  border-color: #0f172a;
}

.pagination-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pagination-ellipsis {
  display: flex;
  align-items: center;
  padding: 0 0.5rem;
  color: #94a3b8;
  font-weight: 600;
}

.pagination-prev,
.pagination-next {
  min-width: auto;
}

/* Responsive Design */
@media (max-width: 991px) {
  .products-column {
    padding-left: 0;
    margin-top: 1.5rem;
  }

  .filters-column {
    padding-right: 0;
  }
}

@media (max-width: 767px) {
  .container-fluid {
    padding: 1.5rem;
  }

  .mobile-filter-toggle {
    display: flex;
  }

  .filters-panel {
    display: none;
    margin-bottom: 1.5rem;
  }

  .filters-panel.show {
    display: block;
    animation: slideDown 0.3s ease-out;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .products-title {
    font-size: 2rem;
  }

  .products-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .pagination-prev span,
  .pagination-next span {
    display: none;
  }

  .pagination-prev,
  .pagination-next {
    min-width: 44px;
    padding: 0;
  }
}

@media (max-width: 480px) {
  .products-title {
    font-size: 1.75rem;
  }

  .product-info {
    padding: 1.25rem;
  }

  .pagination-number {
    min-width: 38px;
    height: 38px;
    font-size: 0.85rem;
  }
}
</style>