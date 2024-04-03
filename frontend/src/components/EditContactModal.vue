

<template>
<div class="modal-overlay">
  <div class="edit-contact-modal">
    <h2 class="title">Editar Contato</h2>
    <div>
    <hr class="my-custom-line">
  </div>
    <form @submit.prevent="submitForm" class="edit-contact-form">
      <div class="form-group">
        <div class="photo-container-edit">
        <div class="photo-preview" @click="triggerFileUpload">
          <img v-if="editedContact.photoUrl" :src="editedContact.photoUrl" alt="Foto do contato" class="photo-image">
          <input type="file" id="photo" ref="photoInput" @change="handleFileUpload" hidden>
        </div>
      </div>
        <label for="name">Nome:</label>
        <input type="text" id="name" v-model="editedContact.name" placeholder="Nome completo" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="editedContact.email" placeholder="Email" required>
      </div>

      <div class="form-group">
        <label for="phone">Telefone:</label>
        <input type="tel" id="phone" v-model="editedContact.phone" placeholder="Telefone" required>
      </div>

      <div class="form-group">
        <label for="celular">Celular:</label>
        <input type="tel" id="celular" v-model="editedContact.celular" placeholder="Celular">
      </div>

      <div class="form-group">
        <label for="address">Endereço:</label>
        <input type="text" id="address" v-model="editedContact.address" placeholder="Endereço" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="bairro">Bairro:</label>
          <input type="text" id="bairro" v-model="editedContact.bairro" placeholder="Bairro" required>
        </div>

        <div class="form-group">
          <label for="estado">Estado:</label>
          <input type="text" id="estado" v-model="editedContact.estado" placeholder="Estado" required>
        </div>
      </div>

     
      <div>
    <hr class="my-custom-line-end">
  </div>
      
      <div class="form-actions">
        <button type="button" @click="closeModal">Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    </form>
  </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'EditContactModal',
  props: {
    contact: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      showModal: true,
      editedContact:{},
     
      errorMessage: ''
    };
  },
  mounted() {
    this.initializeEditedContact();
  },
  methods: {
    initializeEditedContact() {
      console.log('Inicializando editedContact com', this.contact);
      
      if (this.contact) {
        this.editedContact = {
          ...this.contact,
          photoUrl: this.contact.photo ? this.getPhotoUrl(this.contact.photo) : null
        };
      }
      console.log('editedContact inicializado:', this.editedContact);
    },
    getPhotoUrl(photoPath) {
  if (!photoPath) return null;

  const tempBasePath = 'http://localhost:8000/uploads/temp/';
  const uploadBasePath = 'http://localhost:8000/uploads/public/uploads/';

  
  if (photoPath.startsWith('temp/')) {
    return tempBasePath + photoPath.slice('temp/'.length);
  }

  if (photoPath.startsWith('public/uploads/')) {
    return uploadBasePath + photoPath.slice('public/uploads/'.length);
  }

 
  return photoPath;
    },


    triggerFileUpload() {
      this.$refs.photoInput.click();
    },
    handleFileUpload(event) {
  const selectedFile = event.target.files[0];
  if (selectedFile) {
    const reader = new FileReader();
    reader.onload = (e) => {
      this.editedContact.photoUrl = e.target.result;
      console.log('photoUrl atualizado:', this.editedContact.photoUrl);
    };
    reader.readAsDataURL(selectedFile);

    const formData = new FormData();
    formData.append('photo', selectedFile);

    axios.post('http://localhost:8000/api/upload-image', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    .then(response => {
      this.editedContact.photo = response.data.temporaryPath;
      this.editedContact.photoUrl = this.getPhotoUrl(response.data.temporaryPath); 
      console.log('photoUrl após upload:', this.editedContact.photoUrl);
    })
    .catch(error => {
      console.error('Erro ao fazer upload da imagem:', error);
      this.errorMessage = 'Erro ao fazer upload da imagem.';
    });
  }
},
closeModal() {
    this.showModal = false;
    this.$emit('close'); 
  },
submitForm() {
      console.log('Formulário submetido!');
      this.errorMessage = '';

      if (!this.editedContact.name.trim() || !this.editedContact.email.trim() || !this.editedContact.phone.trim() || !this.editedContact.address.trim()) {
        this.errorMessage = 'Por favor, preencha todos os campos obrigatórios.';
        return;
      }

      axios.put(`http://localhost:8000/api/clientes/${this.contact.id}`, this.editedContact)
        .then(response => {
          console.log('Contato editado com sucesso:', response.data);
          this.showModal = false;
          this.$emit('update', response.data);
          this.closeModal()
        })
        .catch(error => {
          this.errorMessage = 'Erro ao editar contato.';
          console.error('Erro ao editar contato:', error);
        });
    },
  }
};

</script>
<style scope>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center; 
  justify-content: center; 
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 10; 
}
.edit-contact-modal{
  background-color: #FFF;
  padding: 42px 14px; 
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 550px;
  height: 630px;
  display: flex;
  flex-direction: column;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

}
.edit-contact-form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 1rem;
  
}s
.form-top {
  display: flex;
  align-items: center; 
}
.form-group label {
  font-weight: bold;
  margin-bottom: .5rem;
}
.my-custom-line-end {
  height: 1px;
  background-color: #e1e1e1ad;
  margin: 0;
  width: 100%;
  margin-top: 10px;
}
.my-custom-line {
  height: 1px;
  background-color: #e1e1e1ad;
  margin: 0;
  width: 100%;
  margin-top: 10px;
}
.photo-upload-btn i {
  font-size: 2rem; 
}
.photo-container-edit {
  margin-top: 20px; 
  margin-right: 4rem; 
  margin-bottom: -102px; 
  display: flex;
  flex-direction: row-reverse;
  justify-content: space-between;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 0.5rem;
}
.form-row-photo {
  display: flex;
  gap: 1rem;
  flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-end;
    justify-content: center;
    margin-right: -104px;

}
.photo-preview, .photo-upload-btn {
  width: 100px; 
  height: 100px; 
  border-radius: 50%; 
  background-color: #f2f2f2; 
  align-items: center;
  justify-content: center;
  overflow: hidden; 
  border: 2px solid #ddd; 
}
.photo-upload-btn i {
  font-size: 2rem; 
}

.form-row {
  display: flex;
  gap: 1rem;
}

.form-actions {
 
 margin-top: 2rem;
 margin-left: 342px;
}
.form-actions button {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: -15px
}
.my-custom-line-end {
  height: 1px;
  background-color: #e1e1e1ad;
  border: none;
  margin: 0;
  width: 100%;
  margin-top: 10px;
}
.photo-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}
.form-actions button[type="submit"] {
  background-color:#321BDE;
  color: white;

  padding: 0px 17px;
  font-family: 'Roboto', sans-serif; 
  font-weight: 400;
  font-style: normal;
  border-radius:26px;
}

.form-actions button[type="button"] {
  background-color: #FFFFFF;
  color: #505050;
  font-size: 14px;
  font-family: 'Roboto', sans-serif;
  font-family: 'Roboto', sans-serif; 
  line-height: 32px;
  font-weight: 400;
  font-style: normal;
}


.form-actions button:hover {
  opacity: 0.8;
}

</style>