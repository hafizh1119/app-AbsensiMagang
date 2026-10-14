{{-- resources/views/admin/data-master/jam-kerja/edit.blade.php --}}
{{-- Modal Edit Jam Kerja (di-include dari index.blade.php, memakai state Alpine milik index) --}}
@php $isEdit = old('_form') === 'edit'; @endphp

<div x-show="editOpen" x-transition.opacity style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div x-show="editOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.outside="editOpen = false"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left max-h-[90vh] overflow-y-auto">

        <h3 class="text-base font-bold text-gray-900 mb-1">Edit Jam Kerja</h3>
        <p class="text-xs text-gray-400 mb-4">Perbarui jadwal jam kerja hari <span class="font-semibold text-gray-600" x-text="form.hari"></span>.</p>

        @if ($isEdit && $errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-100 text-rose-600 text-xs rounded-xl px-4 py-3">
                <p class="font-semibold mb-1">Periksa kembali isian berikut:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form :action="'{{ route('admin.data-master.jam-kerja.update', ['jamKerja' => '__ID__']) }}'.replace('__ID__', form.id)"
              method="POST" class="space-y-4 text-sm">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form" value="edit">
            <input type="hidden" name="id" :value="form.id">

            {{-- Hari --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Hari</label>
                <div class="relative">
                    <select name="hari" x-model="form.hari"
                            class="w-full appearance-none border border-gray-200 rounded-lg pl-3 pr-9 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <template x-for="h in hariEdit()" :key="h">
                            <option :value="h" x-text="h" :selected="h === form.hari"></option>
                        </template>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="status" value="aktif" x-model="form.status"
                               class="w-4 h-4 text-sky-600 focus:ring-sky-500">
                        Aktif
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="radio" name="status" value="libur" x-model="form.status"
                               class="w-4 h-4 text-sky-600 focus:ring-sky-500">
                        Libur
                    </label>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Jam Masuk, Jam Pulang, Toleransi --}}
            <div x-show="form.status === 'aktif'" class="space-y-4">

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Jam Masuk</label>
                    <input type="time" name="jam_masuk" x-model="form.jam_masuk"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Jam Pulang</label>
                    <input type="time" name="jam_pulang" x-model="form.jam_pulang"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Toleransi Terlambat (Menit)</label>
                    <input type="number" name="toleransi_terlambat" min="0" x-model="form.toleransi_terlambat"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>

                <div class="flex items-start gap-2.5 bg-blue-50 text-blue-700 text-xs rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01"/>
                    </svg>
                    <p>Toleransi keterlambatan digunakan untuk menentukan batas maksimal keterlambatan saat absensi masuk.</p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="button" @click="editOpen = false"
                        class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>