<template>
  <div class="min-h-screen flex items-center justify-center p-4 bg-gray-100">
    <div class="w-full max-w-2xl bg-white shadow-xl rounded-xl p-6">
      <!-- Loader global -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="mt-2 text-gray-600">Cargando datos del libro...</p>
      </div>

      <div v-else>
        <!-- Botón atrás -->
        <div class="mb-4">
          <router-link to="/" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al listado
          </router-link>
        </div>

        <!-- Info del libro -->
        <h1 class="text-2xl font-bold mb-2 text-gray-800">📖 {{ book.title }}</h1>
        <p class="text-gray-600 mb-1">Autor: {{ book.author }}</p>
        <p class="text-gray-600 mb-4">Año: {{ book.published_year }}</p>

        <!-- Reseñas -->
        <h2 class="text-xl font-semibold text-gray-800 mb-3">Reseñas</h2>
        <div v-if="loadingReviews" class="text-center py-6 text-gray-600">
          <div class="inline-block w-6 h-6 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
          <p class="mt-2">Cargando reseñas...</p>
        </div>
        <div v-else>
          <div v-if="reviews.length === 0" class="text-gray-500">Sin reseñas aún</div>

          <!-- Contenedor scrollable -->
          <div v-else
              class="max-h-96 overflow-y-auto space-y-3"> <!-- Aquí agregamos scroll -->
            <div v-for="r in reviews" :key="r.id" 
                :class="['rounded-xl shadow p-4 border', getCardColor(r.rating)]">
              <div class="flex items-center justify-between mb-2">
                <span :class="['font-bold', getRatingColor(r.rating)]">⭐ {{ r.rating }}</span>
                <span class="text-xs text-gray-400">{{ formatDate(r.createdAt) }}</span>
              </div>
              <p class="text-gray-700">{{ r.comment }}</p>
            </div>
          </div>
        </div>
        <!-- Agregar reseña -->
        <h2 class="text-xl font-semibold text-gray-800 mt-6 mb-3">Agregar Reseña</h2>

        <Form :validation-schema="schema" v-slot="{ handleSubmit, resetForm, errors }">
          <div class="space-y-3">
            <!-- Rating -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Rating (1-5)</label>
              <Field name="rating" as="select" :class="getFieldClass('rating', errors)" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
                <option disabled value="">Selecciona un rating</option>
                <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
              </Field>
              <span class="text-red-600 text-sm mt-1">{{ errors.rating }}</span>
            </div>

            <!-- Comentario -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Comentario</label>
              <Field name="comment" as="textarea" rows="3" :class="getFieldClass('comment', errors)" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
              <span class="text-red-600 text-sm mt-1">{{ errors.comment }}</span>
            </div>

            <!-- Botón -->
            <button type="button"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-300 cursor-pointer"
                    :disabled="submitting"
                    @click="handleSubmit(async (values) => await onSubmit(values, resetForm))">
              {{ submitting ? 'Enviando...' : 'Guardar Reseña' }}
            </button>
          </div>
        </Form>
      </div>

      <!-- Modal de error -->
      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm text-center shadow-lg">
          <p class="text-red-600 font-bold">{{ modalMessage }}</p>
          <p class="text-gray-600 mt-2">Serás redirigido al listado...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { Form, Field } from 'vee-validate'
import * as yup from 'yup'

const route = useRoute()
const router = useRouter()
const bookId = route.params.id

const book = ref({ title: '', author: '', published_year: '' })
const reviews = ref([])
const loading = ref(true)
const loadingReviews = ref(false)
const submitting = ref(false)
const showModal = ref(false)
const modalMessage = ref('')

const schema = yup.object({
  rating: yup
    .number()
    .required('Rating es requerido')
    .min(1, 'El rating mínimo es 1')
    .max(5, 'El rating máximo es 5'),
  comment: yup
    .string()
    .required('Comentario es requerido')
    .min(3, 'El comentario debe tener al menos 3 caracteres')
    .max(150, 'El comentario no puede exceder 150 caracteres')
})

const getFieldClass = (fieldName, errors) => {
  if (!errors) return ''
  return errors[fieldName] ? 'border-red-500' : 'border-green-500'
}

const getRatingColor = (rating) => {
  if (rating <= 2) return 'text-red-500'
  if (rating <= 4) return 'text-yellow-500'
  return 'text-green-500'
}

const getCardColor = (rating) => {
  if (rating <= 2) return 'bg-red-50 border-red-200'
  if (rating <= 4) return 'bg-yellow-50 border-yellow-200'
  return 'bg-green-50 border-green-200'
}

const handleError = (message) => {
  modalMessage.value = message
  showModal.value = true
  setTimeout(() => {
    showModal.value = false
    router.push('/')
  }, 3000)
}

const fetchBook = async () => {
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_URL}/api/books/${bookId}`)
    book.value = res.data.data
  } catch (err) {
    handleError('Error al cargar el libro.')
  } finally {
    loading.value = false
  }
}

const fetchReviews = async () => {
  loadingReviews.value = true
  try {
    const res = await axios.get(`${import.meta.env.VITE_API_URL}/api/reviews`, { params: { book_id: bookId } })
    reviews.value = res.data.data
  } catch (err) {
    handleError('Error al cargar las reseñas.')
  } finally {
    loadingReviews.value = false
  }
}

const onSubmit = async (values, resetForm) => {
  submitting.value = true
  try {
    await axios.post(`${import.meta.env.VITE_API_URL}/api/reviews`, {
      book_id: bookId,
      rating: values.rating,
      comment: values.comment
    })
    resetForm()
    await fetchReviews()
  } catch (err) {
    handleError('Error al guardar la reseña.')
  } finally {
    submitting.value = false
  }
}

const formatDate = (dateStr) => {
  const date = new Date(dateStr)
  const d = String(date.getDate()).padStart(2, '0')
  const m = String(date.getMonth() + 1).padStart(2, '0')
  const y = date.getFullYear()
  return `${d}/${m}/${y}`
}

onMounted(async () => {
  await fetchBook()
  await fetchReviews()
})
</script>

<style scoped>
.border-red-500 { border-color: #f87171; }
.border-green-500 { border-color: #34d399; }
</style>
