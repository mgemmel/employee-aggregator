<template>

  <div class="q-pa-md">
    <q-table
      title="Employees"
      :rows="employees"
      :columns="columns"
      row-key="name"
    >
      <template v-slot:top-right>
        <q-btn
          color="primary"
          icon-right="person_add"
          label="Add employee"
          no-caps
          @click="() => openDialog(false, null)"
        />
      </template>
      <template #body-cell-actions="props">
        <q-td key="actions" :props="props">
          <q-btn
            class="q-ma-xs"
            color="green"
            label="Detail"
            @click="openDialog(true, props.row.id)"
          />
          <q-btn
            class="q-ma-xs"
            color="red"
            icon="delete_forever"
            @click="deleteEmployeeConfirmation(props.row.id)"
          />
        </q-td>
      </template>
    </q-table>
    <q-dialog v-model="addEmployeeDialog" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar class="q-ma-sm" icon="person_add" color="primary" text-color="white"/>
          <span class="q-ma-sm text-h6">{{ isEditDialog ? 'Edit employee' : 'New employee' }}</span>
          <q-btn class="q-ma-sm" icon="close" flat round dense v-close-popup/>
        </q-card-section>
        <q-card-section class="row items-center">
          <q-form
            class="q-gutter-md col"
            @submit="saveUser"
          >
            <template v-for="(attribute, idx) in attributes" :key="idx">
              <q-input v-if="attribute.type === 'string'"
                       v-model="selectedValues[attribute.name]"
                       :label="attribute.name.toUpperCase()"
                       :rules="[val => attribute.required ? (!!val || 'Field is required') : true]"
              />
              <q-input v-if="attribute.type === 'int'"
                       v-model="selectedValues[attribute.name]"
                       type="number"
                       :label="attribute.name.toUpperCase()"
                       :rules="[val => attribute.required ? (!!val || 'Field is required') : true]"
              />
              <template v-if="Array.isArray(attribute.type)">
                <q-radio v-for="(option, idxo) in attribute.type" :key="idxo"
                         v-model="selectedValues[attribute.name]"
                         checked-icon="task_alt"
                         unchecked-icon="panorama_fish_eye"
                         :rules="[val => attribute.required ? (!!val || 'Field is required') : true]"
                         :label="option" :val="option" :value="option"/>
              </template>

            </template>
            <div class="flex flex-center">
              <q-btn
                type="submit"
                label="Save"
                class="q-mt-md"
                color="green"
              />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import {defineComponent, ref, reactive} from 'vue'
import {useQuasar} from 'quasar'
import {api} from 'boot/axios'

export default defineComponent({
  name: "EmployeePage",
  setup() {
    const $q = useQuasar()
    const employees = ref([]);
    const columns = ref([]);
    const attributes = ref([]);
    const selectedId = ref(null);
    const addEmployeeDialog = ref(false);
    const isEditDialog = ref(false);
    const selectedValues = reactive({})

    async function loadEmployees() {
      employees.value = (await api.get('employees')).data
    }

    function openDialog(edit, id) {
      clearSelectedData()
      addEmployeeDialog.value = true
      isEditDialog.value = edit
      if (edit) {
        selectedId.value = id
        employees.value.find((e) => e.id === id)?.attributes.map((attribute) => {
          selectedValues[attribute.type] = attribute.value
        })
      }

    }

    function closeDialog() {
      clearSelectedData()
      selectedId.value = null
      addEmployeeDialog.value = false
    }

    function clearSelectedData() {
      for (const key in selectedValues) {
        selectedValues[key] = ''
      }
    }

    async function saveUser() {
      let requestData = {}
      for (const key in selectedValues) {
        if (selectedValues[key].length) {
          requestData[key] = selectedValues[key]
        }
      }
      let successMsg = 'Employee created.'
      if (isEditDialog.value){
        successMsg = 'Changes saved.'
        await api.put(`employee/update/${selectedId.value}`, requestData)
      }else {
        await api.post('employee', requestData)
      }

      closeDialog()
      $q.notify({
        type: 'positive',
        message: successMsg
      })
      loadEmployees()
    }

    const deleteEmployeeConfirmation = (userId) => {
      $q.dialog({
        title: 'Delete employee',
        message: `Are you sure you want to delete this employee: ${userId}?`,
        ok: 'Yes',
        cancel: 'No'
      }).onOk(async () => {
        await api.delete(`employee/${userId}`)
        loadEmployees()
        $q.notify({
          type: 'positive',
          message: 'Employee successfully deleted.'
        })
      })
    }

    async function loadAttributes() {
      attributes.value = (await api.get('employees/attributes')).data.map((attribute) => {
        return {
          name: attribute.name,
          label: attribute.name.toUpperCase(),
          align: 'center',
          field: row => row.attributes.find((atr) => atr.type === attribute.name)?.value,
          sortable: true,
          type: attribute.type,
          required: attribute.required
        }
      })
      attributes.value.forEach(attribute => {
        selectedValues[attribute.name] = ""
      })
      columns.value = [{
        name: 'id',
        label: 'ID',
        align: 'left',
        field: row => row.id,
        format: val => `${val}`,
        sortable: true
      },
        ...attributes.value,
        {
          name: 'actions',
          label: 'Actions',
          align: 'center',
          field: (row) => row.id
        }
      ];
    }

    loadAttributes()
    loadEmployees()

    return {
      employees,
      columns,
      addEmployeeDialog,
      attributes,
      selectedValues,
      saveUser,
      deleteEmployeeConfirmation,
      openDialog,
      isEditDialog
    }
  }
})
</script>

<style scoped>

</style>
