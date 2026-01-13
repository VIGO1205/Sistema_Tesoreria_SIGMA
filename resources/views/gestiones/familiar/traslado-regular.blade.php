@extends('base.familiar.blank')

@section('titulo')
    Solicitud de Traslado Regular
@endsection

@section('contenido')
    <div class="p-8 m-4 bg-gray-100 dark:bg-white/[0.03] rounded-2xl">
        <!-- Header -->
        <div class="flex pb-6 justify-between items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <h2 class="text-2xl font-bold dark:text-gray-200 text-gray-800">Solicitud de Traslado Regular</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Vista de traslado regular</p>
            </div>
        </div>

        <div class="mt-8 text-center p-12">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                Funcionalidad en Desarrollo
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
                La funcionalidad de traslado regular estará disponible próximamente.
            </p>
            <a href="{{ route('principal') }}"
                class="inline-block mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                Volver al Inicio
            </a>
        </div>
    </div>
@endsection
