<template>
<div class="modal-overlay">
  <div class="add-contact-modal">
    <h2 class="title">Adicionar novo contato</h2>
    <div>
    <hr class="my-custom-line">
  </div>
    <form @submit.prevent="submitForm" class="add-contact-form">
      <div>
        <div>
          <div class="form-row-photo">
  <div class="form-group">
    <div class="form-group">
  <div class="photo-container">
    <!-- Área de pré-visualização da foto ou iniciais -->
    <div v-if="photoPreview" class="photo-preview" :style="{ backgroundImage: 'url(' + photoPreview + ')' }"></div>
    <!-- Botão de upload da foto -->
    <div v-else class="photo-upload-btn" @click="triggerFileUpload">
      <i class="fa fa-camera"></i>
      <input type="file" id="photo" ref="photoInput" @change="handleFileUpload" accept="image/*" hidden>
    </div>
  </div>
  </div>
  </div>
  
      </div>
          <div>
        <div class="form-group">
          <label for="name">Nome</label>
          <input type="text" id="name" v-model="formData.name" placeholder="Nome completo" required>
        </div>
      

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="formData.email" placeholder="Email" required>
      </div>
    
      <div class="form-group">
        <label for="phone">Telefone</label>
        <input type="tel" id="phone" v-model="formData.phone" placeholder="Telefone" required>
      </div>

      <div class="form-group">
        <label for="celular">Celular</label>
        <input type="tel" id="celular" v-model="formData.celular" placeholder="Celular">
      </div>

      <div class="form-group">
        <label for="address">Endereço</label>
        <input type="text" id="address" v-model="formData.address" placeholder="Endereço">
      </div>
    
      <div class="form-row">
        <div class="form-group">
          <label for="bairro">Bairro</label>
          <input type="text" id="bairro" v-model="formData.bairro" placeholder="Bairro">
        </div>

        <div class="form-group">
          <label for="estado">Estado</label>
          <input type="text" id="estado" v-model="formData.estado" placeholder="Estado">
        </div>
      </div>
      </div>
    </div>
</div>
<div>
    <hr class="my-custom-line-end">
  </div>

      <div class="form-actions">
        <button type="button" @click="resetForm">Cancelar</button>
        <button type="submit">Salvar</button>
      </div>
    
    </form>
  </div>
</div>
</template>
<script>
import axios from 'axios';

export default {
  name: 'AddContactForm',
  data() {
    return {
      showModal: false,
      photoPreview: '',
      formData: {
        name: '',
        email: '',
        phone: '',
        celular: '',
        address: '',
        bairro: '',
        estado: '',
        photo: null
      },
    };
  },
  methods: {
    async submitForm() {
      const fileData = new FormData();
      if (this.formData.photo instanceof File) {
        fileData.append('photo', this.formData.photo);
      } else if (!this.formData.photo && this.formData.name) {
      
        const initialsImage = await this.generateInitials();
        fileData.append('photo', initialsImage);
      }

      
      Object.keys(this.formData).forEach(key => {
        if (key !== 'photo') {
          fileData.append(key, this.formData[key]);
        }
      });

      try {
        const response = await axios.post('http://localhost:8000/api/clientes', fileData);
        console.log('Resposta da API:', response.data);
        this.resetForm();
        this.$emit('contactAdded', response.data);
      } catch (error) {
        console.error('Erro ao adicionar contato:', error);
      }
    },
    triggerFileUpload() {
      this.$refs.photoInput.click();
    },
    handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    this.formData.photo = file;
    const reader = new FileReader();
    reader.onload = e => {
      this.photoPreview = e.target.result; 
    };
    reader.readAsDataURL(file);
  }
},

    resetForm() {
      this.formData = {
        name: '',
        email: '',
        celular: '',
        bairro: '',
        estado: '',
        phone: '',
        address: '',
        photo: null,
      };
      this.photoPreview = '';
      this.showModal = false;
      this.$emit('closeModal'); 
    
    },
    generateInitials() {
      return new Promise((resolve, reject) => {
        const canvas = document.createElement('canvas');
        canvas.width = 100;
        canvas.height = 100;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#f2f2f2';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#333';
        ctx.font = 'bold 24px Arial';
        const initials = this.formData.name.split(' ').map(word => word[0]?.toUpperCase()).join('');
        ctx.fillText(initials, 35, 60);
        canvas.toBlob(blob => {
          if (blob) {
            const file = new File([blob], "initials.png", { type: "image/png" });
            resolve(file);
          } else {
            reject(new Error('Erro ao gerar a imagem de iniciais'));
          }
        }, 'image/png');
      });
    },
    getPhotoStyle() {
      return this.photoPreview ? {
        backgroundImage: `url(${this.photoPreview})`,
        backgroundSize: 'cover',
        borderRadius: '50%',
        width: '100px',
        height: '100px'
      } : { display: 'none' };
    }
  }
};
</script>
<style>

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
.title{
  display:flex
}

.add-contact-modal {
  background-color: #FFF;
  padding: 42px 14px; 
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 555px;
  height: 714px;
  display: flex;
  flex-direction: column;
  box-sizing: border-box; 
  position: fixed; 
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.form-top {
  display: flex;
  align-items: center; 
}

.photo-container {
  margin-top: 20px; 
  margin-right: -13rem; 
  margin-bottom: -102px; 
  display: flex;
  flex-direction: row-reverse;
  justify-content: space-between;
}



.photo-preview, .photo-upload-btn {
  width: 100px; 
  height: 100px; 
  border-radius: 50%; 
  background-color: #f2f2f2; 
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden; 
  border: 2px solid #ddd; 
}

.photo-upload-btn i {
  font-size: 2rem; 
}
.add-contact-form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  font-weight: bold;
  margin-bottom: .5rem;
  display:flex;
  font-size: 12px;
}
#name {
  width: 45%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 0.5rem;
  display:flex;

}
#email {
  width: 45%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 0.5rem;
  display:flex;

}
#phone {
  width: 35%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 08px;
  margin-bottom: 0.5rem;
  display:flex;

}
#celular {
  width: 35%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 10px;
  margin-bottom: 0.5rem;
  display:flex;

}
#address {
  width: 65%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 0.5rem;
  display:flex;

}
#bairro {
  width: 45%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-bottom: 0.5rem;
  display:flex;

}
.my-custom-line {
  height: 1px;
  background-color: #e1e1e1ad; 
  border: none;
  margin: 0;
  width: 100%;
}
.my-custom-line-end {
  height: 1px;
  background-color: #e1e1e1ad; 
  border: none;
  margin: 0;
  width: 100%;
  margin-top: 10px;
}


#estado {
  width: 35%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 10px;
  margin-bottom: 0.5rem;
  display:flex;

}


.form-row {
  display: flex;
  gap: 1rem;
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
.form-row .form-group {
  flex: 1;
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

.form-actions button[type="submit"] {
  background-color:#321BDE;
  color: white;

  padding: 0px 17px;
  font-family: 'Roboto', sans-serif; 
  line-height: 32px;
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