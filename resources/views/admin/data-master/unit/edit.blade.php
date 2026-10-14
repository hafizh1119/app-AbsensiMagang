{{-- resources/views/admin/data-master/unit/edit.blade.php --}}
{{-- Modal Edit Unit (di-include dari index.blade.php, memakai state Alpine milik index) --}}
<div x-show="editOpen"
     x-transition.opacity
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">

    <div x-show="editOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         @click.outside="editOpen = false"
         class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

        <h3 class="text-base font-bold text-gray-900 mb-4">Edit Unit</h3>

        <form :action="'{{ route('admin.data-master.unit.update', ['unit' => '__ID__']) }}'.replace('__ID__', form.id)"
              method="POST" class="space-y-4 text-sm text-left">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form" value="edit">
            <input type="hidden" name="id" :value="form.id">

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Nama Unit <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_unit" x-model="form.nama_unit" placeholder="Contoh: Struktural"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 @if(old('_form') === 'edit') @error('nama_unit') border-rose-400 @enderror @endif">
                @if (old('_form') === 'edit')
                    @error('nama_unit')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

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