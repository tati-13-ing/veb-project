(function () {
  let currentPostId = null;
  let currentScript = null;

  function xmlEscape(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&apos;');
  }

  function showModal(postId, postTitle) {
    const overlay = document.getElementById('comment-modal-overlay');
    const subtitle = document.getElementById('comment-modal-subtitle');
    const textarea = document.getElementById('comment-message-input');
    const errorNode = document.getElementById('comment-modal-error');

    currentPostId = postId;
    subtitle.textContent = 'Запись: ' + postTitle;
    textarea.value = '';
    errorNode.style.display = 'none';
    errorNode.innerHTML = '';
    overlay.hidden = false;
    textarea.focus();
  }

  function hideModal() {
    document.getElementById('comment-modal-overlay').hidden = true;
    currentPostId = null;
  }

  function showError(text) {
    const errorNode = document.getElementById('comment-modal-error');
    errorNode.innerHTML = '<ul><li>' + text + '</li></ul>';
    errorNode.style.display = 'block';
  }

  function appendComment(postId, author, createdAt, text, commentId) {
    const list = document.getElementById('comments-list-' + postId);
    const emptyNode = document.getElementById('no-comments-' + postId);

    if (emptyNode) emptyNode.remove();

    const item = document.createElement('div');
    item.className = 'comment-item';
    item.id = 'comment-' + commentId;

    item.innerHTML =
      '<div class="comment-item-meta">' +
        '<strong>' + author + '</strong>' +
        '<span>' + createdAt + '</span>' +
      '</div>' +
      '<div class="comment-item-text"></div>';

    item.querySelector('.comment-item-text').textContent = text;
    list.appendChild(item);
  }

  function setStatus(postId, message, ok) {
  const node = document.getElementById('blog-comment-status-' + postId);
  if (!node) return;

  node.textContent = message;
  node.classList.remove('is-ok', 'is-error');
  node.classList.add(ok ? 'is-ok' : 'is-error');

  if (node._hideTimer) {
    clearTimeout(node._hideTimer);
  }

  node._hideTimer = setTimeout(function () {
    node.textContent = '';
    node.classList.remove('is-ok', 'is-error');
  }, 3000);
}

  window.blogCommentCallback = function (xmlString) {
    if (currentScript && currentScript.parentNode) {
      currentScript.parentNode.removeChild(currentScript);
      currentScript = null;
    }

    const parser = new DOMParser();
    const xml = parser.parseFromString(xmlString, 'application/xml');
    const parseError = xml.querySelector('parsererror');

    if (parseError) {
      showError('Сервер вернул некорректный XML.');
      return;
    }

    const status = xml.querySelector('status')?.textContent || 'error';
    const message = xml.querySelector('message')?.textContent || 'Ошибка обработки комментария.';

    if (status !== 'success') {
      showError(message);
      if (currentPostId) setStatus(currentPostId, message, false);
      return;
    }

    const commentNode = xml.querySelector('comment');
    if (commentNode) {
      const postId = commentNode.querySelector('post_id').textContent;
      const commentId = commentNode.querySelector('id').textContent;
      const author = commentNode.querySelector('author').textContent;
      const createdAt = commentNode.querySelector('created_at').textContent;
      const text = commentNode.querySelector('text').textContent;

      appendComment(postId, author, createdAt, text, commentId);
      setStatus(postId, message, true);
    }

    hideModal();
  };

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-comment-btn').forEach(function (button) {
      button.addEventListener('click', function () {
        showModal(button.dataset.postId, button.dataset.postTitle || 'Запись блога');
      });
    });

   
    document.getElementById('comment-modal-cancel')?.addEventListener('click', hideModal);

    document.getElementById('comment-modal-overlay')?.addEventListener('click', function (e) {
      if (e.target === this) hideModal();
    });

    document.getElementById('comment-modal-send')?.addEventListener('click', function () {
      const textarea = document.getElementById('comment-message-input');

      if (!textarea || !currentPostId) return;

      const message = textarea.value.trim();
      if (!message) {
        showError('Введите текст комментария.');
        return;
      }

      const xmlPayload =
        '<comment>' +
          '<post_id>' + xmlEscape(currentPostId) + '</post_id>' +
          '<message>' + xmlEscape(message) + '</message>' +
        '</comment>';

      if (currentScript && currentScript.parentNode) {
        currentScript.parentNode.removeChild(currentScript);
      }

      currentScript = document.createElement('script');
      currentScript.src = '/comment/save?xml=' + encodeURIComponent(xmlPayload) + '&_=' + Date.now();
      document.body.appendChild(currentScript);
    });
  });
})();