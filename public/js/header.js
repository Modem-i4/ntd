(() => {
  const $ = id => document.getElementById(id);
  const header = $('site-header');
  const headerBar = header.firstElementChild;
  const toggle = $('nav-toggle');
  const menu   = $('mobile-menu');
  const b1 = $('bar1'), b2 = $('bar2'), b3 = $('bar3');

  let isOpen = false;

  const setHeaderVar = () => {
    if (!headerBar) return;
    document.documentElement.style.setProperty('--header-h', headerBar.offsetHeight + 'px');
  };

  const applyScroll = () => {
    const s = window.scrollY > 8 || isOpen;
    const classes = [ 'scrolled', 'bg-[#689296]/80', 'backdrop-blur-md', 'shadow-lg' ];
    classes.forEach(c => header.classList.toggle(c, s));
    setHeaderVar();
  };

  const setBurger = open => {
    if (!b1 || !b2 || !b3) return;
    if (open) {
      b1.style.transform = 'translateY(0) rotate(45deg)';
      b2.style.opacity   = '0';
      b2.style.transform = 'translateY(0)';
      b3.style.transform = 'translateY(0) rotate(-45deg)';
    } else {
      b1.style.transform = 'translateY(-6px) rotate(0deg)';
      b2.style.opacity   = '1';
      b2.style.transform = 'translateY(0) rotate(0deg)';
      b3.style.transform = 'translateY(6px) rotate(0deg)';
    }
  };

  const setMenu = open => {
    isOpen = open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Закрити меню' : 'Відкрити меню');
    menu.style.maxHeight = open ? menu.scrollHeight + 'px' : '0px';
    menu.style.opacity = open ? '1' : '0';
    setBurger(open);
    setHeaderVar();
    applyScroll(); // ✅ щоб одразу перемикати фон
  };

  const rafScroll = () => {
    let ticking = false;
    return () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => { applyScroll(); ticking = false; });
    };
  };

  const onOutside = e => {
    if (!isOpen) return;
    if (!toggle.contains(e.target) && !menu.contains(e.target)) setMenu(false);
  };

  const onKey = e => {
    if (e.key === 'Escape' && isOpen) setMenu(false);
  };

  applyScroll();
  setHeaderVar();
  setBurger(false);

  window.addEventListener('scroll', rafScroll(), { passive: true });
  window.addEventListener('resize', setHeaderVar, { passive: true });
  document.addEventListener('pointerdown', onOutside, true);
  document.addEventListener('keydown', onKey);

  toggle?.addEventListener('click', () => setMenu(!isOpen));
  menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));
})();
