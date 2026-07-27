(function() {
  const savedTheme = localStorage.getItem('proitec-theme') || 'light';
  document.documentElement.setAttribute('data-theme', savedTheme);
})();

document.addEventListener('DOMContentLoaded', () => {
  const themeBtn = document.getElementById('theme-toggle');
  if (!themeBtn) return;

  function updateBtn(theme) {
    themeBtn.innerHTML = theme === 'dark' 
      ? '☀️ <span>Modo Claro</span>' 
      : '🌙 <span>Modo Escuro</span>';
  }

  const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
  updateBtn(currentTheme);

  themeBtn.addEventListener('click', () => {
    const cur = document.documentElement.getAttribute('data-theme') || 'light';
    const next = cur === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('proitec-theme', next);
    updateBtn(next);
  });
});
