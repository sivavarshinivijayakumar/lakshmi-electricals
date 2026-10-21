document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('form[onsubmit]').forEach(form => {
    form.addEventListener('submit', event => {
      const handler = form.getAttribute('onsubmit');
      if (handler && handler.includes('confirm(')) {
        const message = handler.match(/confirm\(['"](.+?)['"]\)/);
        if (message && !window.confirm(message[1])) event.preventDefault();
      }
    });
  });
});
