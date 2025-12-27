<script setup>
const featuredFrames = [
  {
    title: "Beach Sunset",
    image: "https://images.pexels.com/photos/207353/pexels-photo-207353.jpeg?auto=compress&cs=tinysrgb&w=600",
    description: "A vibrant frame capturing a Caribbean sunset.",
  },
  {
    title: "Rustic Wood",
    image: "https://images.pexels.com/photos/207353/pexels-photo-207353.jpeg?auto=compress&cs=tinysrgb&w=600",
    description: "Natural wood texture, perfect for home decor.",
  },
  {
    title: "Black Minimal",
    image: "https://images.pexels.com/photos/207353/pexels-photo-207353.jpeg?auto=compress&cs=tinysrgb&w=600",
    description: "Sleek and modern frame design.",
  },
];
</script>

<template>
  <div class="demon">
    <!-- Navbar -->
    

    <!-- Hero Section -->
    <section class="py-5 text-center hero">
      <div class="container">
        <h1 class="display-4 fw-bold">Frame Your Favorite Moments</h1>
        <p class="lead">Premium frames, canvas prints, and photo gifts delivered to your door.</p>
        <RouterLink class=" btn btn-dark btn-lg mt-3" :class="{ active: $route.path === '/products'}" to="/products">Browse Frames</RouterLink>
      </div>
    </section>

    <!-- Product Categories -->
    <!-- <section class="py-5">
      <div class="container">
        <div class="row g-4 text-center">
          <div class="col-md-4">
            <div class="card h-100 border-0 shadow">
              <img src="https://via.placeholder.com/300x200?text=Ready-made+Frames" class="card-img-top" />
              <div class="card-body">
                <h5 class="card-title">Ready-made Frames</h5>
                <p class="card-text">Beautiful pre-designed frames for all occasions.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card h-100 border-0 shadow">
              <img src="https://via.placeholder.com/300x200?text=Canvas+Prints" class="card-img-top" />
              <div class="card-body">
                <h5 class="card-title">Canvas Prints</h5>
                <p class="card-text">Turn your favorite photos into wall art.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card h-100 border-0 shadow">
              <img src="https://via.placeholder.com/300x200?text=Custom+Orders" class="card-img-top" />
              <div class="card-body">
                <h5 class="card-title">Custom Frames</h5>
                <p class="card-text">Build a frame to match your unique style and size.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> -->

    <section class="py-5">
      <div class="container">
        <h2 class="text-center mb-4 fw-bold">Featured Frames</h2>
        <div class="row g-4">
          <div class="col-md-4" v-for="(frame, index) in featuredFrames" :key="index">
            <div class="card h-100 shadow-sm border-0">
              <img :src="frame.image" class="card-img-top" :alt="frame.title" />
              <div class="card-body">
                <h5 class="card-title">{{ frame.title }}</h5>
                <p class="card-text">{{ frame.description }}</p>
                <a href="#" class="btn btn-outline-dark">View Frame</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

<section class="py-5 text-center bg-dark text-white">
  <div class="container">
    <h2 class="fw-bold mb-3">Need Something Unique?</h2>
    <p class="lead mb-4">Design your own frame to match your photo, size, and vibe.</p>
    <RouterLink to="/custom-order" class="btn btn-warning btn-lg fw-semibold">
      Get a Custom Frame
    </RouterLink>
  </div>
</section>

    <!-- Footer -->
    <!-- <footer class="py-4 bg-dark text-white text-center">
      <div class="container">
        <p class="mb-0">© 2025 Island Photo. All rights reserved.</p>
      </div>
    </footer> -->
  </div>
</template>

<style scoped>
.hero {
  background-image: url("https://images.pexels.com/photos/4534200/pexels-photo-4534200.jpeg?auto=compress&cs=tinysrgb&w=600");
  background-size: cover;
  background-position: center;
  color: #fff;
}
</style>
