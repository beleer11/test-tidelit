<template>
  <div class="min-h-screen flex items-center justify-center p-4 bg-gray-100">
    <div class="w-full max-w-6xl bg-white shadow-xl rounded-xl p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">
        📚 Lista de Libros
      </h1>

      <!-- Botón refrescar -->
      <div class="flex justify-end mb-4">
        <button
          @click="fetchBooks"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-300 flex items-center gap-2 cursor-pointer"
          :disabled="loading"
        >
          <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
          {{ loading ? 'Cargando...' : 'Refrescar' }}
        </button>
      </div>

      <!-- Spinner global -->
      <div v-if="loading" class="text-center py-8 text-gray-600">
        <div class="inline-block w-6 h-6 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="mt-2 text-sm">Cargando libros...</p>
      </div>

      <!-- Tabla responsive solo escritorio -->
      <div v-if="!loading" class="hidden sm:block overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-700 bg-white rounded-xl">
          <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs uppercase tracking-wider rounded-t-xl">
            <tr>
              <th scope="col" class="px-6 py-3">Título</th>
              <th scope="col" class="px-6 py-3">Autor</th>
              <th scope="col" class="px-6 py-3">Año</th>
              <th scope="col" class="px-6 py-3">Rating Promedio</th>
              <th scope="col" class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="book in books"
              :key="book.id"
              class="border-b hover:bg-blue-50 transition-colors duration-200"
            >
              <td class="px-6 py-4 font-semibold">{{ book.title }}</td>
              <td class="px-6 py-4">{{ book.author }}</td>
              <td class="px-6 py-4">{{ book.published_year }}</td>
              <td class="px-6 py-4">
                <span
                  class="inline-block px-3 py-1 text-xs font-medium rounded-full"
                  :class="getRatingClass(book.average_rating)">
                  {{ book.average_rating ?? 'Sin reseñas' }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <router-link
                  :to="`/books/${book.id}`"
                  class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-3 py-1 rounded-lg transition duration-300"
                >
                  Ver Reseñas
                </router-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Cards solo móviles -->
      <div v-if="!loading" class="grid gap-4 mt-6 sm:hidden">
        <div
          v-for="book in books"
          :key="'card-' + book.id"
          class="bg-white shadow-lg rounded-xl p-6 border hover:shadow-2xl transition"
        >
          <h2 class="text-lg font-semibold text-gray-800">{{ book.title }}</h2>
          <p class="text-gray-600 text-sm">{{ book.author }}</p>
          <p class="text-gray-500 text-xs">Año: {{ book.published_year }}</p>
          <p
            class="mt-3 inline-block px-3 py-1 text-xs font-medium rounded-full"
            :class="getRatingClass(book.average_rating)">
            {{ book.average_rating ?? 'Sin reseñas' }}
          </p>
          <router-link
            :to="`/books/${book.id}`"
            class="block mt-4 text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-3 py-2 rounded-lg transition duration-300"
          >
            Ver Reseñas
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const books = ref([])
const loading = ref(false)

const fetchBooks = async () => {
  loading.value = true
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_URL}/api/books`)
    books.value = res.data.data
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

const getRatingClass = (rating) => {
  if (!rating) return 'bg-gray-100 text-gray-500'
  if (rating <= 2) return 'bg-red-100 text-red-700'
  if (rating === 3) return 'bg-yellow-100 text-yellow-700'
  return 'bg-green-100 text-green-700'
}

onMounted(fetchBooks)
</script>
