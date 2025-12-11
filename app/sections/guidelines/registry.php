<div class="mt-6">
  <label for="search" class="sr-only">Пошук</label>
  <input id="search" type="search" placeholder="Ім'я або номер сертифікату" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-base outline-none ring-0 placeholder:text-gray-400 focus:border-[#698b91]">
</div>
<div id="tableWrap" class="mt-6 rounded-2xl border border-gray-200 bg-white p-3 sm:p-4 shadow-sm">
  <div class="overflow-x-auto">
    <table class="min-w-full text-left text-sm w-full">
      <thead class="border-b bg-gray-50 text-gray-600">
        <tr>
          <th class="px-4 py-3 font-semibold">ПІБ</th>
          <th class="px-4 py-3 font-semibold">Назва курсу</th>
          <th class="px-4 py-3 font-semibold">Годин</th>
          <th class="px-4 py-3 font-semibold">Номер</th>
          <th class="px-4 py-3 font-semibold">Дата видачі</th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr>
          <td colspan="5" class="px-4 py-10 text-center text-gray-600">Сертифікатів за цими даними не знайдено</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div id="status" class="py-8 text-center text-gray-600 hidden"></div>
  <nav id="pager" class="mt-4 flex items-center justify-center gap-1"></nav>
</div>
