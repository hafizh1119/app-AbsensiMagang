{{-- resources/views/admin/data-master/jenis-izin/create.blade.php --}}
{{-- Modal Tambah Jenis Izin (di-include dari index.blade.php, memakai state Alpine milik index) --}}
@php $isCreate = old('_form') === 'create'; @endphp

<div x-show="createOpen" x-transition.opacity style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div x-show="createOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.outside="createOpen = false"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-left">

        <h3 class="text-base font-bold text-gray-900 mb-4">Tambah Jenis Izin</h3>

        <form action="{{ route('admin.data-master.jenis-izin.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="_form" value="create">

            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5">Nama Jenis Izin</label>
                <input type="text" name="nama_izin" x-model="form.nama_izin" placeholder="Contoh: Cuti Tahunan" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-sky-500 @if($isCreate) @error('nama_izin') border-rose-400 @enderror @endif">
                @if ($isCreate) @error('nama_izin')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror @endif
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" @click="createOpen = false"
                        class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 text-sm font-semibold px-4 py-2.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>