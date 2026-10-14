{{-- resources/views/admin/absensi/edit.blade.php --}}
{{-- Modal Edit Absensi (di-include dari semua.blade.php, memakai state Alpine milik semua) --}}
@php $isEdit = old('_form') === 'edit'; @endphp

<div x-show="editOpen" x-transition.opacity style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div x-show="editOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.outside="editOpen = false"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left max-h-[90vh] overflow-y-auto">

        <h3 class="text-base font-bold text-gray-900 mb-4">Edit Absensi</h3>

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

        <form :action="'{{ route('admin.absensi.update', ['id' => '__ID__']) }}'.replace('__ID__', form.id)"
             method="POST" class="space-y-4 text-sm">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form" value="edit">
            <input type="hidden" name="id" :value="form.id">
            <input type="hidden" name="info_nama" :value="form.nama">
            <input type="hidden" name="info_nip" :value="form.nip">

            {{-- Peserta (hanya tampil) --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Peserta</label>
                <div class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700">
                    <span class="font-medium" x-text="form.nama"></span>
                    <span class="text-gray-400"> &middot; </span>
                    <span class="text-gray-500" x-text="form.nip"></span>
                </div>
            </div>

            {{-- Tanggal --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" x-model="form.tanggal" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @if($isEdit) @error('tanggal') border-rose-400 @enderror @endif">
            </div>

            {{-- Jam Masuk & Jam Pulang --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Jam Masuk</label>
                    <input type="time" name="jam_masuk" x-model="form.jam_masuk"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @if($isEdit) @error('jam_masuk') border-rose-400 @enderror @endif">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Jam Pulang</label>
                    <input type="time" name="jam_pulang" x-model="form.jam_pulang"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 @if($isEdit) @error('jam_pulang') border-rose-400 @enderror @endif">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Status</label>
                <div class="relative">
                    <select name="status" x-model="form.status" required
                            class="w-full appearance-none border border-gray-200 rounded-lg pl-3 pr-9 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 @if($isEdit) @error('status') border-rose-400 @enderror @endif">
                        <option value="hadir">Hadir</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="cuti">Cuti</option>
                        <option value="izin_terlambat">Izin Terlambat</option>
                        <option value="tidak_hadir">Tidak Hadir</option>
                        <option value="alpha">Alpha</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Keterangan</label>
                <textarea name="keterangan" x-model="form.keterangan" rows="3" placeholder="Keterangan (opsional)"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none @if($isEdit) @error('keterangan') border-rose-400 @enderror @endif"></textarea>
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