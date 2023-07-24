<template>
  <q-page class="flex flex-center q-pa-md">
    <q-card class="q-ma-md q-pa-md">
      <q-card-section class="row items-center">
        <span class="q-ml-sm text-h6">Age overview</span>
      </q-card-section>
      <q-card-section class="row items-center">
        <Bar v-if="chartData.loaded" id="my-chart-id" :options="chartOptions" :data="chartData"
        />
      </q-card-section>
    </q-card>
    <q-card class="q-ma-md">
      <q-card-section class="row items-center">
        <span class="q-ml-sm text-h6">Gender overview</span>
      </q-card-section>
      <q-card-section class="row items-center">
        <Pie v-if="genderChartData.loaded" :data="genderChartData" :options="chartOptions"/>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import {defineComponent, reactive} from 'vue'
import {Bar, Pie} from 'vue-chartjs'
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement} from 'chart.js'
import {api} from "boot/axios";

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

export default defineComponent({
  name: 'OverviewPage',
  components: {Bar, Pie},
  setup() {
    const chartData = reactive({
      loaded: false,
      labels: ['0-25', '26-35', '36-45', '46-55', '56+'],
      datasets: [],
    })
    const genderChartData = reactive({
      loaded: false,
      labels: ['Male', 'Female'],
      datasets: [],
    })

    async function loadEmployees() {
      let ageRanges = {25: 0, 35: 0, 45: 0, 55: 0, 150: 0}
      const employees = (await api.get('employees')).data
      let data = []
      let male = 0
      let female = 0
      employees.forEach((employee) => {
        const age = Number(employee.attributes.find((atr) => atr.type === 'age')?.value ?? 0)
        const gender = employee.attributes.find((atr) => atr.type === 'gender')?.value
        if (gender === 'male') male++;
        if (gender === 'female') female++;

        for (const maxAge in ageRanges) {
          if (age <= maxAge) {
            ageRanges[maxAge]++
            return
          }
        }
      })
      for (const count in ageRanges) {
        data.push(ageRanges[count])
      }
      chartData.datasets.push({
        label: 'Employees count',
        data: data,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
      })
      genderChartData.datasets.push({
        backgroundColor: [ '#00D8FF', '#DD1B16'],
        data: [male,female]
      })
      chartData.loaded = true
      genderChartData.loaded = true
    }
    loadEmployees()

    return {
      genderChartData,
      chartData,
      chartOptions: {
        responsive: true,
      }
    }
  }
})
</script>
