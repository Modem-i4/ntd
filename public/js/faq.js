/* FAQ.js — мінімальний */
(function () {
  window.renderFAQ = function () {
    const SINGLE_OPEN = false;

    const items = document.querySelectorAll('[data-accordion-button]');
    items.forEach((btn) => {
      btn.addEventListener('click', () => toggle(btn));
    });

    function toggle(btn) {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      if (SINGLE_OPEN && !expanded) {
        document
          .querySelectorAll('[data-accordion-button][aria-expanded="true"]')
          .forEach((otherBtn) => {
            if (otherBtn !== btn) closeItem(otherBtn);
          });
      }
      expanded ? closeItem(btn) : openItem(btn);
    }

    function openItem(b) {
      const p = b.parentElement.querySelector('[data-accordion-panel]');
      const i = b.querySelector('[data-accordion-icon]');
      p.style.maxHeight = p.scrollHeight + 'px';
      p.setAttribute('aria-hidden', 'false');
      b.setAttribute('aria-expanded', 'true');
      i && i.classList.remove('-rotate-90');
      p.addEventListener('transitionend', function tidy(e) {
        if (e.propertyName === 'max-height' && b.getAttribute('aria-expanded') === 'true') {
          p.style.maxHeight = 'none';
          p.removeEventListener('transitionend', tidy);
        }
      });
    }

    function closeItem(b) {
      const p = b.parentElement.querySelector('[data-accordion-panel]');
      const i = b.querySelector('[data-accordion-icon]');
      if (getComputedStyle(p).maxHeight === 'none') {
        p.style.maxHeight = p.scrollHeight + 'px';
        p.getBoundingClientRect();
      }
      p.style.maxHeight = '0px';
      p.setAttribute('aria-hidden', 'true');
      b.setAttribute('aria-expanded', 'false');
      i && i.classList.add('-rotate-90');
    }
  };
  window.renderFAQ();
})();
