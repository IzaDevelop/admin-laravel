import './bootstrap';

// importar o jquery
import $ from 'jquery';

// importar a biblioteca summernote
import 'summernote/dist/summernote-lite';
import 'summernote/dist/summernote-lite.css';

// expor o Jquery no escopo global e tornar acessível globalmente
window.$ = window.jQuery = $;

// função para carregar o editor
$(function () {
  $('#summernote').summernote({
    placeholder: 'Hello stand alone ui',
    height: 120,
    minHeight: 50,
    maxHeight: 300,
    focus: true,
    tabsize: 2,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ]
  });
});

// alerta para confirmar a exclusão
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