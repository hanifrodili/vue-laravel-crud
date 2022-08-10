<template>
  <div>
    <div class="w-full flex justify-between mb-2">
      <BreezeInput type="search" class="mt-1 block" @keyup="searchPatient()" placeholder="Search" v-model="search"/>
      <button class="btn bg-primary hasIcon" @click="registerDialog = true">
        Register
        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
          <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
        </svg>
      </button>
    </div>
    <div class="flex flex-col min-h-[500px] justify-between">
      <table class="table-patient-list striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>I/C Number</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="patient in patients.data" :key="patient.id">
            <td>{{patient.id}}</td>
            <td>{{patient.name}}</td>
            <td>{{patient.ic_no}}</td>
            <td>{{patient.phone_no}}</td>
            <td>{{patient.address}},<br>{{patient.postcode}} {{patient.city}},<br>{{patient.state}}</td>
            <td>
              <div class="flex flex-row justify-center gap-3">
                <button class="btn-icon">
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                      d="M13 19C13 19.34 13.04 19.67 13.09 20H4C2.9 20 2 19.11 2 18V6C2 4.89 2.89 4 4 4H10L12 6H20C21.1 6 22 6.89 22 8V13.81C21.39 13.46 20.72 13.22 20 13.09V8H4V18H13.09C13.04 18.33 13 18.66 13 19M20 18V15H18V18H15V20H18V23H20V20H23V18H20Z" />
                  </svg>
                </button>
                <button class="btn-icon" @click="openEditDialog(patient)">
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path class="bg-primary"
                      d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                  </svg>
                </button>
                <button class="btn-icon" @click="deletePatient(patient.id)">
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path class="bg-danger"
                      d="M9,3V4H4V6H5V19A2,2 0 0,0 7,21H17A2,2 0 0,0 19,19V6H20V4H15V3H9M9,8H11V17H9V8M13,8H15V17H13V8Z" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="flex flex-row w-full">
        <h-pagination :data="paginationData" @change-page="(p) => getPatients(p)"></h-pagination>
      </div>
    </div>
    <!-- Register Dialog -->
    <h-dialog :state="registerDialog">
      <div class="h-dialog-title">
        <div class="flex justify-between">
          <p>Register New</p>
          <div class="cursor-pointer rounded-md hover:bg-gray-200 transition-colors" @click="registerDialog = false">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
            </svg>
          </div>
        </div>
      </div>
      <div class="h-dialog-body">
        <form @submit.prevent="submit">
          <div>
            <BreezeLabel for="name" value="Name" />
            <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="registerForm.name" required
              autofocus />
          </div>
          <div class="mt-4 flex flex-row gap-4">
            <div class="w-full">
              <BreezeLabel for="ic_no" value="I/C Number" />
              <BreezeInput id="ic_no" type="text" min="12" class="mt-1 block w-full" v-model="registerForm.ic_no"
                required />
            </div>

            <div class="w-full">
              <BreezeLabel for="phone_no" value="Phone Number" />
              <BreezeInput id="phone_no" type="tel" class="mt-1 block w-full" v-model="registerForm.phone_no"
                required />
            </div>
          </div>
          <div class="mt-4">
            <BreezeLabel for="address" value="Address" />
            <BreezeInput id="address" type="text" class="mt-1 block w-full" v-model="registerForm.address" required />
          </div>
          <div class="mt-4 flex flex-row gap-4">
            <div class="w-full">
              <BreezeLabel for="postcode" value="Postcode" />
              <BreezeInput id="postcode" type="text" max="5" class="mt-1 block w-full" v-model="registerForm.postcode"
                required />
            </div>
            <div class="w-full">
              <BreezeLabel for="city" value="City" />
              <BreezeInput id="city" type="text" class="mt-1 block w-full" v-model="registerForm.city" required />
            </div>
            <div class="w-full">
              <BreezeLabel for="state" value="State" />
              <BreezeInput id="state" type="text" class="mt-1 block w-full" v-model="registerForm.state" required />
            </div>
          </div>

          <div class="flex items-center justify-end mt-4">
            <button class="btn bg-primary" :disabled="disabled">
              Register
            </button>
          </div>
        </form>
      </div>
    </h-dialog>
    <!-- Edit Dialog -->
    <h-dialog :state="editDialog">
      <div class="h-dialog-title">
        <div class="flex justify-between">
          <p>Edit</p>
          <div class="cursor-pointer rounded-md hover:bg-gray-200 transition-colors" @click="closeEditDialog()">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
            </svg>
          </div>
        </div>
      </div>
      <div class="h-dialog-body">
        <form @submit.prevent="update">
          <div>
            <BreezeLabel for="name" value="Name" />
            <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="editForm.name" required autofocus />
          </div>
          <div class="mt-4 flex flex-row gap-4">
            <div class="w-full">
              <BreezeLabel for="ic_no" value="I/C Number" />
              <BreezeInput id="ic_no" type="text" min="12" class="mt-1 block w-full" v-model="editForm.ic_no"
                required />
            </div>

            <div class="w-full">
              <BreezeLabel for="phone_no" value="Phone Number" />
              <BreezeInput id="phone_no" type="tel" class="mt-1 block w-full" v-model="editForm.phone_no" required />
            </div>
          </div>
          <div class="mt-4">
            <BreezeLabel for="address" value="Address" />
            <BreezeInput id="address" type="text" class="mt-1 block w-full" v-model="editForm.address" required />
          </div>
          <div class="mt-4 flex flex-row gap-4">
            <div class="w-full">
              <BreezeLabel for="postcode" value="Postcode" />
              <BreezeInput id="postcode" type="text" max="5" class="mt-1 block w-full" v-model="editForm.postcode"
                required />
            </div>
            <div class="w-full">
              <BreezeLabel for="city" value="City" />
              <BreezeInput id="city" type="text" class="mt-1 block w-full" v-model="editForm.city" required />
            </div>
            <div class="w-full">
              <BreezeLabel for="state" value="State" />
              <BreezeInput id="state" type="text" class="mt-1 block w-full" v-model="editForm.state" required />
            </div>
          </div>

          <div class="flex items-center justify-end mt-4">
            <button class="btn bg-primary" :disabled="disabled">
              Save
            </button>
          </div>
        </form>
      </div>
    </h-dialog>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import HDialog from '@/Components/HDialog.vue'
  import HPagination from '@/Components/HPagination.vue'
  import BreezeInput from '@/Components/Input.vue';
  import BreezeLabel from '@/Components/Label.vue';
  import { useForm } from '@inertiajs/inertia-vue3';

  onMounted(() => {
    getPatients(1)
  })

  const patients = ref([])
  const patientOnEdit = ref('')
  const paginationData = ref({
    from: 1,
    to: 1,
    total: 1,
    current: 1,
    prev: 1,
    next: 1,
    pages:[],
    last: 1
  })
  const maxVisible = ref(5)
  const startLimit = ref(1)
  const endLimit = ref(maxVisible.value)
  const registerDialog = ref(false)
  const editDialog = ref(false)
  const disabled = ref(false)
  const search = ref('')

  const getPatients = async (p) => {
    if (p) {
      try {
        const resp = await axios.get('/api/patients?page=' + p);
        // console.log(resp.data);
        patients.value = resp.data
        getPagination(resp.data)
        
      } catch (err) {
        // Handle Error Here
        console.error(err);
      }
    }
  }

  const getPageNumber = (urlStr) => {
    if (urlStr) {
      const url = new URL(urlStr);
      const page = url.searchParams.get('page')
      return page
    }
    return false
  }

  const getPagination = (data) => {
    setTimeout(() => {
      paginationData.value.from = data.from
      paginationData.value.to = data.to
      paginationData.value.total = data.total
      paginationData.value.current = data.current_page
      paginationData.value.prev = getPageNumber(data.prev_page_url)
      paginationData.value.next = getPageNumber(data.next_page_url)
      paginationData.value.last = data.last_page
      paginationData.value.pages = []
      if (data.current_page >= endLimit.value) {
        startLimit.value = endLimit.value
        endLimit.value = endLimit.value + maxVisible.value
      }else if (data.current_page <= startLimit.value && startLimit.value >= maxVisible.value) {
        endLimit.value = startLimit.value
        startLimit.value = startLimit.value - (maxVisible.value - 1)
      }
      const links = data.links.slice(1, -1);
      links.forEach((item, index) => {
        if (index >= (startLimit.value - 1) && index <= (endLimit.value - 1)) {
          paginationData.value.pages.push(item)
        }
      })
    }, 100);
  }

  const openEditDialog = (patient) => {
    editDialog.value = true
    patientOnEdit.value = patient.id
    editForm.name = patient.name
    editForm.ic_no = patient.ic_no
    editForm.phone_no = patient.phone_no
    editForm.address = patient.address
    editForm.postcode = patient.postcode
    editForm.city = patient.city
    editForm.state = patient.state
  }
  const closeEditDialog = () => {
    editDialog.value = false
  }
  
  const registerForm = useForm({
    name: '',
    ic_no: '',
    phone_no: '',
    address: '',
    postcode: '',
    city: '',
    state: '',
  });

  const editForm = useForm({
    name: '',
    ic_no: '',
    phone_no: '',
    address: '',
    postcode: '',
    city: '',
    state: '',
  });

  const submit = () => {
    disabled.value = true
    axios.post('/api/patients', registerForm)
    .then((response)=>{
      if (response.status === 200) {
        registerForm.reset()
        registerDialog.value = false
        getPatients(1)
      }
    })
    .catch(error => {
      if(error){
        alert(error.response)
      }
    })
    disabled.value = false
  }

  const update = () => {
    disabled.value = true
    axios.put('/api/patients/' + patientOnEdit.value, editForm)
      .then((response) => {
        // console.log(response);
        if (response.status === 200) {
          editForm.reset()
          editDialog.value = false
          getPatients(1)
        }
      })
      .catch(error => {
        console.log(error);
        if (error) {
          alert(error.response)
        }
      })
    disabled.value = false
  }

  const deletePatient = (id) => {
    let text = "Confirm delete?";
    if (confirm(text) == true) {
      axios.delete('/api/patients/' + id)
        .then(getPatients(1))
        .catch(error => { console.log(error.response) })
    }
  }

  const searchPatient = async () => {
    try {
      const resp = await axios.get('/api/patients?search=' + search.value);
      // console.log(resp.data);
      patients.value = resp.data
      getPagination(resp.data)
    } catch (err) {
      // Handle Error Here
      console.error(err);
    }
  }
</script>