<?php
$scenarios_json = htmlspecialchars(json_encode($scenarios, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
$img_base = rtrim($IMG_BASE ?? '/assets/scenarios/', '/') . '/';
?>

<section x-data='scList(<?= $scenarios_json ?>, <?= json_encode($img_base) ?>)'
         x-init="init()"
         class="mx-auto max-w-6xl">

  <div x-ref="acc" class="overflow-hidden px-4 py-3 transition-[max-height] duration-[360ms] ease-[cubic-bezier(.22,.61,.36,1)]">
    <div class="sc-list grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php foreach ($scenarios as $i => $s): ?>
       <button type="button"
            @click="select(<?= $i ?>)"
            :class="tileClass(<?= $i ?>)"
            class="sc-item group rounded-lg border border-gray-200 bg-white overflow-hidden text-left ring-1 ring-black/10 cursor-pointer
                transform transition duration-200 ease-out hover:-translate-y-0.5 hover:shadow-lg hover:ring-2 hover:ring-orange-300">
          <div class="relative h-40 w-full bg-gray-100 overflow-hidden">
            <img src="<?= !empty($s['img_placeholder']) ? '/assets/wip.webp' : ($IMG_BASE . $s['slug'] . '.webp'); ?>"
                 alt="<?= htmlspecialchars($s['title']) ?>"
                 class="absolute inset-0 w-full h-full object-cover block"
                 style="object-position: <?= $s['feats']['img-offset'] ?? 'center' ?>"
                 />
            <?php if (!empty($s['tags']) && is_array($s['tags'])): ?>
              <div class="absolute bottom-2 right-2 flex flex-wrap gap-1 justify-end">
                <?php foreach ($s['tags'] as $tg): ?>
                  <span class="px-2 py-1 rounded-full text-sm leading-4 font-semibold text-white bg-[#597f85]">
                    <?= htmlspecialchars($tag_titles[(string)$tg]) ?>
                  </span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="p-3">
            <div class="font-semibold leading-snug min-h-[44px]"><?= htmlspecialchars($s['title']) ?></div>
            <?php if (!empty($s['topic'])): ?>
              <div class="text-sm text-gray-600 mt-1 min-h-[80px] flex">
                <div class="my-auto"><?= htmlspecialchars($s['topic']) ?></div>
              </div>
            <?php endif; ?>
          </div>
        </button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="mt-3 w-full">
    <button type="button"
            @click="toggle()"
            :aria-expanded="open ? 'true' : 'false'"
            class="mx-auto rounded-md text-sm font-semibold py-2 px-4 block bg-orange-100 hover:bg-orange-200 transition">
      <span x-show="!open" x-cloak>Переглянути всі</span>
      <span x-show="open" x-cloak>Згорнути</span>
    </button>
  </div>
</section>

<script>
function scList(scenarios, imgBase) {
  return {
    scenarios,
    imgBase,
    activeIdx: 0,
    open: false,
    _sw: null,
    init() {
      const attach = () => {
        if (window.__scSwiper && !this._sw) {
          this._sw = window.__scSwiper;
          this.activeIdx = Number(this._sw.realIndex || 0);
          this._sw.on('realIndexChange', (s) => { this.activeIdx = s.realIndex; });
        }
      };
      attach();
      if (!this._sw) {
        const t = setInterval(() => { attach(); if (this._sw) clearInterval(t); }, 100);
        setTimeout(() => clearInterval(t), 5000);
      }
      document.addEventListener('scenario:active', (e) => {
        if (e?.detail && typeof e.detail.idx === 'number') this.activeIdx = e.detail.idx;
      });
      const setCollapsed = () => {
        if (!this.open && this.$refs.acc) {
          this.$refs.acc.style.maxHeight = this._computeCollapsedHeight() + 'px';
        }
      };
      window.addEventListener('resize', setCollapsed);
      if (document.readyState === 'complete') setCollapsed(); else window.addEventListener('load', setCollapsed);
      setCollapsed();
    },
    toggle() {
      const acc = this.$refs.acc;
      if (!acc) { this.open = !this.open; return; }
      if (this.open) {
        const from = acc.scrollHeight;
        acc.style.maxHeight = from + 'px';
        requestAnimationFrame(() => { acc.style.maxHeight = this._computeCollapsedHeight() + 'px'; });
        const onEnd = () => { this.open = false; acc.removeEventListener('transitionend', onEnd); };
        acc.addEventListener('transitionend', onEnd, { once: true });
      } else {
        const from = acc.getBoundingClientRect().height || this._computeCollapsedHeight();
        acc.style.maxHeight = from + 'px';
        this.open = true;
        requestAnimationFrame(() => { acc.style.maxHeight = acc.scrollHeight + 'px'; });
        const onEnd = () => { acc.style.maxHeight = ''; acc.removeEventListener('transitionend', onEnd); };
        acc.addEventListener('transitionend', onEnd, { once: true });
      }
    },
    _collapsedRows() {
      return window.matchMedia('(min-width:640px)').matches ? 1 : 2;
    },
    _computeCollapsedHeight() {
      const acc = this.$refs.acc;
      if (!acc) return 0;
      const grid = acc.querySelector('.sc-list');
      if (!grid) return 0;
      const cs = getComputedStyle(acc);
      const padY = (parseFloat(cs.paddingTop) || 0) + (parseFloat(cs.paddingBottom) || 0);
      const items = Array.from(grid.children);
      if (!items.length) return Math.ceil(padY);
      const rowTops = [];
      for (const el of items) {
        const top = el.offsetTop;
        if (!rowTops.includes(top)) rowTops.push(top);
      }
      const rows = Math.min(this._collapsedRows(), rowTops.length);
      const firstTop = rowTops[0];
      const targetTop = rowTops[rows - 1];
      let bottom = 0;
      for (const el of items) {
        if (el.offsetTop === targetTop) {
          bottom = Math.max(bottom, el.offsetTop + el.offsetHeight);
        }
      }
      const height = Math.max(0, bottom - firstTop);
      return Math.ceil(height + padY);
    },
    _scrollToSliderSection() {
      const el = document.getElementById('slider-section');
      if (el && typeof el.scrollIntoView === 'function') {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    },
    select(idx) {
      this.activeIdx = idx;
      if (window.__scSwiper) window.__scSwiper.slideTo(idx);
      const slug = this.scenarios[idx].slug;
      document.dispatchEvent(new CustomEvent('scenario:select', { detail: { idx, slug } }));
      this._scrollToSliderSection();
    },
    tileClass(i) {
      return (this.activeIdx === i) ? 'ring-2 ring-orange-500' : 'ring-1 ring-black/10';
    }
  }
}
</script>
