@extends('layouts.app')

@section('title', 'Absensi Pegawai')

@section('content')

<div class="flex">

    {{-- Sidebar tetap di kiri --}}
    @include('admin.components.sidebar')

    {{-- Kolom kanan: header di atas, konten halaman di bawahnya --}}
    <div class="flex-1 flex flex-col min-h-screen">

        @include('admin.components.header')

        <main class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Halaman Dashboard</h1>

            {{-- Konten halaman --}}
        </main>

    </div>

</div>

@endsection