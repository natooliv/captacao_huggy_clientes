<template>
  <div class="contact-list-container">
    <!-- Seção de busca e botão de adicionar contato -->
    <div class="search-and-add-container">
      <div class="search-input-container">
        <input type="search" class="search-input" placeholder="Buscar contato" v-model="searchQuery">
        <i class="bi bi-search search-icon"></i>
      </div>
      <!-- Botão para acionar o modal de adicionar novo contato -->
      
      <button class="add-contact-button" @click="showAddModal = true">
        + Adicionar contato
      </button>
    </div>
<!-- Aqui começa o novo modal de detalhes do contato -->
<div v-if="selectedContact" class="contact-detail-modal">
  <div class="modal-overlay" @click="closeDetailModal"></div>
  <div class="modal-content">
    <div class="modal-header">
       <div class="user-info">
    <img :src="getPhotoUrl(selectedContact.photo)" alt="Foto do contato" class="contact-photo-img">
    <h2>{{ selectedContact.name }}</h2>
  </div>
  <div class="modal-close-and-actions">
    <i class="fa fa-trash delete-icon-modal" @click.stop="deleteContact(selectedContact.id)"></i>
    <i class="fa fa-pencil-alt edit-icon-modal" @click.stop="openEditModal(selectedContact)"></i>
  </div>
  <div>
    <div class="modal-close" @click="closeDetailModal">&times;</div>
  </div>
</div>
<hr class="detail-line">
<div class="modal-body">
  <p><strong class="modal-label">Email:</strong> {{ selectedContact.email }}</p>
  <p><strong class="modal-label">Endereço:</strong> {{ selectedContact.address }}</p>
  <p><strong class="modal-label">Bairro:</strong> {{ selectedContact.bairro}}</p>
  <p><strong class="modal-label">Estado:</strong> {{ selectedContact.estado }}</p>
  <p><strong class="modal-label">Telefone:</strong> {{ selectedContact.phone }}</p>
  <p><strong class="modal-label">Celular</strong> {{ selectedContact.celular}}</p>
  

</div>
</div>
</div>
    <!-- Tabela de contatos com novo layout -->
    <table v-if="filteredContacts.length > 0" class="contacts-table">
      <thead>
        <tr class="contact-header">
          <th>Nome <i class="fa fa-chevron-down"></i></th>
          <th>Email</th>
          <th class="contact-header-phone">Telefone</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="contact in filteredContacts" :key="contact.id" @click="openDetailModal(contact)" class="contact-row">
          <td class="contact-photo">
            <img :src="getPhotoUrl(contact.photo)" alt="Foto do contato">
          </td>
          <td class="contact-name">{{ contact.name }}</td>
          <td class="contact-email">{{ contact.email }}</td>
          <td class="contact-phone">{{ contact.phone }}</td>
          <td class="contact-actions">
            <i class="fa fa-pencil-alt edit-icon" @click.stop="openEditModal(contact)"></i>
            <i class="fa fa-trash delete-icon" @click.stop="openConfirmDeleteModal(contact)"></i>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="no-contacts">
  <i class="fas fa-book-open no-contacts-icon"></i>
  <p>Ainda não há contatos</p>
  <button class="add-contact-button" @click="showAddModal = true">
    + Adicionar contato
  </button>
</div>
<!-- Modal de confirmação de exclusão -->
<div v-if="showConfirmDeleteModal" class="modal-overlay" @click="closeConfirmDeleteModal">
    <div class="modal-content-delete">
      <h2>Excluir este contato?</h2>
      <div class="modal-actions">
        <button  class= 'delete-buttom' @click="closeConfirmDeleteModal">Cancelar</button>
        <button class="confirme-buttom"  @click="confirmDeletion">Excluir</button>
      </div>
    </div>
  </div>
    <div class="contact-actions">
    <!-- Modal de adicionar contato -->
    
    <add-contact-form v-if="showAddModal" @closeModal="showAddModal = false" @contactAdded="handleContactAdded" />
    <!-- Modal de editar contato -->
    <edit-contact-modal v-if="showModal"
  :contact="editedContact"
  @update="updateContact"
  @close="showModal = false"
