<template>
  <div class="dashboard">
    <contact-list></contact-list> <!-- Componente para listar contatos -->
    <add-contact-form></add-contact-form> <!-- Componente para adicionar contatos -->
  </div>
</template>

<script>
import ContactList from '@/components/ContactList';


export default {
  name: 'DashboardName',
  components: {
    ContactList,
    
    
  
  },
  
}
</script>

