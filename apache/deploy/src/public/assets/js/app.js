// assets/js/app.js
document.addEventListener('DOMContentLoaded', function(){
  // delete confirm (admin buttons)
  document.querySelectorAll('.btn-delete').forEach(btn=>{
    btn.addEventListener('click', function(e){
      if(!confirm('Yakin ingin menghapus?')) e.preventDefault();
    });
  });

  // simple client-side search enter behaviour
  var searchForm = document.getElementById('searchForm');
  if (searchForm) {
    searchForm.addEventListener('submit', function(){});
  }
});
