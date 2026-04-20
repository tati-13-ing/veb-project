document.addEventListener('DOMContentLoaded', function () {
  const overlay = document.getElementById('admin-edit-overlay');
  const closeBtn = document.getElementById('admin-edit-close');
  const cancelBtn = document.getElementById('admin-edit-cancel');
  const saveBtn = document.getElementById('admin-edit-save');
  const idInput = document.getElementById('admin-edit-id');
  const titleInput = document.getElementById('admin-edit-post-title');
  const messageInput = document.getElementById('admin-edit-post-message');
  const errorsNode = document.getElementById('admin-edit-errors');

  if (!overlay || !saveBtn || !idInput || !titleInput || !messageInput || !errorsNode) {
    return;
  }

  function openModal(link) {
    idInput.value = link.dataset.id || '';
    titleInput.value = link.dataset.title || '';
    messageInput.value = link.dataset.message || '';
    errorsNode.innerHTML = '';
    overlay.hidden = false;
    titleInput.focus();
  }

  function closeModal() {
    overlay.hidden = true;
    errorsNode.innerHTML = '';
  }

  document.addEventListener('click', function (event) {
    const trigger = event.target.closest('.js-open-blog-edit');
    if (!trigger) return;

    event.preventDefault();
    openModal(trigger);
  });

  closeBtn?.addEventListener('click', closeModal);
  cancelBtn?.addEventListener('click', closeModal);

  overlay.addEventListener('click', function (event) {
    if (event.target === overlay) {
      closeModal();
    }
  });

  saveBtn.addEventListener('click', function () {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/admin/blog/ajaxupdate', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;

      if (xhr.status === 200) {
        const postId = idInput.value;
        const oldNode = document.querySelector('.admin-post-item[data-post-id="' + postId + '"]');

        if (oldNode) {
          const wrapper = document.createElement('div');
          wrapper.innerHTML = xhr.responseText.trim();
          const newNode = wrapper.firstElementChild;
          if (newNode) {
            oldNode.replaceWith(newNode);
          }
        }

        closeModal();
      } else if (xhr.status === 422 || xhr.status === 404) {
        errorsNode.innerHTML = xhr.responseText;
      } else {
        errorsNode.innerHTML = '<div class="form-errors">Не удалось сохранить изменения.</div>';
      }
    };

    const body =
      'id=' + encodeURIComponent(idInput.value) +
      '&title=' + encodeURIComponent(titleInput.value) +
      '&message=' + encodeURIComponent(messageInput.value);

    xhr.send(body);
  });
});