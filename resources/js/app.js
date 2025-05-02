import './bootstrap';

window.confirmDelete = function (id) {
  Swal.fire({
    title: "Tem certeza?",
    text: "Essa ação não pode ser desfeita!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sim, deletar!",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('delete-form-' + id).submit();
      // Swal.fire({
      //   title: "Deletado!",
      //   text: "Usuário deletado com Sucesso.",
      //   icon: "success"
      // });
    }
  });
}