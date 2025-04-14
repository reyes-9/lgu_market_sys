function displayToast(message, type) {
  const iconMap = {
    success: '<i class="bi bi-check-circle-fill"></i>',
    error: '<i class="bi bi-x-circle-fill"></i>',
    warning: '<i class="bi bi-exclamation-triangle-fill"></i>'
  };
  
  const toastHTML = `
    <div class="toast toast-${type} align-items-center border-0 m-2" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          ${iconMap[type] || ''}
          ${message}
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>`;
  
    const container = document.getElementById('toastContainer');
    container.insertAdjacentHTML('beforeend', toastHTML);
  
    const toastElement = container.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
  
    setTimeout(() => {
      toastElement.remove();
    }, 4500);
  }
  