/>

    </div>
  </div>
</template>

<script>
import axios from 'axios';
import EditContactModal from './EditContactModal';
import AddContactForm from './AddContactForm';

export default {
  name: 'ContactList',
  components: {
    EditContactModal,
    AddContactForm,
  },
  data() {
    return {
      searchQuery: '',
      contacts: [],
      selectedContact: null,
      showAddModal: false,
      showModal: false,
      editedContact: null,
      showConfirmDeleteModal: false,
      contactToDelete: null,
    };
  },
  computed: {
    filteredContacts() {
      return this.searchQuery.length > 0
        ? this.contacts.filter(contact =>
            contact.name.toLowerCase().includes(this.searchQuery.toLowerCase()))
        : this.contacts;
    },
  },
  methods: {
    fetchContacts() {
      axios.get('http://localhost:8000/api/clientes')
        .then(response => {
          this.contacts = Array.isArray(response.data) ? response.data : [];
        })
        .catch(error => {
          console.error('Erro ao buscar os clientes:', error);
          this.contacts = [];
        });
    },
    openDetailModal(contact) {
      this.selectedContact = contact;
    },
    openEditModal(contact) {
    this.editedContact = { ...contact };
    this.showModal = true; 
  },
    closeDetailModal() {
      this.selectedContact = null;
    },
    updateContact(updatedContact) {
      const index = this.contacts.findIndex(contact => contact.id === updatedContact.id);
      if (index !== -1) {
        this.contacts.splice(index, 1, updatedContact);
      }
      
    },
    getPhotoUrl(photoPath) {
    return 'http://localhost:8000/uploads/' + photoPath;
  },
  openConfirmDeleteModal(contact) {
      this.contactToDelete = contact;
      this.showConfirmDeleteModal = true;
    },

    closeConfirmDeleteModal() {
      this.contactToDelete = null;
      this.showConfirmDeleteModal = false;
    },

    confirmDeletion() {
      if (this.contactToDelete) {
        this.deleteContact(this.contactToDelete.id);
        this.closeConfirmDeleteModal();
      }
    },
    deleteContact(contactId) {
      axios.delete(`http://localhost:8000/api/clientes/${contactId}`)
        .then(response => {
          console.log('Contato excluído com sucesso:', response.data);
          this.contacts = this.contacts.filter(contact => contact.id !== contactId);
          this.closeConfirmDeleteModal();
        })
        .catch(error => {
          console.error('Erro ao excluir contato:', error);
        });
    },
    handleContactAdded(newContact) {
    const existingContactIndex = this.contacts.findIndex(contact => contact.id === newContact.id);
    if (existingContactIndex === -1) {
      this.contacts.push(newContact);
    } else {
      console.log('Contato já existe e não será adicionado novamente.');
    }
    this.showAddModal = false;
  
},
  },
  mounted() {
    this.fetchContacts();
  },
};
</script>

