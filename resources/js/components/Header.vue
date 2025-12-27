<script setup>
import logo from "../../images/island photo logo copy.jpg"; // Update this path to match your actual logo path
</script>

<template>
    <nav class="navbar navbar-expand-lg shadow-sm">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img :src="logo" alt="Island Photo Logo" width="80" class="me-2" />
        </a>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <RouterLink class="nav-link" :class="{ active: $route.path === '/'}" to="/">Home</RouterLink>
          </li>
          <li class="nav-item">
            <RouterLink class="nav-link" :class="{ active: $route.path === '/products'}" to="/products">Products</RouterLink>
            <!-- <a class="nav-link" href="#">Frames</a> -->
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Custom Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
          </li>
        </ul>
      </div>
    </nav>
</template>

<style scoped>
</style>
