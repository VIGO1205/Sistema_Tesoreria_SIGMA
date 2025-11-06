<div class="top-4 alert-message duration-1000" style="">
    @include($route)
</div>

<script>
  const alert = document.querySelector('.alert-message');
  
  // Mostrar alerta
  setTimeout(() => {
    alert.classList.remove('opacity-0', 'translate-y-2');
    alert.classList.add('opacity-100', 'translate-y-0');
    // Ocultar después de 2 segundos
    setTimeout(() => {
      alert.classList.remove('opacity-100', 'translate-y-0');
      alert.classList.add('opacity-0', '-translate-y-5');
    }, 2000);
    setTimeout(() => {
        alert.classList.add("hidden");
    }, 3000);
  }, 100);
  
</script>