<style scoped>
.search-and-add-container {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}
.modal-body{
  font-family: 'Roboto', sans-serif; 
  line-height: 18px;
  font-weight: 400;
  font-style: normal;
  accent-color:#757575;
  font-size:14px
}
.search-input-container {
  position: relative;
  flex-grow: 1;
  margin-right: 10px;
}
.contacts-table {
  width: 100%;
  margin-top: 20px;
}
.edit-icon-modal{
  color:#505050;
  margin: 0 10px;
   
}
.delete-icon-modal {
  color:#505050;
  margin: 0 10px;
  
}
h2{
  font-family: 'Roboto', sans-serif; 
  line-height: 28px;
  font-weight: 500;
  font-style: normal;
  font: size 20px;
  color:#262626;
}
.detail-line {
  border: none;
  height: 1px;
  background-color: #E1E1E1;
  margin-top: -11px;
  width: 100%;

}
.contact-header {
  display: flex;
  justify-content: space-between;
  padding-bottom: 10px;
  border-bottom: 2px solid #eee;
  font-weight: bold;
}
.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.user-info {
  display: flex;
  align-items: center;
}
.modal-label {
  color: #757575; 
  font-family: 'Roboto', sans-serif; 
  line-height: 16px;
  font-weight: 500;
  font-style: normal;
  font-size:12px
}
.delete-buttom{
  font-family: 'Roboto', sans-serif; 
  font-weight: 500;
  font-style: normal;
  font-size:14px;
  color:#505050;
  background-color: white;
  border:none;
  margin-top: 96px;
  margin-left: 321px;

}
.confirme-buttom{
  font-family: 'Roboto', sans-serif; 
  line-height: 18px;
  font-weight: 500;
  font-style: normal;
  font-size:14px;
  color:#DE321B;
  background-color: white;
  border:none
}
.contact-photo-img {
  width: 50px; 
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px;
}

.modal-close-and-actions {
  
  margin-left: 163px;
}

.edit-icon, .delete-icon {
  margin-left: 10px;
  cursor: pointer;
}

.modal-close {
  margin-left: 10px;
  cursor: pointer;
  font-size: 1.5rem;
}

.contact-body {
  margin-top: 10px;
}
.contact-header-phone {
  margin-left: -292px;
  display: flex;
    margin-right: 472px;

}
.user-photo {
  width: 50px; 
  height: 50px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 15px;
  display: inline-block;
}

.user-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.user-initials {
  background-color: #cccccc; 
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
}

.modal-header h3 {
  display: inline-block;
  margin: 0;
  
  
}

.header-line {
  display: block; 
  height: 2px;
  background-color: #cccccc;
  margin-top: 10px;
}



.contact-row {
  display: flex;
  align-items: center;
  padding: 10px 0;
  
}
.contact-detail-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
}

.modal-overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}
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
.modal-content {
  background: white;
  width: 90%;
  max-width: 500px;
  border-radius: 8px;
  padding: 30px;
  z-index: 11;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.modal-close {
  cursor: pointer;
  font-size: 1.5rem;
}

.user-initials {
  background: #eee;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 10px;
  font-weight: bold;
  color: #333;
}

.modal-footer {
  text-align: right;
  padding-top: 20px;
}

.contact-photo img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px; 
}
.contact-name{
  flex: 1;
    padding: 0 10px;
    display: flex;
}

.contact-email{
  flex: 1;
  padding: 0 10px;
}

.contact-phone {
  flex: 1;
  padding: 0 10px;
}

.contact-actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding-right: 12rem; 
}
.search-input {
  padding: 10px 10px 10px 35px; 
  border: 1px solid #ccc;
  border-radius: 8px;
  display: flex;
  color: #E1E1E1;
}
.contact-detail-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content-delete {
  background: white;
  padding: 20px;
  border-radius: 8px;
  text-align: left;
  width: 475px;
  height: 196px;

}
.search-icon {
  position: absolute;
  left: 10px; 
  top: 50%;
  transform: translateY(-50%);
  color: #9eabb8; 
  pointer-events: none; 
  z-index: 5;
}
.contact-list-container {
  padding: 35px;
}

.search-and-add-container {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}


.add-contact-button {
  background-color: #321BDE;
  color: white;
  padding: 14px 29px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
}

.contacts-table {
  padding: 0px 5px;
  border-collapse: collapse;
}

.contacts-table th, .contacts-table td {
  padding: 10px;
  
}


.edit-icon, .delete-icon {
  color: #646464;
  font-size: 16px;
  margin-left: 0.5rem; 
}



.edit-icon:hover, .delete-icon:hover {
  opacity: 0.8;
}



.no-contacts {
  text-align: center;
  padding: 20px;
}

.no-contacts p {
  margin-top: 16px;
  margin-bottom: 24px;
}

.no-contacts-image {
  max-width: 100px;
  margin-bottom: 8px;
}

</style>

